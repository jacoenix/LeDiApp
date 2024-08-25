<script setup>
import { ref } from 'vue';
import {usePage, useForm, Head} from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import MultiSelect from 'primevue/multiselect';

const { props } = usePage();
const patient = ref(props.patient);
const diagnoses = ref(props.diagnoses);

const form = useForm({
    first_name: patient.value.first_name || '',
    last_name: patient.value.last_name || '',
    patient_number: patient.value.patient_number || '',
    address: patient.value.address || '',
    birth_date: patient.value.birth_date || '',
    information: patient.value.information || '',
    therapy_for: patient.value.therapy_for || '',
    therapy_till: patient.value.therapy_till || '',
    phone_number: patient.value.phone_number || '',
    mothers_name: patient.value.mothers_name || '',
    fathers_name: patient.value.fathers_name || '',
    parents_email: patient.value.parents_email || '',
    parents_work: patient.value.parents_work || '',
    status: patient.value.status || 'active',
    grade: patient.value.grade || '',
    gender: patient.value.gender || '',
    school_type: patient.value.school_type || '',
    school_postcode: patient.value.school_postcode || '',
    diagnosis_ids: patient.value.diagnoses ? patient.value.diagnoses.map(d => d.id) : [],  // Diagnose-IDs als Array
});

const submit = () => {
    form.put(route('patient.update', patient.value.id), {
        preserveScroll: true
    });
};

const exportPatient = () => {
    window.location.href = route('patient.export', patient.value.id);
};
const genders = ref(['--------------', 'männlich', 'weiblich', 'divers']);

const schoolTypes = ref(['--------------','Kindergarten', 'Volksschule', 'Waldorfschule', 'Mittelschule', 'Gymnasium', 'Polytechnikum', 'Lehre', 'Fachschule', 'HAK', 'HLW', 'HTL', 'Heimunterricht', 'Arbeit']);
const grades = ref(['--------------','1. Schulstufe', '2. Schulstufe', '3. Schulstufe', '4. Schulstufe', '5. Schulstufe', '6. Schulstufe', '7. Schulstufe', '8. Schulstufe', '9. Schulstufe', '10. Schulstufe', '11. Schulstufe', '12. Schulstufe', '13. Schulstufe']);

const deletePatient = () => {
    if (confirm('Sind Sie sicher, dass Sie diesen Klienten löschen möchten?')) {
        form.delete(route('patient.destroy', patient.value.id), {
            onSuccess: () => {
                window.location.href = route('dashboard');
            }
        });
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Info" />
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                <!-- First Name -->
                                <div>
                                    <label for="first_name" class="block font-medium text-sm text-gray-700">Vorname*</label>
                                    <input id="first_name" v-model="form.first_name" type="text" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <!-- Last Name -->
                                <div>
                                    <label for="last_name" class="block font-medium text-sm text-gray-700">Nachname*</label>
                                    <input id="last_name" v-model="form.last_name" type="text" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <!-- Gender -->
                                <div>
                                    <label for="gender" class="block font-medium text-sm text-gray-700">Geschlecht</label>
                                    <select id="gender" v-model="form.gender" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option v-for="type in genders" :key="type" :value="type">{{ type }}</option>
                                    </select>
                                </div>
                                <!-- Patient Number -->
                                <div>
                                    <label for="patient_number" class="block font-medium text-sm text-gray-700">Klientennummer*</label>
                                    <input id="patient_number" v-model="form.patient_number" type="number" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                                <!-- School Type -->
                                <div>
                                    <label for="school_type" class="block font-medium text-sm text-gray-700">Schultyp</label>
                                    <select id="school_type" v-model="form.school_type" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option v-for="type in schoolTypes" :key="type" :value="type">{{ type }}</option>
                                    </select>
                                </div>
                                <!-- School Grade -->
                                <div>
                                    <label for="grade" class="block font-medium text-sm text-gray-700">Schulstufe</label>
                                    <select id="grade" v-model="form.grade" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option v-for="grade in grades" :key="grade" :value="grade">{{ grade }}</option>
                                    </select>
                                </div>
                                <!-- School Postal -->
                                <div>
                                    <label for="school_postcode" class="block font-medium text-sm text-gray-700">Ort der Einrichtung</label>
                                    <input id="school_postcode" v-model="form.school_postcode" type="text" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <!-- Birth Date -->
                                <div>
                                    <label for="birth_date" class="block font-medium text-sm text-gray-700">Geburtsdatum</label>
                                    <input id="birth_date" v-model="form.birth_date" type="date" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <!-- Therapy For -->
                                <div>
                                    <label for="therapy_for" class="block font-medium text-sm text-gray-700">Behandlungsbeginn</label>
                                    <input id="therapy_for" v-model="form.therapy_for" type="date" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <!-- Therapy For -->
                                <div>
                                    <label for="therapy_till" class="block font-medium text-sm text-gray-700">Behandlungsende</label>
                                    <input id="therapy_till" v-model="form.therapy_till" type="date" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-3">
                                <!-- Diagnoses -->
                                <div class="col-span-2 mt-4">
                                    <label for="diagnosis_ids" class="block font-medium text-sm text-gray-700">Diagnosen</label>
                                    <MultiSelect
                                        v-model="form.diagnosis_ids"
                                        :options="diagnoses"
                                        option-label="name"
                                        option-value="id"
                                        placeholder="Wähle Diagnosen"
                                        class="w-full border rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    ></MultiSelect>
                                </div>

                                <!-- Address -->
                                <div class="col-span-2">
                                    <label for="address" class="block font-medium text-sm text-gray-700">Adresse</label>
                                    <input id="address" v-model="form.address" type="text" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>

                                <!-- Mother's Name -->
                                <div>
                                    <label for="mothers_name" class="block font-medium text-sm text-gray-700">Name der Mutter</label>
                                    <input id="mothers_name" v-model="form.mothers_name" type="text" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <!-- Father's Name -->
                                <div>
                                    <label for="fathers_name" class="block font-medium text-sm text-gray-700">Name des Vaters</label>
                                    <input id="fathers_name" v-model="form.fathers_name" type="text" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <!-- Phone Number -->
                                <div>
                                    <label for="phone_number" class="block font-medium text-sm text-gray-700">Telefonnummer</label>
                                    <input id="phone_number" v-model="form.phone_number" type="text" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <!-- Parents' Email -->
                                <div>
                                    <label for="parents_email" class="block font-medium text-sm text-gray-700">Email der Eltern</label>
                                    <input id="parents_email" v-model="form.parents_email" type="email" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <!-- Status -->

                                <!-- Parents' Work -->
                                <div class="col-span-2">
                                    <label for="parents_work" class="block font-medium text-sm text-gray-700">Beruf der Eltern</label>
                                    <textarea id="parents_work" v-model="form.parents_work" rows="3" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                </div>
                                <!-- Information -->
                                <div class="col-span-2">
                                    <label for="information" class="block font-medium text-sm text-gray-700">Information</label>
                                    <textarea id="information" v-model="form.information" rows="10" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                                </div>
                                <div class="col-span-2">
                                    <label for="status" class="block font-medium text-sm text-gray-700">Status</label>
                                    <select id="status" v-model="form.status" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="active">Aktiv</option>
                                        <option value="inactive">Inaktiv</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-between items-center">
                                <div class="flex gap-4">
                                    <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-6 rounded-lg hover:bg-blue-700">Änderungen speichern</button>
                                    <Transition
                                        enter-active-class="transition ease-in-out"
                                        enter-from-class="opacity-0"
                                        leave-active-class="transition ease-in-out"
                                        leave-to-class="opacity-0"
                                    >
                                        <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Änderungen gespeichert.</p>
                                    </Transition>
                                </div>
                            </div>
                        </form>
                        <div class="mt-6 flex justify-end">
                            <button @click="exportPatient" class="bg-green-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-700">Klientenblatt</button>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button @click="deletePatient" class="bg-red-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-red-700">Klient löschen</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Custom styles */
</style>
