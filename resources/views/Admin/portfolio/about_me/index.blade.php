@extends('admin.dashboard')
@section('title', 'About Me Management')

@section('content')
<div class="w-full h-full px-2 py-2 sm:px-4 sm:py-4" x-data="aboutMeManagement()">
    
    @include('admin.portfolio.about_me.partials.header')
    
    <div class="hidden md:block">
        @include('admin.portfolio.about_me.partials.table')
    </div>
    
    <div class="md:hidden">
        @include('admin.portfolio.about_me.partials.mobile_card')
    </div>
    
    @include('admin.portfolio.projects.partials.pagination')
    @include('admin.portfolio.about_me.partials.modal')
    @include('admin.portfolio.projects.partials.delete_modal')

</div>

<script>
function aboutMeManagement() {
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
        showCols: {
            image: true,
            description: true,
            status: true
        },

        sortBy: 'created_at',
        sortDir: 'desc',

        form: {
            id: null,
            name: '',
            description: '',
            image: null,
            status: 1
        },
        imagePreview: null,
        errors: {},

        init() { this.fetchData() },

        async fetchData(){
            let url = "{{ route('admin.about_me.fetch') }}"
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
                this.form = {
                    id: item.id,
                    name: item.name,
                    description: item.description || '',
                    image: null,
                    status: item.status
                }
                this.imagePreview = item.image ? '/storage/' + item.image : null
            }else{
                this.editMode = false
                this.form = { id: null, name: '', description: '', image: null, status: 1 }
                this.imagePreview = null
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
            formData.append('name', this.form.name);
            formData.append('description', this.form.description);
            formData.append('status', this.form.status);
            
            if(this.form.image instanceof File){
                formData.append('image', this.form.image);
            }

            let url = "{{ route('admin.about_me.store') }}";
            if(this.editMode){
                url = `/admin/about-me/${this.form.id}`;
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
                await fetch(`/admin/about-me/${id}/toggle`,{
                    method:'POST',
                    headers:{ 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                })
                this.fetchData()
            }catch(e){ console.error(e) }
        },

        startSequentialEdit() {
            const itemsToEdit = this.items.filter(t => this.selectedIds.some(id => id == t.id));
            if (itemsToEdit.length === 0) {
                alert('សូមជ្រើសរើសទិន្នន័យដែលអ្នកចង់កែប្រែ (Please select items to edit)');
                return;
            }
            let index = 0;
            const next = () => {
                if (index >= itemsToEdit.length) {
                    this.selectedIds = []; this.selectAll = false; return;
                }
                const item = itemsToEdit[index];
                index++;
                this.openModal('edit', item);
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
                    const response = await fetch(`/admin/about-me/${this.deleteTargetId}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                    });
                    const data = await response.json();
                    window.dispatchEvent(new CustomEvent('notify', { detail: { type: 'success', message: data.message } }));
                } 
                else if (this.deleteType === 'bulk') {
                    const response = await fetch(`/admin/about-me/bulk-delete`, {
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
                this.fetchData(); 
                this.closeDeleteModal(); 
            } catch (e) { console.error(e); } 
            finally { this.isLoading = false; }
        }
    }
}
</script>
@endsection