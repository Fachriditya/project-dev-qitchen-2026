<section>
    <header class="mb-6">
        <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Change Password</h2>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div>
            <label for="update_password_current_password" class="block mb-2 text-sm font-bold text-gray-700">Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password" class="w-full bg-gray-50 border-transparent text-gray-900 rounded-2xl focus:ring-2 focus:ring-orange-500 focus:bg-white block p-4 transition outline-none" placeholder="********" autocomplete="current-password" />
            @error('current_password', 'updatePassword') <p class="mt-2 text-sm text-red-500 font-bold"><i class="bi bi-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
        </div>

        <!-- New Password -->
        <div>
            <label for="update_password_password" class="block mb-2 text-sm font-bold text-gray-700">New Password</label>
            <input id="update_password_password" name="password" type="password" class="w-full bg-gray-50 border-transparent text-gray-900 rounded-2xl focus:ring-2 focus:ring-orange-500 focus:bg-white block p-4 transition outline-none" placeholder="********" autocomplete="new-password" />
            @error('password', 'updatePassword') <p class="mt-2 text-sm text-red-500 font-bold"><i class="bi bi-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="update_password_password_confirmation" class="block mb-2 text-sm font-bold text-gray-700">Confirm Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full bg-gray-50 border-transparent text-gray-900 rounded-2xl focus:ring-2 focus:ring-orange-500 focus:bg-white block p-4 transition outline-none" placeholder="********" autocomplete="new-password" />
            @error('password_confirmation', 'updatePassword') <p class="mt-2 text-sm text-red-500 font-bold"><i class="bi bi-exclamation-circle mr-1"></i> {{ $message }}</p> @enderror
        </div>

        <!-- Action Area -->
        <div class="pt-4 flex items-center gap-4">
            <button type="submit" class="bg-orange-500 text-white px-8 py-3.5 rounded-full font-bold hover:bg-orange-600 transition shadow-md">
                Save Changes
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm font-bold text-green-500">
                    <i class="bi bi-check-circle-fill mr-1"></i> Saved.
                </p>
            @endif
        </div>
    </form>
</section>