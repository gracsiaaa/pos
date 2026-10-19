<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Manajemen Kategori') }}
        </h2>
    </x-slot>

    <!-- Inject Toast for messages -->
    @if(session('success'))
        <x-toast type="success" :message="session('success')" />
    @endif
    @if(session('error'))
        <x-toast type="error" :message="session('error')" />
    @endif
    @if($errors->any())
        <x-toast type="error" message="Terdapat kesalahan input data." />
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden animate-fade-in" x-data="{ 
        showModal: false, 
        isEdit: false,
        form: { id: '', name: '', slug: '', description: '' },
        openCreateModal() {
            this.isEdit = false;
            this.form = { id: '', name: '', slug: '', description: '' };
            this.showModal = true;
        },
        openEditModal(cat) {
            this.isEdit = true;
            this.form = { id: cat.id, name: cat.name, slug: cat.slug, description: cat.description || '' };
            this.showModal = true;
        },
        generateSlug() {
            if(!this.isEdit) {
                this.form.slug = this.form.name.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
            }
        }
    }">
        
        <div class="p-4 border-b border-gray-100 flex justify-between bg-gray-50/50 items-center">
            <h3 class="font-semibold text-gray-700">Daftar Kategori Produk</h3>
            <button @click="openCreateModal()" class="flex items-center bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition-colors text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Kategori
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Nama Kategori</th>
                        <th class="px-6 py-4 font-semibold">Slug</th>
                        <th class="px-6 py-4 font-semibold">Deskripsi</th>
                        <th class="px-6 py-4 font-semibold text-center">Jml Produk</th>
                        <th class="px-6 py-4 font-semibold text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr class="bg-white border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $category->name }}</td>
                        <td class="px-6 py-4">{{ $category->slug }}</td>
                        <td class="px-6 py-4 truncate max-w-xs">{{ $category->description ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-blue-50 text-blue-600 text-xs font-bold px-2.5 py-1 rounded-md">{{ $category->products_count }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="openEditModal({{ $category->toJson() }})" class="p-2 text-primary-600 hover:bg-primary-50 rounded-lg transition-colors tooltip">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" onclick="return confirm('Hapus kategori ini?')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">Belum ada kategori.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Alpine Modal -->
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm" style="display: none;">
            <div @click.outside="showModal = false" class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden animate-slide-up">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="font-bold text-gray-800" x-text="isEdit ? 'Edit Kategori' : 'Tambah Kategori'"></h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
                
                <form :action="isEdit ? '{{ url('admin/categories') }}/' + form.id : '{{ route('admin.categories.store') }}'" method="POST" class="p-6 space-y-4">
                    @csrf
                    <template x-if="isEdit"><input type="hidden" name="_method" value="PUT"></template>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                        <input type="text" name="name" x-model="form.name" @input="generateSlug()" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                        <input type="text" name="slug" x-model="form.slug" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm bg-gray-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <textarea name="description" x-model="form.description" rows="3" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm"></textarea>
                    </div>
                    
                    <div class="pt-4 flex justify-end gap-2">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm font-bold text-white bg-primary-600 rounded-lg hover:bg-primary-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>