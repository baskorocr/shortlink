<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Edit Link') }}
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
        <!-- Link Info -->
        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Informasi Link</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-medium text-gray-700 dark:text-gray-300">Short Code:</span>
                    <code class="ml-2 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">{{ $link->short_code }}</code>
                </div>
                <div>
                    <span class="font-medium text-gray-700 dark:text-gray-300">Clicks:</span>
                    <span class="ml-2 text-gray-900 dark:text-gray-100">{{ $link->click_count }}</span>
                </div>
                <div class="md:col-span-2">
                    <span class="font-medium text-gray-700 dark:text-gray-300">Short URL:</span>
                    <div class="flex items-center mt-1">
                        <code class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded flex-1">{{ $link->short_url }}</code>
                        <button onclick="copyToClipboard('{{ $link->short_url }}')" 
                                class="ml-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('links.update', $link) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Original URL -->
            <div>
                <x-form.label for="original_url" value="{{ __('URL Asli') }}" />
                <x-form.input id="original_url" 
                             name="original_url" 
                             type="url" 
                             class="mt-1 block w-full" 
                             value="{{ old('original_url', $link->original_url) }}" 
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
                             placeholder="Kosongkan jika tidak ingin mengubah password" />
                <x-form.error :messages="$errors->get('password')" class="mt-2" />
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    @if($link->hasPassword())
                        Link ini sudah memiliki password. Kosongkan jika tidak ingin mengubah.
                    @else
                        Isi jika ingin menambahkan password protection.
                    @endif
                </p>
            </div>

            <!-- Expires At (Optional) -->
            <div>
                <x-form.label for="expires_at" value="{{ __('Tanggal Kadaluarsa (Opsional)') }}" />
                <x-form.input id="expires_at" 
                             name="expires_at" 
                             type="datetime-local" 
                             class="mt-1 block w-full" 
                             value="{{ old('expires_at', $link->expires_at?->format('Y-m-d\TH:i')) }}" 
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
                       {{ old('is_active', $link->is_active) ? 'checked' : '' }}>
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
                    {{ __('Update Link') }}
                </x-button>
            </div>
        </form>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Link copied to clipboard!');
            }, function(err) {
                console.error('Could not copy text: ', err);
            });
        }
    </script>
</x-app-layout>