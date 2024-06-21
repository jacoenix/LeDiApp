<template>
    <AuthenticatedLayout>
        <Head title="Sitzungen verwalten" />
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-6 flex justify-between items-center">
                            <div class="flex items-center">
                                <label for="filter_date" class="mr-2">Filter nach Datum:</label>
                                <input id="filter_date" type="date" v-model="filterDate" @change="filterSessions" class="border rounded p-2 mr-4">
                                <label for="end_date" class="mr-2">Enddatum:</label>
                                <input id="end_date" type="date" v-model="endDate" @change="filterSessions" class="border rounded p-2">
                            </div>
                            <div>
                                <button @click="deleteAllSessions" class="ml-4 bg-red-500 text-white p-2 rounded">Alle Sitzungen löschen</button>
                            </div>
                        </div>
                        <div class="mb-4">
                            <input v-model="searchQuery" type="text" placeholder="Suche nach Titel..." class="w-full p-2 border rounded">
                        </div>
                        <div v-for="session in filteredSessions" :key="session.id" class="border rounded p-4 mb-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p><strong>Titel:</strong> {{ session.title }}</p>
                                    <p><strong>Datum:</strong> {{ session.session_date }}</p>
                                    <p><strong>Zeit:</strong> {{ session.start_time }} - {{ session.end_time }}</p>
                                </div>
                                <div class="flex">
                                    <button @click="openEditModal(session)" class="bg-green-500 text-white p-2 rounded-lg mr-2 hover:bg-green-700">
                                        <svg class="w-6 h-6" :viewBox="'0 0 24 24'">
                                            <path :d="mdiEye" fill="currentColor" />
                                        </svg>
                                    </button>
                                    <button @click="deleteSession(session.id)" class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-700">
                                        <svg class="w-6 h-6" :viewBox="'0 0 24 24'">
                                            <path :d="mdiDelete" fill="currentColor" />
                                        </svg>
                                    </button>
                                    <button @click="exportSession(session.id)" class="bg-yellow-500 text-white p-2 rounded-lg ml-2 hover:bg-yellow-700">
                                        <svg class="w-6 h-6" :viewBox="'0 0 24 24'">
                                            <path :d="mdiFilePdfBox" fill="currentColor" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showEditModal" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-1/2">
                <h3 class="text-xl mb-4">Sitzung bearbeiten</h3>
                <form @submit.prevent="updateSession">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="edit_session_date" class="block font-medium text-sm text-gray-700">Datum</label>
                            <input id="edit_session_date" v-model="selectedSession.session_date" type="date" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="edit_start_time" class="block font-medium text-sm text-gray-700">Startzeit</label>
                            <input id="edit_start_time" v-model="selectedSession.start_time" type="time" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="edit_end_time" class="block font-medium text-sm text-gray-700">Endzeit</label>
                            <input id="edit_end_time" v-model="selectedSession.end_time" type="time" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div class="col-span-2">
                            <label for="edit_title" class="block font-medium text-sm text-gray-700">Titel</label>
                            <input id="edit_title" v-model="selectedSession.title" type="text" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div class="col-span-2">
                            <label for="edit_description" class="block font-medium text-sm text-gray-700">Beschreibung</label>
                            <textarea id="edit_description" v-model="selectedSession.description" rows="5" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700">Änderungen speichern</button>
                        <button @click="closeEditModal" class="ml-2 bg-gray-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-gray-700">Schließen</button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import {Head, usePage} from '@inertiajs/vue3';
import axios from 'axios';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { mdiDelete, mdiEye, mdiFilePdfBox } from "@mdi/js";

const { props } = usePage();
const patientsessions = ref(props.patientsessions || []);
const filterDate = ref('');
const startDate = ref('');
const endDate = ref('');
const searchQuery = ref('');
const showEditModal = ref(false);
const selectedSession = ref(null);
const selectedPatient = ref(props.selectedPatient);

const filteredSessions = computed(() => {
    return patientsessions.value.filter(session => {
        const sessionDate = new Date(session.session_date);
        const start = startDate.value ? new Date(startDate.value) : null;
        const end = endDate.value ? new Date(endDate.value) : null;
        const matchesDate = (!start || sessionDate >= start) && (!end || sessionDate <= end);
        const matchesSearch = session.title.toLowerCase().includes(searchQuery.value.toLowerCase());
        return matchesDate && matchesSearch;
    });
});

const deleteSession = async (sessionId) => {
    if (confirm('Sind Sie sicher, dass Sie diese Sitzung löschen möchten?')) {
        try {
            await axios.delete(route('sessions.destroy', { patient: selectedPatient.value.id, session: sessionId }));
            patientsessions.value = patientsessions.value.filter(session => session.id !== sessionId);
        } catch (error) {
            console.error(error);
            alert('Fehler beim Löschen der Sitzung');
        }
    }
};

const deleteAllSessions = async () => {
    if (!confirm('Sind Sie sicher, dass Sie alle Sitzungen löschen möchten?')) {
        return;
    }
    try {
        await axios.post(route('sessions.deleteAll', { patient: selectedPatient.value.id }));
        patientsessions.value = [];
    } catch (error) {
        console.error(error);
        alert('Fehler beim Löschen aller Sitzungen');
    }
};

const openEditModal = async (session) => {
    try {
        const response = await axios.get(route('sessions.show', { patient: selectedPatient.value.id, session: session.id }));
        selectedSession.value = response.data.session;
        showEditModal.value = true;
    } catch (error) {
        console.error(error);
    }
};

const closeEditModal = () => {
    showEditModal.value = false;
};

const updateSession = async () => {
    try {
        const response = await axios.put(route('sessions.update', { patient: selectedSession.value.patient_id, session: selectedSession.value.id }), selectedSession.value);
        const updatedSession = response.data;
        const index = patientsessions.value.findIndex(session => session.id === updatedSession.id);
        if (index !== -1) {
            patientsessions.value[index] = updatedSession;
        }
        showEditModal.value = false;
    } catch (error) {
        console.error(error);
        alert('Fehler beim Aktualisieren der Sitzung');
    }
};

const exportSession = (sessionId) => {
    window.location.href = route('sessions.export', { patient: selectedPatient.value.id, session: sessionId });
};

</script>

<style scoped>
/* Fügen Sie hier benutzerdefinierte Stile hinzu */
</style>
