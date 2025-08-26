<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Apply for {{ $jobVacancies->title }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('job-vacancies.show', $jobVacancies->id) }}"
                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                    ← Back to Job Details
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


                    </div>
                </div>
            </div>

            <!-- form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                        Apply for {{ $jobVacancies->title }}
                    </h2>
                    <form action="{{ route('job-vacancies.processApply', $jobVacancies->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        {{-- Resume selection --}}
                        <div class="mb-4">
                            <x-input-label for="resume" value="Resume" />
                            {{-- List of resumes --}}
                            <div class="mt-2">
                                @forelse ($resumes as $resume)
                                    <div class="flex items-center gap-2">
                                        <input x-ref="{{ $resume->id }}" type="radio" name="resume_option"
                                            id="{{ $resume->id }}" value="{{ $resume->id }}"
                                            @error('resume_option') class="border-red-500" @else class="border-gray-300 dark:border-gray-700" @enderror>
                                        <x-input-label for="{{ $resume->id }}"
                                            class="text-gray-700 dark:text-gray-300 cursor-pointer">
                                            {{ ucfirst(str_replace('.pdf', '', $resume->file_name)) }}
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                <a href="{{ Storage::url($resume->file_url) }}" target="_blank"
                                                    class="text-blue-500 px-2">View</a>
                                                ({{ $resume->updated_at->format('M d, Y') }})
                                            </span>
                                        </x-input-label>
                                    </div>
                                @empty
                                    <div class="text-gray-500 dark:text-gray-400">
                                        No resumes found.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        {{-- Upload new resume --}}
                        <div class="mb-4" x-data="{ fileName: '', hasError: {{ $errors->has('resume_file') ? 'true' : 'false' }} }">

                            <div class="flex items-center gap-2">
                                <input x-ref="new_resume" type="radio" name="resume_option" id="new_resume"
                                    value="new_resume"
                                    @error('resume_option') class="border-red-500" @else class="border-gray-300 dark:border-gray-700" @enderror>
                                <x-input-label for="new_resume" value="Upload New Resume" class="cursor-pointer" />
                            </div>
                            <div class="flex items-center mt-2">
                                <div class="w-full">
                                    <label for="new_resume_file"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                                        <div class="border-2 border-dashed rounded-md p-4 text-center"
                                            :class="{
                                                'border-blue-500': fileName && !hasError,
                                                'border-red-500': hasError,
                                                'border-gray-300 dark:border-gray-700': !fileName && !hasError
                                            }">
                                            <input type="file" name="resume_file" id="new_resume_file" accept=".pdf"
                                                class="hidden"
                                                @change="fileName = $event.target.files[0].name; $refs.new_resume.checked = true;">
                                            <div class="text-center text-sm text-gray-500 dark:text-gray-400">
                                                <template x-if="!fileName">
                                                    <p>Upload a PDF (Max 5MB)</p>
                                                </template>
                                                <template x-if="fileName">
                                                    <div>
                                                        <p x-text="fileName" class="font-bold text-blue-500"></p>
                                                        <p class="font-bold">Click to change file</p>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        @if ($errors->any())
                            <div class="mb-4">
                                <div class="mt-2 text-sm text-red-600">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                        {{-- Submit button --}}
                        <div class="mb-4">
                            <button type="submit"
                                class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-lg">
                                Apply Now
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
