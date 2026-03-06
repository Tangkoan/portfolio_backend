@extends('admin.dashboard')

@section('title', __('messages.technologies_management'))

@section('content')

<div class="w-full h-full px-2 py-2 sm:px-4 sm:py-4" x-data="technologyManagement()">

    {{-- HEADER --}}
    @include('admin.portfolio.technologies.partials.header')

    {{-- TABLE --}}
    <div class="hidden md:block">
        @include('admin.portfolio.technologies.partials.table')
    </div>

    {{-- MOBILE CARD --}}
    <div class="md:hidden">
        @include('admin.portfolio.technologies.partials.mobile_card')
    </div>

    {{-- PAGINATION --}}
    @include('admin.portfolio.technologies.partials.pagination')

    {{-- MODAL --}}
    @include('admin.portfolio.technologies.partials.modal')

</div>

<script>
function technologyManagement() {
    return {
        search: '',
        perPage: 10,
        currentPage: 1,

        pagination: {
            last_page: 1,
            total: 0
        },

        isModalOpen: false,
        editMode: false,
        isLoading: false,

        selectedIds: [],
        selectAll: false,

        // 1. បន្ថែមអថេរសម្រាប់បិទ/បើក Dropdown
        openCol: false, 

        // 2. បន្ថែមអថេរសម្រាប់កំណត់ថាតើ Column ណាបើក ឬបិទ (Defaut គឺ true ទាំងអស់)
        showCols: {
            image: true,
            status: true,
            created: true
        },

        sortBy: 'created_at',
        sortDir: 'desc',

        form: {
            id: null,
            name: '',
            image: null
        },

        imagePreview: null,
        errors: {},


        init() {
            this.fetchTechnologies()
        },


        async fetchTechnologies(){

            let url = "{{ route('admin.technologies.fetch') }}"

            const params = new URLSearchParams({
                keyword: this.search,
                per_page: this.perPage,
                page: this.currentPage,
                sort_by: this.sortBy,
                sort_dir: this.sortDir
            })

            this.isLoading = true

            try{

                const response = await fetch(`${url}?${params}`)
                const data = await response.json()

                this.technologies = data.data
                this.pagination = data
                this.currentPage = data.current_page

                // Reset selection when fetching new data
                this.selectAll = false
                this.selectedIds = []

            }catch(e){
                console.error(e)
            }
            finally{
                this.isLoading = false
            }

        },


        gotoPage(page){
            if(page === '...') return
            this.currentPage = page
            this.fetchTechnologies()
        },


        sort(col){

            if(this.sortBy === col){
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc'
            }else{
                this.sortBy = col
                this.sortDir = 'desc'
            }

            this.fetchTechnologies()

        },


        toggleSelectAll(){
            this.selectedIds = this.selectAll ? this.technologies.map(t => t.id) : []
        },


        handleFileUpload(e){

            const file = e.target.files[0]

            if(file){
                this.form.image = file
                this.imagePreview = URL.createObjectURL(file)
            }

        },
        
        async bulkDeleteSelected() {
            if(this.selectedIds.length === 0){
                alert('មិនមានទិន្នន័យត្រូវបានជ្រើសរើសទេ (No items selected)')
                return
            }

            if(!confirm('តើអ្នកពិតជាចង់លុបទិន្នន័យដែលបានជ្រើសរើសមែនទេ? (Are you sure you want to delete selected technologies?)')) return

            try {
                const response = await fetch(`/admin/technologies/bulk-delete`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ ids: this.selectedIds })
                })

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json()

                if(data.success){
                    this.selectedIds = []
                    this.selectAll = false
                    this.fetchTechnologies()
                    window.dispatchEvent(new CustomEvent('notify',{
                        detail:{type:'success', message:data.message}
                    }))
                }
            } catch(e) {
                console.error('Error in bulk delete:', e)
                alert('មានបញ្ហាក្នុងការលុបទិន្នន័យ សូមពិនិត្យមើល Backend របស់អ្នក។')
            }
        },

        startSequentialEdit() {
            // ទាញយកតែទិន្នន័យណាដែលត្រូវបាន Select ប៉ុណ្ណោះ
            // const itemsToEdit = this.technologies.filter(t => this.selectedIds.includes(t.id));
            const itemsToEdit = this.technologies.filter(t => this.selectedIds.some(id => id == t.id));

            if (itemsToEdit.length === 0) {
                alert('សូមជ្រើសរើសទិន្នន័យដែលអ្នកចង់កែប្រែ (Please select items to edit)');
                return;
            }

            let index = 0;

            const next = () => {
                if (index >= itemsToEdit.length) {
                    // បញ្ចប់ការ Edit លុប Selection ចោល
                    this.selectedIds = [];
                    this.selectAll = false;
                    return;
                }

                const tech = itemsToEdit[index];
                index++;

                // Open modal in edit mode
                this.openModal('edit', tech);

                // ចាប់ Event នៅលើ window ព្រោះខាងក្រោមយើង dispatch ទៅ window
                const handler = () => {
                    next(); // បើក modal សម្រាប់ item បន្ទាប់
                    window.removeEventListener('closeModalEvent', handler);
                };
                window.addEventListener('closeModalEvent', handler);
            }

            next();
        },


        openModal(mode, item = null){

            this.isModalOpen = true
            this.errors = {}

            if(mode === 'edit'){

                this.editMode = true

                this.form = {
                    id: item.id,
                    name: item.name,
                    image: null
                }

                this.imagePreview = item.image ? '/storage/' + item.image : null

            }else{

                this.editMode = false

                this.form = {
                    id: null,
                    name: '',
                    image: null
                }

                this.imagePreview = null

            }

        },


        closeModal(){

            this.isModalOpen = false
            this.fetchTechnologies()
            // Dispatch event ទៅ window សម្រាប់ sequential edit ដំណើរការ
            window.dispatchEvent(new Event('closeModalEvent'));
        },

        // បន្ថែម Function ថ្មីនេះ
        skipEdit(){
            this.isModalOpen = false
            
            // Dispatch event ដើម្បីហៅទិន្នន័យបន្ទាប់មក Edit 
            // (យើងមិនហៅ this.fetchTechnologies() ទេ ដើម្បីសន្សំសំចៃពេល)
            window.dispatchEvent(new Event('closeModalEvent'));
        },


        async submitForm(){

            this.isLoading = true
            this.errors = {}

            let formData = new FormData()

            formData.append('name', this.form.name)

            if(this.form.image instanceof File){
                formData.append('image', this.form.image)
            }

            let url = "{{ route('admin.technologies.store') }}"

            if(this.editMode){

                url = `/admin/technologies/${this.form.id}`

                formData.append('_method','PUT')

            }

            try{

                const response = await fetch(url,{
                    method:'POST',
                    headers:{
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body:formData
                })

                const data = await response.json()

                if(!response.ok){

                    if(response.status === 422){
                        this.errors = data.errors
                    }

                }else{

                    this.closeModal()
                    // មិនបាច់ fetchTechnologies() ទីនេះទេ ព្រោះក្នុង closeModal() មានហៅរួចហើយ

                    window.dispatchEvent(new CustomEvent('notify',{
                        detail:{type:'success',message:data.message}
                    }))

                }

            }catch(e){
                console.error(e)
            }
            finally{
                this.isLoading = false
            }

        },


        async confirmDelete(id){

            if(!confirm("តើអ្នកពិតជាចង់លុបទិន្នន័យមួយនេះមែនទេ? (Delete this technology?)")) return

            try{

                const response = await fetch(`/admin/technologies/${id}`,{
                    method:'DELETE',
                    headers:{
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })

                const data = await response.json()

                this.fetchTechnologies()

                window.dispatchEvent(new CustomEvent('notify',{
                    detail:{type:'success',message:data.message}
                }))

            }catch(e){
                console.error(e)
            }

        },


        async toggleStatus(id){

            try{

                await fetch(`/admin/technologies/${id}/toggle`,{
                    method:'POST',
                    headers:{
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })

                this.fetchTechnologies()

            }catch(e){
                console.error(e)
            }

        }

    }
}
</script>
@endsection