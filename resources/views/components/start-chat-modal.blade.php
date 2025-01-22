<x-modal name="start-chat" focusable>
    <form method="POST" action="{{ route('chat.store') }}" class="p-6">
        @csrf

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Start a New Chat') }}
        </h2>

        <div class="mt-6">
            <x-input-label for="name" value="{{ __('Your Name') }}" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-input-label for="email" value="{{ __('Your Email') }}" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-input-label for="message" value="{{ __('Initial Message') }}" />
            <x-textarea-input id="message" name="message" class="mt-1 block w-full" rows="3" required />
            <x-input-error :messages="$errors->get('message')" class="mt-2" />
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-primary-button class="ml-3">
                {{ __('Start Chat') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>
