@extends('admin.dashboard')
@section('title', 'Tools Management')

@section('content')
<div class="w-full h-full px-2 py-2 sm:px-4 sm:py-4" x-data="toolManagement()">
    
    @include('admin.portfolio.tools.partials.header')
    
    <div class="hidden md:block">
        @include('admin.portfolio.tools.partials.table')
    </div>
    
    <div class="md:hidden">
        @include('admin.portfolio.tools.partials.mobile_card')
    </div>
    
    @include('admin.portfolio.projects.partials.pagination') {{-- ប្រើ Pagination ចាស់បាន --}}
    @include('admin.portfolio.tools.partials.modal')
    @include('admin.portfolio.projects.partials.delete_modal') {{-- ប្រើ Delete Modal ចាស់បាន --}}

</div>

<script>
function toolManagement() {
    return {
        tools: [],
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
        showCols: {
            image: true,
            status: true
        },

        sortBy: 'created_at',
        sortDir: 'desc',

        form: {
            id: null,
            name: '',
            image: null,
            status: 1
        },
        imagePreview: null,
        errors: {},

        init() { this.fetchTools() },

        async fetchTools(){
            let url = "{{ route('admin.tools.fetch') }}"
            const params = new URLSearchParams({
                keyword: this.search, per_page: this.perPage,
                page: this.currentPage, sort_by: this.sortBy, sort_dir: this.sortDir
            })
            this.isLoading = true
            try{
                const response = await fetch(`${url}?${params}`)
                const data = await response.json()
                this.tools = data.data
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
            this.fetchTools()
        },

        sort(col){
            if(this.sortBy === col){
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc'
            }else{
                this.sortBy = col
                this.sortDir = 'desc'
            }
            this.fetchTools()
        },

        toggleSelectAll(){
            this.selectedIds = this.selectAll ? this.tools.map(t => t.id) : []
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
                this.form = {
                    id: item.id,
                    name: item.name,
                    image: null,
                    status: item.status
                }
                this.imagePreview = item.image ? '/storage/' + item.image : null
            }else{
                this.editMode = false
                this.form = { id: null, name: '', image: null, status: 1 }
                this.imagePreview = null
            }
        },

        closeModal(){
            this.isModalOpen = false
            this.fetchTools()
            window.dispatchEvent(new Event('closeModalEvent'));
        },

        async submitForm(){
            this.isLoading = true; this.errors = {};
            let formData = new FormData();
            formData.append('name', this.form.name);
            formData.append('status', this.form.status);
            
            if(this.form.image instanceof File){
                formData.append('image', this.form.image);
            }

            let url = "{{ route('admin.tools.store') }}";
            if(this.editMode){
                url = `/admin/tools/${this.form.id}`; // ដូរ path
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
                await fetch(`/admin/tools/${id}/toggle`,{ // ដូរ path
                    method:'POST',
                    headers:{ 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                })
                this.fetchTools()
            }catch(e){ console.error(e) }
        },

        startSequentialEdit() {
            const itemsToEdit = this.tools.filter(t => this.selectedIds.some(id => id == t.id));
            if (itemsToEdit.length === 0) {
                alert('សូមជ្រើសរើសទិន្នន័យដែលអ្នកចង់កែប្រែ (Please select items to edit)');
                return;
            }
            let index = 0;
            const next = () => {
                if (index >= itemsToEdit.length) {
                    this.selectedIds = []; this.selectAll = false; return;
                }
                const tool = itemsToEdit[index];
                index++;
                this.openModal('edit', tool);
                const handler = () => {
                    next(); 
                    window.removeEventListener('closeModalEvent', handler);
                };
                window.addEventListener('closeModalEvent', handler);
            }
            next();
        },

        skipEdit(){
            this.isModalOpen = false;
            window.dispatchEvent(new Event('closeModalEvent'));
        },

        openDeleteModal(type, id = null) {
            if (type === 'bulk' && this.selectedIds.length === 0) {
                alert('សូមជ្រើសរើសទិន្នន័យដែលអ្នកចង់លុបសិន!'); return;
            }
            this.deleteType = type;
            this.deleteTargetId = id;
            this.isDeleteModalOpen = true;
        },

        closeDeleteModal() {
            this.isDeleteModalOpen = false;
            this.deleteTargetId = null;
        },

        async executeDelete() {
            this.isLoading = true;
            try {
                if (this.deleteType === 'single') {
                    const response = await fetch(`/admin/tools/${this.deleteTargetId}`, { // ដូរ path
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                    });
                    const data = await response.json();
                    window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'success', message: data.message } }));
                } 
                else if (this.deleteType === 'bulk') {
                    const response = await fetch(`/admin/tools/bulk-delete`, { // ដូរ path
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ ids: this.selectedIds })
                    });
                    const data = await response.json();
                    this.selectedIds = []; this.selectAll = false;
                    window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'success', message: data.message } }));
                }
                this.fetchTools(); 
                this.closeDeleteModal(); 
            } catch (e) { console.error(e); } 
            finally { this.isLoading = false; }
        }
    }
}
</script>
@endsection