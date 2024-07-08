<x-app-layout>
    <div class="w-[400px] mx-auto my-32 text-white">
        <div class="mb-4">
            {{ __('verify.message') }}
        </div>
    
        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-200">
                {{ __('verify.success') }}
            </div>
        @endif
    
        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
    
                <div>
                    <x-primary-button>
                        {{ __('verify.button') }}
                    </x-primary-button>
                </div>
            </form>
    
            <form method="POST" action="{{ route('logout') }}">
                @csrf
    
                <button type="submit" class="underline text-sm text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                    {{ __('verify.logout') }}
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
