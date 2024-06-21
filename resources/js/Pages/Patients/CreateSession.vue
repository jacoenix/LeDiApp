<template>
    <AuthenticatedLayout>
        <Head title="Sitzungen verwalten" />
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Sitzungen verwalten</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <button @click="openCreateModal" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 w-full mb-6">Neue Sitzung erstellen</button>
                        <div class="mb-6">
                            <label for="search" class="block text-lg font-medium text-gray-700 mb-2">Sitzungen suchen:</label>
                            <input id="search" type="text" v-model="searchQuery" class="w-full border rounded p-2" placeholder="Nach Titel, Datum oder Klient suchen">
                        </div>
                        <div v-for="session in filteredSessions" :key="session.id" class="border rounded p-4 mb-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-xl font-semibold mb-2">{{ session.title }}</p>
                                    <p><strong>Klient:</strong> {{ session.patient.last_name }} {{ session.patient.first_name }}</p>
                                    <p><strong>Datum:</strong> {{ session.session_date }}</p>
                                </div>
                                <div class="flex space-x-2">
                                    <button @click="openDetailModal(session)" class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-700"><svg class="w-6 h-6" :viewBox="'0 0 24 24'">
                                        <path :d="mdiEye" fill="currentColor" />
                                    </svg></button>
                                    <button @click="exportSession(session.patient_id, session.id)" class="bg-yellow-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-700"><svg class="w-6 h-6" :viewBox="'0 0 24 24'">
                                        <path :d="mdiFilePdfBox" fill="currentColor" />
                                    </svg></button>
                                    <button @click="deleteSession(session.patient_id, session.id)" class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-700"><svg class="w-6 h-6" :viewBox="'0 0 24 24'">
                                        <path :d="mdiDelete" fill="currentColor" />
                                    </svg></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showCreateModal" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-1/2">
                <h3 class="text-xl mb-4">Neue Sitzung erstellen</h3>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="patient_id" class="block font-medium text-sm text-gray-700">Klient*</label>
                            <select id="patient_id" v-model="form.patient_id" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">Wählen Sie einen Klienten</option>
                                <option v-for="patient in patients" :key="patient.id" :value="patient.id">{{ patient.last_name }} {{ patient.first_name }}</option>
                            </select>
                        </div>
                        <div>
                            <label for="session_date" class="block font-medium text-sm text-gray-700">Datum*</label>
                            <input id="session_date" v-model="form.session_date" type="date" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="start_time" class="block font-medium text-sm text-gray-700">Startzeit*</label>
                            <input id="start_time" v-model="form.start_time" type="time" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label for="end_time" class="block font-medium text-sm text-gray-700">Endzeit*</label>
                            <input id="end_time" v-model="form.end_time" type="time" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div class="col-span-1 md:col-span-2">
                            <label for="title" class="block font-medium text-sm text-gray-700">Titel*</label>
                            <input id="title" v-model="form.title" type="text" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>
                        <div class="col-span-1 md:col-span-2">
                            <label for="description" class="block font-medium text-sm text-gray-700">Beschreibung*</label>
                            <textarea id="description" v-model="form.description" rows="5" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700">Erstellen</button>
                        <button @click="closeCreateModal" type="button" class="ml-2 bg-gray-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-gray-700">Schließen</button>
                    </div>
                </form>
            </div>
        </div>
        <div v-if="showDetailModal" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-1/2">
                <h3 class="text-xl mb-4">Sitzung anzeigen</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="patient_id" class="block font-medium text-sm text-gray-700">Klient*</label>
                        <input type="text" v-model="detailSession.patient_name" disabled class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                    </div>
                    <div>
                        <label for="session_date" class="block font-medium text-sm text-gray-700">Datum*</label>
                        <input type="date" v-model="detailSession.session_date" disabled class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                    </div>
                    <div>
                        <label for="start_time" class="block font-medium text-sm text-gray-700">Startzeit*</label>
                        <input type="time" v-model="detailSession.start_time" disabled class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                    </div>
                    <div>
                        <label for="end_time" class="block font-medium text-sm text-gray-700">Endzeit*</label>
                        <input type="time" v-model="detailSession.end_time" disabled class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <label for="title" class="block font-medium text-sm text-gray-700">Titel*</label>
                        <input type="text" v-model="detailSession.title" disabled class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                    </div>
                    <div class="col-span-1 md:col-span-2">
                        <label for="description" class="block font-medium text-sm text-gray-700">Beschreibung*</label>
                        <textarea v-model="detailSession.description" rows="5" disabled class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button @click="closeDetailModal" type="button" class="ml-2 bg-gray-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-gray-700">Schließen</button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout2.vue";
import {mdiDelete, mdiEye, mdiFilePdfBox} from "@mdi/js";

const { props } = usePage();
const patients = ref(props.patients || []);
const sessions = ref(props.sessions || []);
const searchQuery = ref('');
const showCreateModal = ref(false);
const showDetailModal = ref(false);
const detailSession = ref({});

const form = useForm({
    patient_id: '',
    session_date: '',
    start_time: '',
    end_time: '',
    title: '',
    description: '',
});

const submit = () => {
    form.post(route('sessions.store'), {
        onSuccess: () => {
            form.reset();
            closeCreateModal();
            // Refresh the sessions list
            axios.get(route('sessions.all')).then(response => {
                sessions.value = response.data.sessions;
            });
        },
    });
};

const openCreateModal = () => {
    showCreateModal.value = true;
};

const closeCreateModal = () => {
    showCreateModal.value = false;
};

const openDetailModal = (session) => {
    detailSession.value = {
        ...session,
        patient_name: `${session.patient.last_name} ${session.patient.first_name}`
    };
    showDetailModal.value = true;
};

const closeDetailModal = () => {
    showDetailModal.value = false;
};

const filteredSessions = computed(() => {
    if (!searchQuery.value) {
        return sessions.value;
    }
    return sessions.value.filter(session =>
        session.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        session.session_date.includes(searchQuery.value) ||
        `${session.patient.last_name.toLowerCase()} ${session.patient.first_name.toLowerCase()}`.includes(searchQuery.value.toLowerCase())
    );
});

const exportSession = (patientId, sessionId) => {
    window.location.href = route('sessions.export', { patient: patientId, session: sessionId });
};

const deleteSession = async (patientId, sessionId) => {
    if (confirm('Sind Sie sicher, dass Sie diese Sitzung löschen möchten?')) {
        try {
            await axios.delete(route('sessions.destroy', { patient: patientId, session: sessionId }));
            sessions.value = sessions.value.filter(session => session.id !== sessionId);
        } catch (error) {
            console.error(error);
            alert('Fehler beim Löschen der Sitzung');
        }
    }
};
</script>

<style scoped>
/* Fügen Sie hier benutzerdefinierte Stile hinzu */
</style>
