@extends('admin.dashboard')
@section('title', 'Projects Management')

@section('content')
<div class="w-full h-full px-2 py-2 sm:px-4 sm:py-4" x-data="projectManagement()">
    
    @include('admin.portfolio.projects.partials.header')
    
    <div class="hidden md:block">
        @include('admin.portfolio.projects.partials.table')
    </div>
    
    <div class="md:hidden">
        @include('admin.portfolio.projects.partials.mobile_card')
    </div>
    
    @include('admin.portfolio.projects.partials.pagination')
    @include('admin.portfolio.projects.partials.modal')
    @include('admin.portfolio.projects.partials.delete_modal')

</div>

<script>
function projectManagement() {
    return {
        projects: [],
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
            subtitle: true,
            link: true,
            status: true
        },

        sortBy: 'created_at',
        sortDir: 'desc',

        form: {
            id: null,
            name: '',
            sup_name: '',
            url_project: '',
            image: null,
            status: 1
        },
        imagePreview: null,
        errors: {},

        init() { this.fetchProjects() },

        async fetchProjects(){
            let url = "{{ route('admin.projects.fetch') }}"
            const params = new URLSearchParams({
                keyword: this.search, per_page: this.perPage,
                page: this.currentPage, sort_by: this.sortBy, sort_dir: this.sortDir
            })
            this.isLoading = true
            try{
                const response = await fetch(`${url}?${params}`)
                const data = await response.json()
                this.projects = data.data
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
            this.fetchProjects()
        },

        sort(col){
            if(this.sortBy === col){
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc'
            }else{
                this.sortBy = col
                this.sortDir = 'desc'
            }
            this.fetchProjects()
        },

        toggleSelectAll(){
            this.selectedIds = this.selectAll ? this.projects.map(t => t.id) : []
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
                    sup_name: item.sup_name || '',
                    url_project: item.url_project || '',
                    image: null,
                    status: item.status
                }
                this.imagePreview = item.image ? '/storage/' + item.image : null
            }else{
                this.editMode = false
                this.form = { id: null, name: '', sup_name: '', url_project: '', image: null, status: 1 }
                this.imagePreview = null
            }
        },

        closeModal(){
            this.isModalOpen = false
            this.fetchProjects()
            window.dispatchEvent(new Event('closeModalEvent'));
        },

        async submitForm(){
            this.isLoading = true; this.errors = {};
            let formData = new FormData();
            formData.append('name', this.form.name);
            formData.append('sup_name', this.form.sup_name);
            formData.append('url_project', this.form.url_project);
            formData.append('status', this.form.status);
            
            if(this.form.image instanceof File){
                formData.append('image', this.form.image);
            }

            let url = "{{ route('admin.projects.store') }}";
            if(this.editMode){
                url = `/admin/projects/${this.form.id}`;
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
                await fetch(`/admin/projects/${id}/toggle`,{
                    method:'POST',
                    headers:{ 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                })
                this.fetchProjects()
            }catch(e){ console.error(e) }
        },

        startSequentialEdit() {
            const itemsToEdit = this.projects.filter(t => this.selectedIds.some(id => id == t.id));
            if (itemsToEdit.length === 0) {
                alert('សូមជ្រើសរើសទិន្នន័យដែលអ្នកចង់កែប្រែ (Please select items to edit)');
                return;
            }
            let index = 0;
            const next = () => {
                if (index >= itemsToEdit.length) {
                    this.selectedIds = []; this.selectAll = false; return;
                }
                const proj = itemsToEdit[index];
                index++;
                this.openModal('edit', proj);
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
                    const response = await fetch(`/admin/projects/${this.deleteTargetId}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                    });
                    const data = await response.json();
                    window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'success', message: data.message } }));
                } 
                else if (this.deleteType === 'bulk') {
                    const response = await fetch(`/admin/projects/bulk-delete`, {
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
                this.fetchProjects(); 
                this.closeDeleteModal(); 
            } catch (e) { console.error(e); } 
            finally { this.isLoading = false; }
        }
    }
}
</script>
@endsection