// resources/js/components/crudTable.js

window.crudTable = function(config) {
    return {
        // ============================
        // 1. CONFIGURATION (ទទួលពី Blade)
        // ============================
        items: [],
        isLoading: false,
        search: '',
        
        // Config ដែលត្រូវបញ្ជូនមកពី Blade:
        // config.apiUrl (URL សម្រាប់ fetch)
        // config.createUrl (URL សម្រាប់ create)
        // config.deleteUrl (URL សម្រាប់ delete)
        // config.initialForm (ទម្រង់ Form ដើម)
        ...config, 

        // Pagination State
        pagination: { current_page: 1, last_page: 1, total: 0, prev_page_url: null, next_page_url: null },
        perPage: '10',

        // Bulk Actions
        selectedIds: [],
        selectAll: false,

        // Modal & Form
        isModalOpen: false,
        editMode: false,
        form: { ...config.initialForm }, // Copy form ដើម
        errors: {},

        // Sequence Mode (Optional)
        isSequenceMode: false,
        sequenceQueue: [],
        currentSeqIndex: 0,

        init() {
            // មើលការផ្លាស់ប្តូរ Column (បើមាន)
            if (this.showCols) {
                this.$watch('showCols', (value) => {
                    localStorage.setItem(this.tableName + '_cols', JSON.stringify(value));
                });
            }
            this.fetchData();
        },

        // ============================
        // 2. GENERIC FETCH FUNCTION
        // ============================
        async fetchData(url = null, pageNumber = null) {
            let baseUrl = this.apiUrl;
            const params = new URLSearchParams();

            if (url) {
                try {
                    const urlObj = new URL(url);
                    const page = urlObj.searchParams.get('page');
                    if (page) params.append('page', page);
                } catch (e) {}
            } else if (pageNumber) {
                params.append('page', pageNumber);
            }

            if(this.search) params.append('keyword', this.search);
            params.append('per_page', this.perPage);

            const finalUrl = `${baseUrl}?${params.toString()}`;
            this.isLoading = true;

            try {
                const response = await fetch(finalUrl);
                const data = await response.json();
                
                this.items = data.data; // ប្រើ items ជំនួស users
                
                this.pagination = {
                    total: data.total,
                    current_page: data.current_page,
                    last_page: data.last_page,
                    prev_page_url: data.prev_page_url,
                    next_page_url: data.next_page_url
                };
                
                this.selectedIds = [];
                this.selectAll = false;
            } catch (error) { console.error(error); }
            finally { this.isLoading = false; }
        },

        changePage(url) { if(url) this.fetchData(url); },

        // ============================
        // 3. GENERIC FORM HANDLING
        // ============================
        openModal(mode, item = null) {
            this.isSequenceMode = false;
            this.isModalOpen = true;
            this.errors = {};
            
            if (mode === 'edit') {
                this.editMode = true;
                // Copy ទិន្នន័យពី Item ចូល Form
                // (ត្រូវប្រាកដថា Key ក្នុង Table ដូចគ្នាជាមួយ Key ក្នុង Form)
                this.form = { ...item, password: '' }; 
            } else {
                this.editMode = false;
                this.form = { ...this.initialForm };
            }
        },

        async submitForm() {
            this.isLoading = true;
            this.errors = {};
            
            // កំណត់ URL និង Method ដោយស្វ័យប្រវត្តិ
            let url = this.editMode ? `${this.createUrl}/${this.form.id}` : this.createUrl; // សន្មតថា createUrl គឺ base ដូចគ្នា
            let method = this.editMode ? 'PUT' : 'POST';

            // ប្រសិនបើ URL ខុសគ្នាខ្លាំង អ្នកអាចដាក់ក្នុង config.updateUrl បាន

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
                    },
                    body: JSON.stringify(this.form)
                });
                const data = await response.json();

                if (!response.ok) {
                    if (response.status === 422) {
                        this.errors = data.errors;
                        window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'error', message: 'Please fix errors.' } }));
                    } else {
                        window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'error', message: data.message } }));
                        if (response.status === 403) this.closeModal(true);
                    }
                } else {
                    window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'success', message: data.message } }));
                    
                    if (this.isSequenceMode) {
                        this.nextInSequence();
                    } else {
                        this.isModalOpen = false;
                        this.fetchData();
                    }
                }
            } catch (error) { console.error(error); } 
            finally { this.isLoading = false; }
        },

        // ============================
        // 4. GENERIC DELETE
        // ============================
        async performDelete(ids, isBulk = false) {
            // Logic ដូចមុន គ្រាន់តែប្តូរ URL ទៅតាម config
            let url = isBulk ? this.deleteUrl : `${this.apiUrl}/${ids[0]}`; // អាចនឹងត្រូវកែតាម Route ជាក់ស្តែង
            let method = isBulk ? 'POST' : 'DELETE';
            
            // ... (Logic Delete នៅដដែល) ...
            // គ្រាន់តែពេលចប់ហៅ this.fetchData()
        },
        
        // Helper ផ្សេងៗ (toggleSelectAll, closeModal...) គឺដូចគ្នាគ្រាន់តែប្តូរ users ទៅ items
        toggleSelectAll() {
            this.selectedIds = this.selectAll ? this.items.map(item => item.id) : [];
        },
        
        // ...
    };
};