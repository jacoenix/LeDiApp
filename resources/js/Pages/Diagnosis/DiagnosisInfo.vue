<template>
    <AuthenticatedLayout>
        <Head title="Diagnosen verwalten" />
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Diagnosen verwalten</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <button @click="showCreateModal = true" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700">Neue Diagnose erstellen</button>
                        <table class="min-w-full mt-6">
                            <thead>
                            <tr>
                                <th class="px-6 py-3 border-b-2 border-gray-300 text-left text-xs leading-4 text-gray-600 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 border-b-2 border-gray-300"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="diagnosis in diagnoses" :key="diagnosis.id">
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-300">{{ diagnosis.name }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-300 text-right">
                                    <button @click="openEditModal(diagnosis)" class="text-indigo-600 hover:text-indigo-900"><svg class="w-6 h-6" :viewBox="'0 0 24 24'">
                                        <path :d="mdiPencil" fill="currentColor" />
                                    </svg></button>
                                    <button @click="deleteDiagnosis(diagnosis.id)" class="ml-4 text-red-600 hover:text-red-900"><svg class="w-6 h-6" :viewBox="'0 0 24 24'">
                                        <path :d="mdiDelete" fill="currentColor" />
                                    </svg></button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Neue Diagnose erstellen Modal -->
        <div v-if="showCreateModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <span @click="showCreateModal = false" class="close absolute top-2 right-4 text-2xl cursor-pointer">&times;</span>
                <form @submit.prevent="createDiagnosis">
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" v-model="form.name" id="name" required class="mt-2 mb-4 p-2 border rounded-md w-full">
                    <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 w-full">Erstellen</button>
                </form>
            </div>
        </div>

        <!-- Diagnose bearbeiten Modal -->
        <div v-if="showEditModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <span @click="showEditModal = false" class="close absolute top-2 right-4 text-2xl cursor-pointer">&times;</span>
                <form @submit.prevent="updateDiagnosis">
                    <label for="edit-name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" v-model="form.name" id="edit-name" required class="mt-2 mb-4 p-2 border rounded-md w-full">
                    <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 w-full">Speichern</button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import {Head, usePage} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout2.vue';
import {Inertia} from "@inertiajs/inertia";
import {mdiDelete, mdiPencil} from "@mdi/js";

const { props } = usePage();
const diagnoses = ref(props.diagnoses);
const showCreateModal = ref(false);
const showEditModal = ref(false);
const form = ref({ name: '', id: null });

const openEditModal = (diagnosis) => {
    form.value = { name: diagnosis.name, id: diagnosis.id };
    showEditModal.value = true;
};

const createDiagnosis = () => {
    Inertia.post(route('diagnoses.store'), form.value, {
        onSuccess: () => showCreateModal.value = false
    });
};

const updateDiagnosis = () => {
    Inertia.put(route('diagnoses.update', form.value.id), form.value, {
        onSuccess: () => showEditModal.value = false
    });
};

const deleteDiagnosis = (id) => {
    if (confirm('Sind Sie sicher, dass Sie diese Diagnose löschen möchten?')) {
        Inertia.delete(route('diagnoses.destroy', id));
    }
};
</script>

<style scoped>
/* Custom styles */
</style>
