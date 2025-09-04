<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen antialiased">
        <div class="min-h-svh flex flex-col items-center justify-center p-4" style="background-color:#008080; background-image:url('https://www.transparenttextures.com/patterns/hexellence.png');">
            <div class="auth-card flex w-full max-w-md flex-col gap-4 bg-white rounded-xl shadow-lg p-8 text-black">
                <div class="w-full flex justify-start mb-2">
                    <a href="{{ route('home') }}" class="inline-flex items-center px-3 py-1 rounded bg-transparent text-white font-semibold hover:text-[#008080] transition border border-white">
                        <span class="mr-2">&#8592;</span> Back to Homepage
                    </a>
                </div>
                <style>
                    .auth-card, .auth-card *, .auth-card input, .auth-card label, .auth-card ::placeholder {
                        color: #000 !important;
                        fill: #000 !important;
                        border-color: #000 !important;
                    }
                </style>
                <div class="flex flex-col items-center gap-4">
                    <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                        <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                    </a>
                    <div class="flex flex-col gap-6 w-full text-center text-black">
                        {{ $slot }}
                        @if (request()->routeIs('login'))
                            <a href="{{ route('password.request') }}" class="text-sm text-[#008080] hover:underline mt-2">Forgot Password?</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
