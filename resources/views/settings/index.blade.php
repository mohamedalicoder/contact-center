<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Settings') }}
            </h2>
            @if(Auth::user()?->isAdmin())
                <a href="{{ route('settings.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    {{ __('Add New Setting') }}
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="space-y-8">
                        @foreach($settings as $group => $groupSettings)
                            <div class="bg-white overflow-hidden shadow rounded-lg">
                                <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        {{ ucfirst($group) }}
                                    </h3>
                                </div>
                                <div class="px-4 py-5 sm:p-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                        @foreach($groupSettings as $setting)
                                            <div class="bg-gray-50 rounded-lg p-4 relative">
                                                <form action="{{ route('settings.update', $setting) }}" method="POST" class="space-y-4">
                                                    @csrf
                                                    @method('PUT')
                                                    
                                                    <div>
                                                        <label for="{{ $setting->key }}" class="block text-sm font-medium text-gray-700 mb-1">
                                                            {{ $setting->name }}
                                                            @if(!$setting->is_public)
                                                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                                    Admin Only
                                                                </span>
                                                            @endif
                                                        </label>
                                                        
                                                        @if($setting->type === 'boolean')
                                                            <div class="mt-2">
                                                                <label class="inline-flex items-center">
                                                                    <input type="checkbox" name="value" 
                                                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                                           {{ $setting->value ? 'checked' : '' }}
                                                                           @if(!Auth::user()?->isAdmin() && !$setting->is_public) disabled @endif>
                                                                    <span class="ml-2">{{ __('Enabled') }}</span>
                                                                </label>
                                                            </div>
                                                        @elseif($setting->type === 'number')
                                                            <input type="number" name="value" id="{{ $setting->key }}"
                                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                                   value="{{ $setting->value }}"
                                                                   @if(!Auth::user()?->isAdmin() && !$setting->is_public) disabled @endif>
                                                        @else
                                                            <input type="text" name="value" id="{{ $setting->key }}"
                                                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                                   value="{{ $setting->value }}"
                                                                   @if(!Auth::user()?->isAdmin() && !$setting->is_public) disabled @endif>
                                                        @endif

                                                        @if($setting->description)
                                                            <p class="mt-1 text-sm text-gray-500">{{ $setting->description }}</p>
                                                        @endif

                                                        @error("settings.{$setting->id}")
                                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    @if(Auth::user()?->isAdmin() || $setting->is_public)
                                                        <div class="flex justify-end">
                                                            <button type="submit"
                                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 active:bg-indigo-700 transition ease-in-out duration-150">
                                                                {{ __('Save') }}
                                                            </button>
                                                        </div>
                                                    @endif
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
