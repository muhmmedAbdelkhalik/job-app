<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Job Details
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('dashboard') }}"
                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                    ← Back to Jobs
                </a>
            </div>
            <!-- Job Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-8">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                        <!-- Job Title and Company -->
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                                {{ $jobVacancies->title }}
                            </h1>

                            <div class="flex items-center gap-3 mb-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                    <span class="text-lg text-gray-700 dark:text-gray-300">
                                        {{ $jobVacancies->company->name ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-4 text-gray-600 dark:text-gray-400">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>{{ $jobVacancies->location }}</span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ ucfirst(str_replace('-', ' ', $jobVacancies->type)) }}</span>
                                </div>

                                <div class="flex items-center gap-2">

                                    <span class="text-lg font-semibold text-green-600 dark:text-green-400">
                                        $ {{ number_format($jobVacancies->salary) }} / Year
                                    </span>
                                </div>

                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span>Published {{ $jobVacancies->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Apply Button -->
                        <div class="flex-shrink-0">
                            <a href="{{ route('job-vacancies.apply', $jobVacancies->id) }}"
                                class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-lg">
                                Apply Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job Details Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Job Description -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                                Job Description
                            </h3>
                            <div class="prose dark:prose-invert max-w-none">
                                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                    {{ $jobVacancies->description }}
                                </p>
                            </div>
                        </div>
                    </div>


                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Company Information -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                                Company Information
                            </h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Company Name</span>
                                    <p class="text-gray-900 dark:text-white font-medium">
                                        {{ $jobVacancies->company->name ?? 'N/A' }}
                                    </p>
                                </div>
                                @if ($jobVacancies->company)
                                    @if ($jobVacancies->company->website)
                                        <div>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">Website</span>
                                            <p class="text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                                <a href="{{ $jobVacancies->company->website }}" target="_blank"
                                                    rel="noopener noreferrer">
                                                    {{ $jobVacancies->company->website }}
                                                </a>
                                            </p>
                                        </div>
                                    @endif
                                    @if ($jobVacancies->company->address)
                                        <div>
                                            <span class="text-sm text-gray-500 dark:text-gray-400">Address</span>
                                            <p class="text-gray-900 dark:text-white">
                                                {{ $jobVacancies->company->address }}
                                            </p>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
