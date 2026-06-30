@extends('layouts.master')

@section('title', 'User Management')

@section('content')
<div class="p-8 bg-[#f8f9fa] min-h-screen">
    
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">User Management</h1>
            <p class="text-gray-500 font-medium mt-1">Manage your staff and customer accounts</p>
        </div>
        
        <!-- Tombol persis seperti di desain (Invite user) -->
        <a href="{{ route('admin.users.create') }}" class="bg-orange-500 text-white px-6 py-3 rounded-full font-bold hover:bg-orange-600 transition shadow-md flex items-center">
            <i class="bi bi-plus-lg mr-2 text-lg"></i> Invite user
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-600 rounded-2xl font-bold flex items-center">
            <i class="bi bi-check-circle-fill mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-gray-100 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] overflow-hidden">
        
        <!-- Area Search & Filter -->
        <div class="p-6 border-b border-gray-100 flex flex-col xl:flex-row xl:items-center justify-between gap-4">
            
            <!-- Search -->
            <form action="{{ route('admin.users.index') }}" method="GET" class="relative w-full max-w-md">
                @if(request('role'))
                    <input type="hidden" name="role" value="{{ request('role') }}">
                @endif
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or phone..." class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-full pl-11 pr-4 py-2.5 focus:ring-2 focus:ring-orange-500 focus:bg-white focus:border-transparent text-sm outline-none transition shadow-sm" />
            </form>

            <!-- Role Filters (Bisa di-scroll horizontal jika layarnya sempit) -->
            <div class="flex items-center gap-1 bg-gray-50 rounded-full p-1 border border-gray-200 shadow-sm overflow-x-auto whitespace-nowrap">
                <a href="{{ route('admin.users.index', ['search' => request('search')]) }}" 
                   class="px-5 py-1.5 rounded-full text-sm font-medium transition {{ !request('role') ? 'bg-white shadow-sm text-gray-900 font-bold' : 'text-gray-500 hover:text-gray-900' }}">
                    All
                </a>
                
                @php
                    $roles = ['admin', 'customer', 'cashier', 'kitchen', 'courier'];
                @endphp
                
                @foreach($roles as $roleOption)
                    <a href="{{ route('admin.users.index', ['role' => $roleOption, 'search' => request('search')]) }}" 
                       class="px-5 py-1.5 rounded-full text-sm font-medium capitalize transition {{ request('role') == $roleOption ? 'bg-white shadow-sm text-gray-900 font-bold' : 'text-gray-500 hover:text-gray-900' }}">
                        {{ $roleOption }}
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Area Tabel (Scrollable Horizontal) -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="text-gray-400 text-xs font-bold uppercase tracking-wider border-b border-gray-100 bg-gray-50/50">
                        <th class="p-5 pl-8 w-16">No</th>
                        <th class="p-5 text-center">Profile</th>
                        <th class="p-5">Name</th>
                        <th class="p-5">Email</th>
                        <th class="p-5">Phone</th>
                        <th class="p-5 text-center">Role</th>
                        <th class="p-5">Referral Code</th>
                        <th class="p-5">Referred By</th>
                        <th class="p-5">Joined At</th>
                        <th class="p-5 pr-8 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    
                    @forelse($users as $index => $user)
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <!-- No -->
                            <td class="p-5 pl-8 text-gray-900 font-bold">
                                {{ $users->firstItem() + $index }}
                            </td>

                            <!-- Profile -->
                            <td class="p-5 text-center">
                                <div class="w-10 h-10 mx-auto rounded-full bg-slate-900 text-white flex items-center justify-center font-bold overflow-hidden shadow-sm">
                                    @if($user->profile_picture)
                                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile" class="w-full h-full object-cover">
                                    @else
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    @endif
                                </div>
                            </td>

                            <!-- Name -->
                            <td class="p-5 text-gray-900 font-bold">
                                {{ $user->name }}
                            </td>

                            <!-- Email -->
                            <td class="p-5 text-gray-500 font-medium text-sm">
                                {{ $user->email }}
                            </td>

                            <!-- Phone -->
                            <td class="p-5 text-gray-500 font-medium text-sm">
                                {{ $user->phone ?? '-' }}
                            </td>

                            <!-- Role -->
                            <td class="p-5 text-center">
                                @if($user->role === 'customer')
                                    <span class="bg-gray-100 text-gray-500 px-3 py-1 rounded-full text-xs font-bold capitalize border border-gray-200">{{ $user->role }}</span>
                                @elseif($user->role === 'admin' || $user->role === 'super_admin')
                                    <span class="bg-orange-50 text-orange-600 px-3 py-1 rounded-full text-xs font-bold capitalize border border-orange-100">{{ str_replace('_', ' ', $user->role) }}</span>
                                @else
                                    <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-bold capitalize border border-blue-100">{{ $user->role }}</span>
                                @endif
                            </td>

                            <!-- Referral Code -->
                            <td class="p-5 text-gray-900 font-mono text-sm font-bold">
                                {{ $user->referral_code ?? '-' }}
                            </td>

                            <!-- Referred By -->
                            <td class="p-5 text-gray-500 font-medium text-sm">
                                {{ $user->referred_by ?? '-' }}
                            </td>

                            <!-- Created At -->
                            <td class="p-5 text-gray-500 font-medium text-sm">
                                {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                            </td>
                            
                            <!-- Actions -->
                            <td class="p-5 pr-8 flex justify-end gap-3">
                                <a href="{{ route('admin.users.edit', $user->user_id) }}" class="w-9 h-9 rounded-full bg-white border border-gray-200 text-gray-400 flex items-center justify-center hover:border-blue-500 hover:bg-blue-50 hover:text-blue-600 transition shadow-sm" title="Edit">
                                    <i class="bi bi-pencil-fill text-sm"></i>
                                </a>
                                <!-- Sembunyikan tombol delete untuk diri sendiri agar admin tidak bisa bunuh diri (menghapus akunnya sendiri yang sedang login) -->
                                @if(Auth::id() !== $user->user_id)
                                <form action="{{ route('admin.users.destroy', $user->user_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-9 h-9 rounded-full bg-white border border-gray-200 text-gray-400 flex items-center justify-center hover:border-red-500 hover:bg-red-50 hover:text-red-600 transition shadow-sm" title="Delete">
                                        <i class="bi bi-trash3-fill text-sm"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="p-8 text-center text-gray-500 font-medium">
                                <i class="bi bi-people text-4xl text-gray-300 block mb-3"></i>
                                No users found matching your criteria.
                            </td>
                        </tr>
                    @endforelse
                    
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
            <div class="p-5 border-t border-gray-100 bg-white">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection