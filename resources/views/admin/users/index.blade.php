<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">Manajemen User</h2>
    </x-slot>

    @if(session('success')) <x-toast type="success" :message="session('success')" /> @endif
    @if(session('error')) <x-toast type="error" :message="session('error')" /> @endif
    @if($errors->any()) <x-toast type="error" message="Terdapat kesalahan input data." /> @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden animate-fade-in" x-data="{ 
        showModal: false, 
        isEdit: false,
        form: { id: '', name: '', email: '', role_id: '' },
        openCreateModal() { this.isEdit = false; this.form = { id: '', name: '', email: '', role_id: '' }; this.showModal = true; },
        openEditModal(user) { this.isEdit = true; this.form = { id: user.id, name: user.name, email: user.email, role_id: user.role_id }; this.showModal = true; }
    }">
        <div class="p-4 border-b border-gray-100 flex justify-between bg-gray-50/50 items-center">
            <h3 class="font-semibold text-gray-700">Daftar Karyawan / Pengguna</h3>
            <button @click="openCreateModal()" class="flex items-center bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm text-sm">
                + Tambah User
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">Pengguna</th>
                        <th class="px-6 py-4 text-center">Role</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-center uppercase text-xs tracking-wider">{{ $user->role->name }}</td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ url('admin/users/'.$user->id.'/toggle') }}" method="POST">
                                @csrf
                                <button type="submit" class="px-2 py-1 rounded text-xs font-bold {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $user->is_active ? 'AKTIF' : 'NONAKTIF' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button @click="openEditModal({{ $user->toJson() }})" class="text-primary-600 hover:text-primary-900 font-medium text-xs bg-primary-50 px-2 py-1 rounded">Edit</button>
                                <form action="{{ url('admin/users/'.$user->id) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin hapus?')" class="text-red-600 hover:text-red-900 font-medium text-xs bg-red-50 px-2 py-1 rounded">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm" style="display: none;">
            <div @click.outside="showModal = false" class="bg-white rounded-2xl w-full max-w-md animate-slide-up">
                <div class="px-6 py-4 border-b bg-gray-50 rounded-t-2xl flex justify-between">
                    <h3 class="font-bold text-gray-800" x-text="isEdit ? 'Edit User' : 'Tambah User'"></h3>
                    <button @click="showModal = false">&times;</button>
                </div>
                <form :action="isEdit ? '{{ url('admin/users') }}/' + form.id : '{{ route('admin.users.store') }}'" method="POST" class="p-6 space-y-4">
                    @csrf
                    <template x-if="isEdit"><input type="hidden" name="_method" value="PUT"></template>
                    <div>
                        <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
                        <input type="text" name="name" x-model="form.name" required class="w-full rounded-lg border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" name="email" x-model="form.email" required class="w-full rounded-lg border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Role / Peran</label>
                        <select name="role_id" x-model="form.role_id" required class="w-full rounded-lg border-gray-300 text-sm">
                            <option value="">-- Pilih --</option>
                            @foreach($roles as $role) <option value="{{ $role->id }}">{{ $role->display_name }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Password <span x-show="isEdit" class="text-xs text-gray-400 font-normal">(Kosongkan jika tidak ubah)</span></label>
                        <input type="password" name="password" :required="!isEdit" class="w-full rounded-lg border-gray-300 text-sm">
                    </div>
                    <div class="pt-4 flex justify-end gap-2 border-t">
                        <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-100 rounded-lg text-sm">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-bold">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>