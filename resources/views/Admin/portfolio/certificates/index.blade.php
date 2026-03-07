@extends('admin.dashboard')
@section('title', 'Certifications Management')

@section('content')
<div class="w-full h-full px-2 py-2 sm:px-4 sm:py-4" x-data="certificateManagement()">
    
    @include('admin.portfolio.certificates.partials.header')
    
    <div class="hidden md:block">
        @include('admin.portfolio.certificates.partials.table')
    </div>
    
    <div class="md:hidden">
        @include('admin.portfolio.certificates.partials.mobile_card')
    </div>
    
    @include('admin.portfolio.projects.partials.pagination')
    @include('admin.portfolio.certificates.partials.modal')
    @include('admin.portfolio.projects.partials.delete_modal')

</div>

<script>
function certificateManagement() {
    return {
        items: [],
        search: '',
        perPage: 10,
        currentPage: 1,
        pagination: { last_page: 1, total: 0 },
        
        isModalOpen: false,
        editMode: false,
        isLoading: false,
        
        isDeleteModalOpen: false,
        deleteType: '',
        deleteTargetId: null,

        selectedIds: [],
        selectAll: false,

        openCol: false, 
        showCols: { image: true, date: true, status: true },

        sortBy: 'created_at',
        sortDir: 'desc',

        form: { id: null, image: null, status: 1 },
        imagePreview: null,
        errors: {},

        init() { this.fetchData() },

        async fetchData(){
            let url = "{{ route('admin.certificates.fetch') }}"
            const params = new URLSearchParams({
                keyword: this.search, per_page: this.perPage,
                page: this.currentPage, sort_by: this.sortBy, sort_dir: this.sortDir
            })
            this.isLoading = true
            try{
                const response = await fetch(`${url}?${params}`)
                const data = await response.json()
                this.items = data.data
                this.pagination = data
                this.currentPage = data.current_page
                this.selectAll = false
                this.selectedIds = []
            }catch(e){ console.error(e) }
            finally{ this.isLoading = false }
        },

        gotoPage(page){
            if(page === '...') return
            this.currentPage = page
            this.fetchData()
        },

        sort(col){
            if(this.sortBy === col){
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc'
            }else{
                this.sortBy = col
                this.sortDir = 'desc'
            }
            this.fetchData()
        },

        toggleSelectAll(){
            this.selectedIds = this.selectAll ? this.items.map(t => t.id) : []
        },

        handleFileUpload(e){
            const file = e.target.files[0]
            if(file){
                this.form.image = file
                this.imagePreview = URL.createObjectURL(file)
            }
        },

        openModal(mode, item = null){
            this.isModalOpen = true
            this.errors = {}
            if(mode === 'edit'){
                this.editMode = true
                this.form = { id: item.id, image: null, status: item.status }
                this.imagePreview = item.image ? '/storage/' + item.image : null
            }else{
                this.editMode = false
                this.form = { id: null, image: null, status: 1 }
                this.imagePreview = null
                document.getElementById('cert_image_input').value = '';
            }
        },

        closeModal(){
            this.isModalOpen = false
            this.fetchData()
            window.dispatchEvent(new Event('closeModalEvent'));
        },

        async submitForm(){
            this.isLoading = true; this.errors = {};
            let formData = new FormData();
            formData.append('status', this.form.status);
            if(this.form.image instanceof File) formData.append('image', this.form.image);

            let url = "{{ route('admin.certificates.store') }}";
            if(this.editMode){
                url = `/admin/certificates/${this.form.id}`;
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
            }catch(e){ console.error(e) }
            finally{ this.isLoading = false }
        },

        async toggleStatus(id){
            try{
                await fetch(`/admin/certificates/${id}/toggle`,{
                    method:'POST',
                    headers:{ 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                })
                this.fetchData()
            }catch(e){ console.error(e) }
        },

        startSequentialEdit() {
            const itemsToEdit = this.items.filter(t => this.selectedIds.some(id => id == t.id));
            if (itemsToEdit.length === 0) return alert('សូមជ្រើសរើសទិន្នន័យ!');
            let index = 0;
            const next = () => {
                if (index >= itemsToEdit.length) { this.selectedIds = []; this.selectAll = false; return; }
                this.openModal('edit', itemsToEdit[index++]);
                const handler = () => { next(); window.removeEventListener('closeModalEvent', handler); };
                window.addEventListener('closeModalEvent', handler);
            }
            next();
        },

        skipEdit(){ this.closeModal(); },

        openDeleteModal(type, id = null) {
            if (type === 'bulk' && this.selectedIds.length === 0) return alert('សូមជ្រើសរើសទិន្នន័យសិន!');
            this.deleteType = type; this.deleteTargetId = id; this.isDeleteModalOpen = true;
        },

        closeDeleteModal() { this.isDeleteModalOpen = false; this.deleteTargetId = null; },

        async executeDelete() {
            this.isLoading = true;
            try {
                let url = this.deleteType === 'single' ? `/admin/certificates/${this.deleteTargetId}` : `/admin/certificates/bulk-delete`;
                let options = {
                    method: this.deleteType === 'single' ? 'DELETE' : 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'), 'Content-Type': 'application/json' }
                };
                if(this.deleteType === 'bulk') options.body = JSON.stringify({ ids: this.selectedIds });

                const response = await fetch(url, options);
                const data = await response.json();
                
                if(this.deleteType === 'bulk') { this.selectedIds = []; this.selectAll = false; }
                window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'success', message: data.message } }));
                this.fetchData(); this.closeDeleteModal(); 
            } catch (e) { console.error(e); } finally { this.isLoading = false; }
        }
    }
}
</script>
@endsection