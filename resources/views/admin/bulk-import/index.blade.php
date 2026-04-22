<x-admin-layout>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Bulk Import/Export</h1>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Export Section -->
                <div class="space-y-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Export Scores</h2>
                        <p class="text-gray-600 mb-4">
                            Download scores data in CSV format for backup, analysis, or external processing.
                        </p>

                        <form method="POST" action="{{ route('admin.bulk-import.export') }}" class="space-y-4">
                            @csrf

                            <div>
                                <label for="export_event_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Event <span class="text-red-500">*</span>
                                </label>
                                <select name="event_id" id="export_event_id"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="">Select Event</option>
                                    @foreach($events as $event)
                                        <option value="{{ $event->id }}">{{ $event->event_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="export_category_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Category (Optional)
                                </label>
                                <select name="category_id" id="export_category_id"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Categories</option>
                                </select>
                            </div>

                            <div>
                                <label for="export_format" class="block text-sm font-medium text-gray-700 mb-1">
                                    Format <span class="text-red-500">*</span>
                                </label>
                                <select name="format" id="export_format"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="csv">CSV</option>
                                </select>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                                <i class="bi bi-download mr-2"></i>Export Scores
                            </button>
                        </form>
                    </div>

   
                </div>

                <!-- Import Section -->
                <div class="space-y-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Import Scores</h2>
                        <p class="text-gray-600 mb-4">
                            Upload scores from a CSV file. Choose how to handle existing scores.
                        </p>

                        <form method="POST" action="{{ route('admin.bulk-import.import') }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf

                            <div>
                                <label for="import_event_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Event <span class="text-red-500">*</span>
                                </label>
                                <select name="event_id" id="import_event_id"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="">Select Event</option>
                                    @foreach($events as $event)
                                        <option value="{{ $event->id }}">{{ $event->event_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="import_category_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Category (Optional)
                                </label>
                                <select name="category_id" id="import_category_id"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Categories</option>
                                </select>
                            </div>

                            <div>
                                <label for="import_mode" class="block text-sm font-medium text-gray-700 mb-1">
                                    Import Mode <span class="text-red-500">*</span>
                                </label>
                                <select name="import_mode" id="import_mode"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="upsert">Create or Update (Recommended)</option>
                                    <option value="create">Create Only (Skip existing)</option>
                                    <option value="update">Update Only (Skip missing)</option>
                                </select>
                                <p class="mt-1 text-sm text-gray-500">
                                    Choose how to handle scores that already exist in the system.
                                </p>
                            </div>

                            <div>
                                <label for="file" class="block text-sm font-medium text-gray-700 mb-1">
                                    CSV File <span class="text-red-500">*</span>
                                </label>
                                <input type="file" name="file" id="file" accept=".csv,.txt"
                                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                <p class="mt-1 text-sm text-gray-500">
                                    Upload a CSV file with scores data. Maximum size: 10MB.
                                </p>
                            </div>

                            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium">
                                <i class="bi bi-upload mr-2"></i>Import Scores
                            </button>
                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to load categories for an event
    function loadCategories(eventId, targetSelect) {
        if (!eventId) {
            targetSelect.innerHTML = '<option value="">All Categories</option>';
            return;
        }

        fetch(`{{ route('admin.bulk-import.categories') }}?event_id=${eventId}`)
            .then(response => response.json())
            .then(data => {
                targetSelect.innerHTML = '<option value="">All Categories</option>';
                data.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    targetSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error loading categories:', error));
    }

    // Event listeners for export form
    document.getElementById('export_event_id').addEventListener('change', function() {
        loadCategories(this.value, document.getElementById('export_category_id'));
    });

    // Event listeners for template form
    document.getElementById('template_event_id').addEventListener('change', function() {
        loadCategories(this.value, document.getElementById('template_category_id'));
    });

    // Event listeners for import form
    document.getElementById('import_event_id').addEventListener('change', function() {
        loadCategories(this.value, document.getElementById('import_category_id'));
    });
});
</script>
</x-admin-layout>