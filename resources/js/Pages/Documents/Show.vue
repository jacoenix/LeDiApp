<!-- resources/js/Pages/Documents/Show.vue -->
<template>
    <AuthenticatedLayout :auth="auth" :selected-patient="selectedPatient">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dokument anzeigen: {{ document.name }}
            </h2>
        </template>

        <template #default>
            <div>
                <p>Dateipfad: <a :href="document.file_path" target="_blank">{{ document.file_path }}</a></p>
            </div>
        </template>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const { props } = usePage();
const document = ref(props.document);
const selectedPatient = ref(props.selectedPatient);
const auth = ref(props.auth);
</script>
