<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Create New Event') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Form Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <h3 class="text-xl font-bold text-gray-900">Event Details</h3>
                    <p class="mt-1 text-sm text-gray-600">Fill in the information to create a new event</p>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('admin.event.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Event Name -->
                            <div>
                                <x-input-label for="event_name" :value="__('Event Name')" />
                                <x-text-input id="event_name" class="block mt-1 w-full" type="text" name="event_name" :value="old('event_name')" required autofocus autocomplete="event_name" placeholder="Enter event name" />
                                <x-input-error :messages="$errors->get('event_name')" class="mt-2" />
                            </div>

                            <!-- Event Type -->
                            <div>
                                <x-input-label for="event_type" :value="__('Event Type')" />
                                <select id="event_type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="event_type">
                                    <option value="pageant" {{ old('event_type', 'pageant') == 'pageant' ? 'selected' : '' }}>Pageant</option>
                                    <option value="contest" {{ old('event_type') == 'contest' ? 'selected' : '' }}>Contest</option>
                                    <option value="competition" {{ old('event_type') == 'competition' ? 'selected' : '' }}>Competition</option>
                                </select>
                                <x-input-error :messages="$errors->get('event_type')" class="mt-2" />
                            </div>

                            <!-- Event Description -->
                            <div class="md:col-span-2">
                                <x-input-label for="event_description" :value="__('Description')" />
                                <textarea id="event_description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="event_description" rows="4" placeholder="Enter event description">{{ old('event_description') }}</textarea>
                                <x-input-error :messages="$errors->get('event_description')" class="mt-2" />
                            </div>

                            <!-- Start Date -->
                            <div>
                                <x-input-label for="start_date" :value="__('Start Date & Time')" />
                                <x-text-input id="start_date" class="block mt-1 w-full" type="datetime-local" name="start_date" :value="old('start_date')" required />
                                <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                            </div>

                            <!-- End Date -->
                            <div>
                                <x-input-label for="end_date" :value="__('End Date & Time')" />
                                <x-text-input id="end_date" class="block mt-1 w-full" type="datetime-local" name="end_date" :value="old('end_date')" required />
                                <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                            </div>

                            <!-- Event Status -->
                            <div>
                                <x-input-label for="event_status" :value="__('Status')" />
                                <select id="event_status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="event_status">
                                    <option value="upcoming" {{ old('event_status', 'upcoming') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                    <option value="ongoing" {{ old('event_status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                    <option value="completed" {{ old('event_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                                <x-input-error :messages="$errors->get('event_status')" class="mt-2" />
                            </div>

                            <!-- Anonymous Judging -->
                            <div class="md:col-span-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="anonymous_judging" name="anonymous_judging" value="1"
                                           {{ old('anonymous_judging') ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <label for="anonymous_judging" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                        Enable Anonymous Judging
                                    </label>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">When enabled, judge identities will be hidden from participants and participant names will be masked for judges.</p>
                            </div>

                            <!-- Anonymity Level (shown when anonymous judging is enabled) -->
                            <div class="md:col-span-2" id="anonymity-level-container" style="display: none;">
                                <x-input-label for="anonymity_level" :value="__('Anonymity Level')" />
                                <select id="anonymity_level" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="anonymity_level">
                                    <option value="full" {{ old('anonymity_level', 'full') == 'full' ? 'selected' : '' }}>Full Anonymity</option>
                                    <option value="partial" {{ old('anonymity_level') == 'partial' ? 'selected' : '' }}>Partial Anonymity</option>
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Full: Both judges and participants are anonymous. Partial: Only judges are anonymous to participants.</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 space-x-4">
                            <a href="{{ route('admin.event.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Create Event
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('anonymous_judging').addEventListener('change', function() {
            const container = document.getElementById('anonymity-level-container');
            if (this.checked) {
                container.style.display = 'block';
            } else {
                container.style.display = 'none';
            }
        });

        // Check on page load
        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('anonymous_judging');
            const container = document.getElementById('anonymity-level-container');
            if (checkbox.checked) {
                container.style.display = 'block';
            }
        });
    </script></x-admin-layout>
