<!-- Desktop Navigation -->
<div class="hidden sm:flex sm:items-center sm:ml-6">
    <x-dropdown align="right" width="48">
        <x-slot name="trigger">
            <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                <div>{{ __('Queue Management') }}</div>

                <div class="ml-1">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </button>
        </x-slot>

        <x-slot name="content">
            <x-dropdown-link :href="route('queue.dashboard')" :active="request()->routeIs('queue.dashboard')">
                {{ __('Queue Dashboard') }}
            </x-dropdown-link>

            <x-dropdown-link :href="route('tags.index')" :active="request()->routeIs('tags.index')">
                {{ __('Manage Tags') }}
            </x-dropdown-link>

            <x-dropdown-link :href="route('custom-fields.index')" :active="request()->routeIs('custom-fields.index')">
                {{ __('Custom Fields') }}
            </x-dropdown-link>
        </x-slot>
    </x-dropdown>
</div>

<!-- Mobile Navigation -->
<div class="pt-4 pb-1 border-t border-gray-200 sm:hidden">
    <div class="px-4">
        <div class="font-medium text-base text-gray-800">{{ __('Queue Management') }}</div>
    </div>

    <div class="mt-3 space-y-1">
        <x-responsive-nav-link :href="route('queue.dashboard')" :active="request()->routeIs('queue.dashboard')">
            {{ __('Queue Dashboard') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('tags.index')" :active="request()->routeIs('tags.index')">
            {{ __('Manage Tags') }}
        </x-responsive-nav-link>

        <x-responsive-nav-link :href="route('custom-fields.index')" :active="request()->routeIs('custom-fields.index')">
            {{ __('Custom Fields') }}
        </x-responsive-nav-link>
    </div>
</div>
