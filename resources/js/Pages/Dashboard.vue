<script setup>
import {Head, Link} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {mdiAccountPlus, mdiFileClockOutline, mdiListStatus} from '@mdi/js';

</script>

<template>
    <AuthenticatedLayout>
        <Head title="Menü" />
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- Benutzer-Icon zum Erstellen eines neuen Patienten -->
                        <div class="flex flex-col items-center">
                            <Link :href="route('patients.create')" class="text-center">
                                <svg class="w-24 h-24 text-blue-500 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                                    <path :d="mdiAccountPlus" />
                                </svg>
                                <span class="text-xl font-bold text-gray-800">Neuen Klienten erstellen</span>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div >
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- Benutzer-Icon zum Erstellen eines neuen Patienten -->
                        <div class="flex flex-col items-center">
                            <Link :href="route('sessions.create')" class="text-center">
                                <svg class="w-24 h-24 text-blue-500 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                                    <path :d="mdiFileClockOutline" />
                                </svg>
                                <span class="text-xl font-bold text-gray-800">Sitzungen verwalten</span>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- Benutzer-Icon zum Erstellen eines neuen Patienten -->
                        <div class="flex flex-col items-center">
                            <Link :href="route('diagnoses.index')" class="text-center">
                                <svg class="w-24 h-24 text-blue-500 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                                    <path :d="mdiListStatus" />
                                </svg>
                                <span class="text-xl font-bold text-gray-800">Diagnosen verwalten</span>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
