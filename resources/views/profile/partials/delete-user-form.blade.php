<section>
    <header>
        <h2 class="text-xl font-extrabold text-red-600 tracking-tight flex items-center">
            <i class="bi bi-exclamation-triangle-fill mr-2"></i> Delete Account
        </h2>

        <p class="mt-1 text-sm text-gray-500 font-medium">
            Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
        </p>
    </header>

    <div class="mt-6">
        <!-- Tombol Trigger Pembuka Modal -->
        <button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="bg-red-50 text-red-600 border border-red-200 px-8 py-3.5 rounded-xl font-bold hover:bg-red-600 hover:text-white transition-all duration-300 shadow-sm"
        >
            Delete Account
        </button>
    </div>

    <!-- Modal Pop-up -->
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8">
            @csrf
            @method('delete')

            <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">
                Are you sure you want to delete your account?
            </h2>

            <p class="mt-2 text-sm text-gray-500 font-medium">
                Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
            </p>

            <!-- Input Password Validasi -->
            <div class="mt-6">
                <label for="password" class="sr-only">Password</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl focus:ring-red-500 focus:border-red-500 block p-3.5 transition outline-none"
                    placeholder="Enter your password to confirm"
                />

                @error('password', 'userDeletion')
                    <p class="mt-2 text-sm text-red-500 font-bold flex items-center">
                        <i class="bi bi-exclamation-circle mr-1"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Tombol Aksi di dalam Modal -->
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-bold hover:bg-gray-200 transition">
                    Cancel
                </button>

                <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-red-700 transition shadow-md flex items-center">
                    <i class="bi bi-trash3-fill mr-2"></i> Confirm Deletion
                </button>
            </div>
        </form>
    </x-modal>
</section>