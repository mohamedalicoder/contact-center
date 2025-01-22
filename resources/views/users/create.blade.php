<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Create User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden p-4 bg-white shadow-sm sm:rounded-lg d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('users.index') }}" class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">Back to Users</a>
                </div>
                <div class="p-6 text-gray-900">
                    Create User
                </div>
            </div>
        </div>
    </div>
     <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="p-4 bg-white shadow sm:p-8 sm:rounded-lg">
        <form method="POST" action="{{ route('users.store') }}" class="mt-6 space-y-6">
            @csrf
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" name="name" type="text" class="block mt-1 w-full" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="block mt-1 w-full" :value="old('email')" required autocomplete="email" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" name="password" type="password" class="block mt-1 w-full" required autocomplete="new-password" />
                <x-input-error class="mt-2" :messages="$errors->get('password')" />
            </div>

            <div>
                <x-input-label for="role" :value="__('Role')" />
                <select id="role" name="role" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="">Select a role</option>
                    <option value="user" {{ old('role') == 'user' ? 'selected' : ''  }}>User</option>
                    <option value="supervisor" {{ old('role') == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                    <option value="agent" {{ old('role') == 'agent' ? 'selected' : ''  }}>Agent</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('role')" />
            </div>

            <div class="flex gap-4 items-center">
                <x-primary-button>{{ __('Create') }}</x-primary-button>
            </div>
        </form>

     </div>
 </div>
</x-app-layout>
