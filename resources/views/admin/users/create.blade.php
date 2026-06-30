@extends('layouts.master')
@section('title', 'Invite User')

@section('content')
<div class="p-8 bg-[#f8f9fa] min-h-screen">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.users.index') }}" class="w-10 h-10 bg-white border border-gray-200 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-gray-900 transition shadow-sm">
            <i class="bi bi-arrow-left text-lg"></i>
        </a>
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Invite New User</h1>
    </div>

    <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] max-w-3xl">
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-orange-500 @error('name') border-red-500 @enderror" required>
                    @error('name') <span class="text-red-500 text-xs font-bold mt-1">{{ $message }}</span> @enderror
                </div>
                
                <!-- Email -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-orange-500 @error('email') border-red-500 @enderror" required>
                    @error('email') <span class="text-red-500 text-xs font-bold mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">Role <span class="text-red-500">*</span></label>
                    <select name="role" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-orange-500" required>
                        <option value="" disabled selected>Select Role</option>
                        <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                        <option value="cashier" {{ old('role') == 'cashier' ? 'selected' : '' }}>Cashier</option>
                        <option value="kitchen" {{ old('role') == 'kitchen' ? 'selected' : '' }}>Kitchen Staff</option>
                        <option value="courier" {{ old('role') == 'courier' ? 'selected' : '' }}>Courier</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    </select>
                    @error('role') <span class="text-red-500 text-xs font-bold mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Password -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-900 mb-2">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:ring-2 focus:ring-orange-500 @error('password') border-red-500 @enderror" required>
                    @error('password') <span class="text-red-500 text-xs font-bold mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Upload Foto -->
            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-900 mb-2">Profile Picture</label>
                <input type="file" name="profile_picture" accept="image/*" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-orange-50 file:text-orange-500 hover:file:bg-orange-100 transition">
            </div>

            <div class="flex justify-end gap-3 border-t border-gray-100 pt-6">
                <a href="{{ route('admin.users.index') }}" class="px-6 py-3 rounded-full font-bold text-gray-500 hover:bg-gray-100 transition">Cancel</a>
                <button type="submit" class="bg-orange-500 text-white px-8 py-3 rounded-full font-bold hover:bg-orange-600 transition shadow-md">
                    Invite User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection