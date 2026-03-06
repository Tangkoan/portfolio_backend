@extends('admin.dashboard')
@section('title', 'Experiences Management')

@section('content')
<div class="w-full h-full px-2 py-2 sm:px-4 sm:py-4" x-data="experienceManagement()">
    
    {{-- ហៅឯកសារ Partials --}}
    @include('admin.portfolio.experiences.partials.header')
    
    <div class="hidden md:block">
        @include('admin.portfolio.experiences.partials.table')
    </div>
    
    <div class="md:hidden">
        @include('admin.portfolio.experiences.partials.mobile_card')
    </div>
    
    @include('admin.portfolio.experiences.partials.pagination')
    @include('admin.portfolio.experiences.partials.modal')

</div>

<script>
function experienceManagement() {
    return {
        experiences: [],
        search: '',
        perPage: 10,
        currentPage: 1,
        pagination: { last_page: 1, total: 0 },
        isModalOpen: false,
        editMode: false,
        isLoading: false,
        selectedIds: [],
        selectAll: false,

        openCol: false, 
        showCols: {
            company: true,
            duration: true,
            status: true
        },

        sortBy: 'created_at',
        sortDir: 'desc',

        form: {
            id: null,
            name: '',
            sup_name: '',
            start_day: '',
            end_day: '',
            status: 1
        },
        errors: {},

        init() { 
            this.fetchExperiences() 
        },

        // ទាញយកទិន្នន័យ (Fetch Data)
        async fetchExperiences(){
            let url = "{{ route('admin.experiences.fetch') }}"
            const params = new URLSearchParams({
                keyword: this.search, per_page: this.perPage,
                page: this.currentPage, sort_by: this.sortBy, sort_dir: this.sortDir
            })
            this.isLoading = true
            try{
                const response = await fetch(`${url}?${params}`)
                const data = await response.json()
                this.experiences = data.data
                this.pagination = data
                this.currentPage = data.current_page
                this.selectAll = false
                this.selectedIds = []
            }catch(e){ 
                console.error(e) 
            }
            finally{ 
                this.isLoading = false 
            }
        },

        // ប្តូរទំព័រ (Pagination)
        gotoPage(page){
            if(page === '...') return
            this.currentPage = page
            this.fetchExperiences()
        },

        // តម្រៀបទិន្នន័យ (Sorting)
        sort(col){
            if(this.sortBy === col){
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc'
            }else{
                this.sortBy = col
                this.sortDir = 'desc'
            }
            this.fetchExperiences()
        },

        // ជ្រើសរើសទាំងអស់ (Select All)
        toggleSelectAll(){
            this.selectedIds = this.selectAll ? this.experiences.map(t => t.id) : []
        },

        // បើក Modal បញ្ចូល/កែប្រែ
        openModal(mode, item = null){
            this.isModalOpen = true
            this.errors = {}
            if(mode === 'edit'){
                this.editMode = true
                this.form = {
                    id: item.id,
                    name: item.name,
                    sup_name: item.sup_name,
                    start_day: item.start_day,
                    end_day: item.end_day || '',
                    status: item.status
                }
            }else{
                this.editMode = false
                this.form = { id: null, name: '', sup_name: '', start_day: '', end_day: '', status: 1 }
            }
        },

        // បិទ Modal
        closeModal(){
            this.isModalOpen = false
            this.fetchExperiences()
            window.dispatchEvent(new Event('closeModalEvent'));
        },

        // បញ្ជូនទិន្នន័យ (Submit Create/Update)
        async submitForm(){
            this.isLoading = true; this.errors = {};
            let formData = new FormData();
            formData.append('name', this.form.name);
            formData.append('sup_name', this.form.sup_name);
            formData.append('start_day', this.form.start_day);
            formData.append('end_day', this.form.end_day);
            formData.append('status', this.form.status);

            let url = "{{ route('admin.experiences.store') }}";
            if(this.editMode){
                url = `/admin/experiences/${this.form.id}`;
                formData.append('_method','PUT');
            }

            try{
                const response = await fetch(url,{
                    method:'POST',
                    headers:{ 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                    body:formData
                })
                const data = await response.json()
                if(!response.ok){
                    if(response.status === 422) this.errors = data.errors;
                }else{
                    this.closeModal()
                    window.dispatchEvent(new CustomEvent('notify',{ detail:{type:'success',message:data.message} }))
                }
            }catch(e){ 
                console.error(e) 
            }
            finally{ 
                this.isLoading = false 
            }
        },

        // លុបទិន្នន័យ១ (Single Delete)
        async confirmDelete(id) {
            // ហៅ Modal បញ្ជាក់ (Custom Confirm) ជំនួសឱ្យពាក្យបញ្ជា confirm() របស់ Browser
            askConfirm(async () => {
                
                // កូដលុបនឹងដំណើរការពេល User ចុច "យល់ព្រម"
                try {
                    const response = await fetch(`/admin/experiences/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    if (!response.ok) throw new Error('Network response was not ok');

                    const data = await response.json();
                    
                    this.fetchExperiences(); // ទាញយកទិន្នន័យថ្មីមកបង្ហាញ
                    
                    // លោតសារជោគជ័យ
                    window.dispatchEvent(new CustomEvent('notify', {
                        detail: { type: 'success', message: data.message }
                    }));
                    
                } catch(e) {
                    console.error('Error deleting experience:', e);
                    alert('មានបញ្ហាក្នុងការលុបទិន្នន័យ សូមពិនិត្យមើល Backend របស់អ្នក។');
                }
                
            });
        },

        // បិទ/បើក ស្ថានភាព (Toggle Status)
        async toggleStatus(id){
            try{
                await fetch(`/admin/experiences/${id}/toggle`,{
                    method:'POST',
                    headers:{
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                this.fetchExperiences()
            }catch(e){
                console.error(e)
            }
        },

        // លុបទិន្នន័យច្រើន (Bulk Delete)
        async bulkDeleteSelected() {
            if(this.selectedIds.length === 0){
                alert('មិនមានទិន្នន័យត្រូវបានជ្រើសរើសទេ (No items selected)');
                return;
            }

            // ហៅ Modal បញ្ជាក់ (Confirm)
            askConfirm(async () => {
                
                // កូដលុបត្រូវយកមកដាក់ក្នុងនេះ 
                // វានឹងដំណើរការលុះត្រាតែ User ចុច "យល់ព្រម (Confirm)"
                try {
                    const response = await fetch(`/admin/experiences/bulk-delete`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ ids: this.selectedIds })
                    });

                    if (!response.ok) throw new Error('Network response was not ok');

                    const data = await response.json();

                    if(data.success){
                        this.selectedIds = [];
                        this.selectAll = false;
                        this.fetchExperiences(); // ហៅទិន្នន័យមកវិញ
                        window.dispatchEvent(new CustomEvent('notify',{
                            detail:{type:'success', message:data.message}
                        }));
                    }
                } catch(e) {
                    console.error('Error in bulk delete:', e);
                    alert('មានបញ្ហាក្នុងការលុបទិន្នន័យ សូមពិនិត្យមើល Backend របស់អ្នក។');
                }

            });
        },

        // កែប្រែទិន្នន័យច្រើន (Sequential Edit)
        startSequentialEdit() {
            const itemsToEdit = this.experiences.filter(t => this.selectedIds.some(id => id == t.id));

            if (itemsToEdit.length === 0) {
                alert('សូមជ្រើសរើសទិន្នន័យដែលអ្នកចង់កែប្រែ (Please select items to edit)');
                return;
            }

            let index = 0;

            const next = () => {
                if (index >= itemsToEdit.length) {
                    this.selectedIds = [];
                    this.selectAll = false;
                    return;
                }

                const exp = itemsToEdit[index];
                index++;

                this.openModal('edit', exp);

                const handler = () => {
                    next(); 
                    window.removeEventListener('closeModalEvent', handler);
                };
                window.addEventListener('closeModalEvent', handler);
            }

            next();
        },

        // រំលងពេលកំពុងកែប្រែ (Skip Edit)
        skipEdit(){
            this.isModalOpen = false;
            window.dispatchEvent(new Event('closeModalEvent'));
        }
    }
}
</script>
@endsection