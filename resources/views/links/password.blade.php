<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <div class="mb-4 text-center">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                {{ __('Password Required') }}
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                This link is password protected. Please enter the password to continue.
            </p>
        </div>

        <!-- Link Info -->
        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                </svg>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                        {{ $link->short_code }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate" title="{{ $link->original_url }}">
                        {{ Str::limit($link->original_url, 60) }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />
   

        <form method="POST" action="{{ url('/' . $link->short_code) }}">
            @csrf

            <!-- Password -->
            <div>
                <x-form.label for="password" value="{{ __('Password') }}" />
                <x-form.input-with-icon-wrapper>
                    <x-slot name="icon">
                        <svg aria-hidden="true" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </x-slot>

                    <x-form.input id="password" 
                                 class="block mt-1 w-full" 
                                 type="password" 
                                 name="password" 
                                 required 
                                 autocomplete="current-password" 
                                 placeholder="Enter password" 
                                 withicon="true"
                                 autofocus />
                </x-form.input-with-icon-wrapper>
            </div>

            <div class="flex items-center justify-center mt-6">
                <x-button class="w-full justify-center">
                    {{ __('Access Link') }}
                </x-button>
            </div>
        </form>

        <div class="mt-6 text-center">
            <p class="text-xs text-gray-500 dark:text-gray-400">
                Don't have the password? Contact the link owner.
            </p>
        </div>
    </x-auth-card>
</x-guest-layout>