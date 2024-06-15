<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Neue Sitzung erstellen</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="patient_id" class="block font-medium text-sm text-gray-700">Patient*</label>
                                    <select id="patient_id" v-model="form.patient_id" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">Wählen Sie einen Patienten</option>
                                        <option v-for="patient in patients" :key="patient.id" :value="patient.id">{{ patient.last_name }} {{ patient.first_name }} </option>
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
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout2.vue";

const { props } = usePage();
const patients = ref(props.patients || []);

const form = useForm({
    patient_id: '',
    session_date: '',
    start_time: '',
    end_time: '',
    title: '' || null,
    description: '',
});

const submit = () => {
    form.post(route('sessions.store'));
};
</script>

<style scoped>
/* Fügen Sie hier benutzerdefinierte Stile hinzu */
</style>
