<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Notification Preferences') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('notifications.preferences.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-medium mb-4">Email Notifications</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="email_judge_assignment" value="1"
                                               {{ old('email_judge_assignment', $preferences->email_judge_assignment ?? true) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                            Judge Assignment Notifications
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="email_submission_deadline" value="1"
                                               {{ old('email_submission_deadline', $preferences->email_submission_deadline ?? true) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                            Submission Deadline Reminders
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="email_scores_submitted" value="1"
                                               {{ old('email_scores_submitted', $preferences->email_scores_submitted ?? true) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                            Score Submission Notifications
                                        </label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="email_results_published" value="1"
                                               {{ old('email_results_published', $preferences->email_results_published ?? true) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                            Results Publication Notifications
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium mb-4">In-App Notifications</h3>
                                <div class="space-y-3">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="in_app_notifications" value="1"
                                               {{ old('in_app_notifications', $preferences->in_app_notifications ?? true) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                        <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                            Enable In-App Notifications
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Save Preferences
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>