<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Message -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Welcome, Muhmmed</h1>
            </div>

            <!-- Search and Filters Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                        <!-- Search Bar -->
                        <div class="flex-1">
                            <div class="relative">
                                <form action="{{ route('dashboard') }}" method="GET">
                                    <input type="text" placeholder="Search jobs..." name="search"
                                        value="{{ request('search') }}"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>

                                    @if (request('filter'))
                                        <input type="hidden" name="filter" value="{{ request('filter') }}">
                                    @endif
                                </form>
                            </div>
                        </div>

                        <!-- Filter Buttons -->
                        <div class="flex gap-2">
                            <a href="{{ route('dashboard', ['search' => request('search')]) }}"
                                class="px-4 py-2 {{ !request('filter') ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }} rounded-lg hover:bg-blue-700 hover:text-white transition-colors">
                                All Jobs
                            </a>
                            <a href="{{ route('dashboard', ['filter' => 'full-time', 'search' => request('search')]) }}"
                                class="px-4 py-2 {{ request('filter') === 'full-time' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }} rounded-lg hover:bg-blue-700 hover:text-white transition-colors">
                                Full Time
                            </a>
                            <a href="{{ route('dashboard', ['filter' => 'part-time', 'search' => request('search')]) }}"
                                class="px-4 py-2 {{ request('filter') === 'part-time' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }} rounded-lg hover:bg-blue-700 hover:text-white transition-colors">
                                Part Time
                            </a>
                            <a href="{{ route('dashboard', ['filter' => 'hybrid', 'search' => request('search')]) }}"
                                class="px-4 py-2 {{ request('filter') === 'hybrid' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }} rounded-lg hover:bg-blue-700 hover:text-white transition-colors">
                                Hybrid
                            </a>
                            <a href="{{ route('dashboard', ['filter' => 'remote', 'search' => request('search')]) }}"
                                class="px-4 py-2 {{ request('filter') === 'remote' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }} rounded-lg hover:bg-blue-700 hover:text-white transition-colors">
                                Remote
                            </a>
                            @if (request('filter'))
                                <a href="{{ route('dashboard', ['search' => request('search')]) }}"
                                    class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                                    Clear Filters
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>



            <!-- Job Listings -->
            <div class="space-y-4">
                @forelse ($jobs as $job)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex-1">
                                    <a href="{{ route('job-vacancies.show', $job->id) }}">
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                            {{ $job->title }}
                                        </h3>
                                    </a>
                                    <p class="text-gray-600 dark:text-gray-400 mb-1">{{ $job->company->name ?? 'N/A' }}
                                        -
                                        {{ $job->location }}</p>
                                    <p class="text-lg font-semibold text-green-600 dark:text-green-400">
                                        ${{ number_format($job->salary) }} / Year</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg">
                                        {{ ucfirst(str_replace('-', ' ', $job->type)) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <p class="text-gray-600 dark:text-gray-400">No jobs found</p>
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="mt-4">
                {{ $jobs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
