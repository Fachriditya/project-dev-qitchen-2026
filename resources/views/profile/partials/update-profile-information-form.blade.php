<section>
    <header class="mb-6">
        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Profile information</h2>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('patch')

        <!-- Profile -->
        <div>
            <label for="photo" class="block mb-3 text-sm font-bold text-gray-700">Profile Photo</label>
            <div class="flex items-center gap-5">
                <!-- Preview Foto Kecil -->
                <div class="w-16 h-16 rounded-full bg-slate-900 flex items-center justify-center text-white font-bold shadow-sm overflow-hidden shrink-0 border border-gray-100">
                    @if(Auth::user()->profile_picture)
                        <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    @endif
                </div>
                <!-- Input File Modern -->
                <input id="profile_picture" name="profile_picture" type="file" accept="image/*" class="w-full bg-gray-50 border-transparent text-gray-900 rounded-2xl focus:ring-2 focus:ring-orange-500 focus:bg-white block p-2 transition outline-none cursor-pointer file:mr-4 file:py-2.5 file:px-5 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-orange-50 file:text-orange-500 hover:file:bg-orange-100" />
            </div>
            @error('photo') <p class="mt-2 text-sm text-red-500 font-bold"><i class="bi bi-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Full Name -->
            <div>
                <label for="name" class="block mb-2 text-sm font-bold text-gray-700">Full name</label>
                <input id="name" name="name" type="text" class="w-full bg-gray-50 border-transparent text-gray-900 rounded-2xl focus:ring-2 focus:ring-orange-500 focus:bg-white block p-4 transition outline-none" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                @error('name') <p class="mt-2 text-sm text-red-500 font-bold"><i class="bi bi-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block mb-2 text-sm font-bold text-gray-700">Phone</label>
                <input id="phone" type="text" class="w-full bg-gray-50 border-transparent text-gray-400 rounded-2xl block p-4 cursor-not-allowed" value="+62 812 3456 7890" disabled title="Phone number cannot be changed here" />
            </div>
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block mb-2 text-sm font-bold text-gray-700">Email</label>
            <input id="email" name="email" type="email" class="w-full bg-gray-50 border-transparent text-gray-900 rounded-2xl focus:ring-2 focus:ring-orange-500 focus:bg-white block p-4 transition outline-none" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @error('email') <p class="mt-2 text-sm text-red-500 font-bold"><i class="bi bi-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
        </div>

        <!-- Action Area -->
        <div class="pt-4 flex items-center gap-4">
            <button type="submit" class="bg-orange-500 text-white px-8 py-3.5 rounded-full font-bold hover:bg-orange-600 transition shadow-md">
                Save Changes
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm font-bold text-green-500">
                    <i class="bi bi-check-circle-fill mr-1"></i> Saved.
                </p>
            @endif
        </div>
    </form>
</section>