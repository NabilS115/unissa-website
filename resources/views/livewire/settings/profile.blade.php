<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id)
            ],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>



<div class="w-full">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-8 mt-8">
        <div class="flex items-center gap-4 mb-6">
            <img src="{{ Auth::user()->profile_photo_url ?: asset('images/default-profile.svg') }}" alt="Profile Picture" class="w-20 h-20 rounded-full object-cover border-4 border-teal-600">
            <div>
                <div class="text-2xl font-bold text-teal-700">{{ Auth::user()->name }}</div>
                <div class="text-gray-600">Role: {{ Auth::user()->role ?? 'Lecturer / Student / Staff' }}</div>
            </div>
        </div>
        <form wire:submit="updateProfileInformation" class="space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input wire:model="name" id="name" type="text" required autofocus autocomplete="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" />
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input wire:model="email" id="email" type="email" required autocomplete="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" />
                @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
                    <div class="mt-2">
                        <span class="text-sm text-yellow-600">Your email address is unverified.</span>
                        <button type="button" wire:click.prevent="resendVerificationNotification" class="ml-2 text-sm text-teal-700 underline">Click here to re-send the verification email.</button>
                        @if (session('status') === 'verification-link-sent')
                            <span class="block mt-2 font-medium text-green-600">A new verification link has been sent to your email address.</span>
                        @endif
                    </div>
                @endif
            </div>
            <div class="flex items-center gap-4 mt-6">
                <button type="submit" class="bg-teal-600 text-white px-6 py-2 rounded-lg hover:bg-teal-700 transition w-full">Save</button>
                @if (session('profile-updated'))
                    <span class="me-3 text-green-600 font-medium">{{ __('Saved.') }}</span>
                @endif
            </div>
        </form>
        <div class="mt-8">
            <livewire:settings.delete-user-form />
        </div>
    </div>
</div>
