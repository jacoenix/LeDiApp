<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div class="flex flex-col items-center">
            <Link href="/">
                <img src="https://www.culinaryschools.org/clipart/agriculture/cartoon-sheep.png" alt="Application Logo" class="h-32 w-auto">
            </Link>
            <span class="text-2xl font-bold text-gray-800 mt-4">LeDiApp</span>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
