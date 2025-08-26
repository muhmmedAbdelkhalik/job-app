<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                My Applications
            </h2>
        </div>
    </x-slot>

    <x-toast-notification />

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if($jobApplications->count() > 0)
                <div class="flex flex-col gap-6">
                    @foreach ($jobApplications as $jobApplication)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-shadow duration-300" x-data="{ expanded: false }">
                            <div class="p-6">
                                <!-- Header Section -->
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                                {{ $jobApplication->jobVacancy->title ?? 'N/A' }}
                                            </h3>
                                            <x-status :status="$jobApplication->status" />
                                        </div>
                                        
                                        <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                                <span>{{ $jobApplication->jobVacancy->company->name ?? 'N/A' }}</span>
                                            </div>
                                            
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                <span>{{ $jobApplication->jobVacancy->location ?? 'N/A' }}</span>
                                            </div>
                                            
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                </svg>
                                                <span>{{ $jobApplication->jobVacancy->salary ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="text-right">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            Applied on
                                        </div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $jobApplication->created_at ? $jobApplication->created_at->format('M d, Y') : 'N/A' }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Expandable Content -->
                                <div x-show="expanded" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95">
                                    <!-- Job Details Section -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div class="space-y-3">
                                            <div>
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Job Type:</span>
                                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                                    {{ ucfirst($jobApplication->jobVacancy->type ?? 'N/A') }}
                                                </span>
                                            </div>
                                            
                                            <div>
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Category:</span>
                                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $jobApplication->jobVacancy->category->name ?? 'N/A' }}
                                                </span>
                                            </div>
                                            
                                            <div>
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Industry:</span>
                                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $jobApplication->jobVacancy->company->industry->name ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="space-y-3">
                                            <div>
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Company Website:</span>
                                                @if($jobApplication->jobVacancy->company->website)
                                                    <a href="{{ $jobApplication->jobVacancy->company->website }}" target="_blank" class="ml-2 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 underline">
                                                        {{ $jobApplication->jobVacancy->company->website }}
                                                    </a>
                                                @else
                                                    <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">N/A</span>
                                                @endif
                                            </div>
                                            
                                            <div>
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Company Address:</span>
                                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $jobApplication->jobVacancy->company->address ?? 'N/A' }}
                                                </span>
                                            </div>
                                            
                                            <div>
                                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Views:</span>
                                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                                    {{ number_format($jobApplication->jobVacancy->view_count ?? 0) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- AI Analysis Section -->
                                    @if($jobApplication->ai_score || $jobApplication->ai_feedback)
                                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mb-4">
                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                                </svg>
                                                AI Analysis
                                            </h4>
                                            
                                            <div class="space-y-4">
                                                @if($jobApplication->ai_score)
                                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                                        <div class="flex items-center justify-between mb-2">
                                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">AI Score</span>
                                                            <span class="text-lg font-bold text-blue-600">{{ $jobApplication->ai_score }}/100</span>
                                                        </div>
                                                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $jobApplication->ai_score }}%"></div>
                                                        </div>
                                                    </div>
                                                @endif
                                                
                                                @if($jobApplication->ai_feedback)
                                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3">
                                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 block mb-2">AI Feedback</span>
                                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $jobApplication->ai_feedback }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Job Description Preview -->
                                    @if($jobApplication->jobVacancy->description)
                                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mb-4">
                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Job Description</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3">
                                                {{ Str::limit($jobApplication->jobVacancy->description, 200) }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Actions Section -->
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-4 flex items-center justify-between">
                                    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span>Application ID: {{ Str::limit($jobApplication->id, 8) }}</span>
                                    </div>
                                    
                                    <div class="flex items-center gap-2">
                                        <!-- Expand/Collapse Button -->
                                        <button @click="expanded = !expanded" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                            <svg x-show="!expanded" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                            <svg x-show="expanded" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                            </svg>
                                            <span x-text="expanded ? 'Show Less' : 'Show More'"></span>
                                        </button>
                                        
                                        <a href="{{ route('job-vacancies.show', $jobApplication->job_vacancy_id) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View Job
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if($jobApplications->hasPages())
                    <div class="mt-8">
                        {{ $jobApplications->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No applications yet</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by applying to your first job.</p>
                    <div class="mt-6">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Browse Jobs
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
