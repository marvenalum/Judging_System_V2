<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Event') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex">
                @include('admin.sidebar.admin')

                <!-- Main Content -->
                <div class="flex-1 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form method="POST" action="{{ route('admin.event.store') }}">
                            @csrf

                            <!-- Event Name -->
                            <div class="mb-4">
                                <x-input-label for="event_name" :value="__('Event Name')" />
                                <x-text-input id="event_name" class="block mt-1 w-full" type="text" name="event_name" :value="old('event_name')" required autofocus autocomplete="event_name" />
                                <x-input-error :messages="$errors->get('event_name')" class="mt-2" />
                            </div>

                            <!-- Event Description -->
                            <div class="mb-4">
                                <x-input-label for="event_description" :value="__('Description')" />
                                <textarea id="event_description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="event_description" rows="4">{{ old('event_description') }}</textarea>
                                <x-input-error :messages="$errors->get('event_description')" class="mt-2" />
                            </div>

                            <!-- Event Type -->
                            <div class="mb-4">
                                <x-input-label for="event_type" :value="__('Event Type')" />
                                <select id="event_type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="event_type">
                                    <option value="pageant" {{ old('event_type', 'pageant') == 'pageant' ? 'selected' : '' }}>Pageant</option>
                                    <option value="contest" {{ old('event_type') == 'contest' ? 'selected' : '' }}>Contest</option>
                                    <option value="competition" {{ old('event_type') == 'competition' ? 'selected' : '' }}>Competition</option>
                                </select>
                                <x-input-error :messages="$errors->get('event_type')" class="mt-2" />
                            </div>

                            <!-- Start Date -->
                            <div class="mb-4">
                                <x-input-label for="start_date" :value="__('Start Date & Time')" />
                                <x-text-input id="start_date" class="block mt-1 w-full" type="datetime-local" name="start_date" :value="old('start_date')" required />
                                <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                            </div>

                            <!-- End Date -->
                            <div class="mb-4">
                                <x-input-label for="end_date" :value="__('End Date & Time')" />
                                <x-text-input id="end_date" class="block mt-1 w-full" type="datetime-local" name="end_date" :value="old('end_date')" required />
                                <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                            </div>

                            <!-- Event Status -->
                            <div class="mb-4">
                                <x-input-label for="event_status" :value="__('Status')" />
                                <select id="event_status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="event_status">
                                    <option value="upcoming" {{ old('event_status', 'upcoming') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                    <option value="ongoing" {{ old('event_status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                    <option value="completed" {{ old('event_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                                <x-input-error :messages="$errors->get('event_status')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <a href="{{ route('admin.event.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">Cancel</a>
                                <x-primary-button>
                                    {{ __('Create Event') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
