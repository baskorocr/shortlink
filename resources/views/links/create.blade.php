<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Buat Link Baru') }}
            </h2>
            <x-button href="{{ route('links.index') }}" variant="secondary" class="justify-center max-w-xs gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali</span>
            </x-button>
        </div>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <form action="{{ route('links.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Original URL -->
            <div>
                <x-form.label for="original_url" value="{{ __('URL Asli') }}" />
                <x-form.input id="original_url" 
                             name="original_url" 
                             type="url" 
                             class="mt-1 block w-full" 
                             value="{{ old('original_url') }}" 
                             required 
                             placeholder="https://example.com" />
                <x-form.error :messages="$errors->get('original_url')" class="mt-2" />
            </div>

            <!-- Password (Optional) -->
            <div>
                <x-form.label for="password" value="{{ __('Password (Opsional)') }}" />
                <x-form.input id="password" 
                             name="password" 
                             type="password" 
                             class="mt-1 block w-full" 
                             placeholder="Kosongkan jika tidak ingin menggunakan password" />
                <x-form.error :messages="$errors->get('password')" class="mt-2" />
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Jika diisi, user harus memasukkan password untuk mengakses link.
                </p>
            </div>

            <!-- Expires At (Optional) -->
            <div>
                <x-form.label for="expires_at" value="{{ __('Tanggal Kadaluarsa (Opsional)') }}" />
                <x-form.input id="expires_at" 
                             name="expires_at" 
                             type="datetime-local" 
                             class="mt-1 block w-full" 
                             value="{{ old('expires_at') }}" 
                             min="{{ now()->format('Y-m-d\TH:i') }}" />
                <x-form.error :messages="$errors->get('expires_at')" class="mt-2" />
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Kosongkan jika link tidak memiliki batas waktu.
                </p>
            </div>

            <!-- Is Active -->
            <div class="flex items-center">
                <input id="is_active" 
                       name="is_active" 
                       type="checkbox" 
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" 
                       {{ old('is_active', true) ? 'checked' : '' }}>
                <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">
                    {{ __('Aktifkan link') }}
                </label>
            </div>

            <div class="flex items-center justify-end mt-6 space-x-4">
                <x-button type="button" 
                         onclick="window.location.href='{{ route('links.index') }}'" 
                         variant="secondary">
                    {{ __('Batal') }}
                </x-button>
                
                <x-button type="submit" variant="primary">
                    {{ __('Buat Link') }}
                </x-button>
            </div>
        </form>
    </div>
</x-app-layout>