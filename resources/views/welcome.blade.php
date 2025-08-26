<x-main-layout title="Welcome">
    <!-- Hero Section with Animated Content -->
    <div class="max-w-4xl mx-auto px-6">
        <!-- Main Heading -->
        <div x-data="{ show: false }" x-init="setTimeout(() => { show = true }, 100)">
            <div x-cloak x-show="show" x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <h1 class="text-5xl md:text-6xl font-bold text-white dark:text-gray-100 mb-6 leading-tight">
                    Find Your Dream Job
                </h1>
            </div>
        </div>

        <!-- Descriptive Text -->
        <div x-data="{ show: false }" x-init="setTimeout(() => { show = true }, 200)">
            <div x-cloak x-show="show" x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <p class="text-white/60 dark:text-gray-300 text-lg md:text-xl mb-8 max-w-2xl mx-auto leading-relaxed">
                    Connect with top employers, and find exciting opportunities that match your skills and career goals.
                </p>
            </div>
        </div>

        <!-- Call to Action Buttons -->
        <div x-data="{ show: false }" x-init="setTimeout(() => { show = true }, 300)">
            <div x-cloak x-show="show" x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <!-- Create Account Button -->
                    <a href="{{ route('register') }}"
                        class="rounded-lg bg-white/10 hover:bg-white/20 dark:bg-gray-800/50 dark:hover:bg-gray-700/50 px-6 py-3 text-white dark:text-gray-100 font-medium transition-all duration-300 hover:scale-105 border border-white/20 dark:border-gray-600/50">
                        Create an Account
                    </a>

                    <!-- Login Button -->
                    <a href="{{ route('login') }}"
                        class="rounded-lg bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 px-6 py-3 text-white font-medium transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-xl">
                        Login
                    </a>
                </div>
            </div>
        </div>

        <!-- Additional Features Section -->
        <div x-data="{ show: false }" x-init="setTimeout(() => { show = true }, 400)">
            <div x-cloak x-show="show" x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="text-center">
                        <div
                            class="w-16 h-16 bg-white/10 dark:bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6.5">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-white dark:text-gray-100 font-semibold mb-2">Browse Jobs</h3>
                        <p class="text-white/60 dark:text-gray-400 text-sm">Explore thousands of job opportunities</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="text-center">
                        <div
                            class="w-16 h-16 bg-white/10 dark:bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-white dark:text-gray-100 font-semibold mb-2">Easy Apply</h3>
                        <p class="text-white/60 dark:text-gray-400 text-sm">Simple and quick application process</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="text-center">
                        <div
                            class="w-16 h-16 bg-white/10 dark:bg-gray-800/50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-white dark:text-gray-100 font-semibold mb-2">Fast Results</h3>
                        <p class="text-white/60 dark:text-gray-400 text-sm">Get responses from employers quickly</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>
