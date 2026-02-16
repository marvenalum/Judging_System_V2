<!-- Sidebar -->

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex">
                <!-- Sidebar -->
                <div class="w-64 bg-white shadow-sm sm:rounded-lg mr-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Navigation</h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300 {{ request()->routeIs('dashboard') ? 'bg-blue-500 text-white' : '' }}">
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="#" class="block px-4 py-2 text-gray-700 rounded hover:bg-gray-300">
                                    Submissions
                                </a>
                            </li>
                            <li>
                                <a href="#" class="block px-4 py-2 text-gray-700 rounded hover:bg-gray-300">
                                    Competitions
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 rounded hover:bg-gray-300">
                                    Profile
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>