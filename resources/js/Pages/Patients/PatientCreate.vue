<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout2.vue';

const form = useForm({
    first_name: '',
    last_name: '',
    patient_number: '',
    address: '',
    birth_date: '',
    therapy_for: '',
    information: '',
    phone_number: '',
    mothers_name: '',
    fathers_name: '',
    parents_email: '',
    parents_work: '',
    grade: '',
    school_postcode: '',
    school_type: ''


});

const schoolTypes = ref(['--------------','Kindergarten', 'Volksschule', 'Mittelschule', 'Gymnasium', 'Polytechnikum', 'Lehre', 'Fachschule', 'HAK', 'HLW', 'HTL', 'Heimunterricht', 'Arbeit']);
const grades = ref(['--------------','1. Schulstufe', '2. Schulstufe', '3. Schulstufe', '4. Schulstufe', '5. Schulstufe', '6. Schulstufe', '7. Schulstufe', '8. Schulstufe', '9. Schulstufe', '10. Schulstufe', '11. Schulstufe', '12. Schulstufe', '13. Schulstufe']);

const submit = () => {
    form.post(route('patients.store'), {
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                                <!-- Patient Number -->
                                <div>
                                    <label for="patient_number" class="block font-medium text-sm text-gray-700">Patientennummer*</label>
                                    <input id="patient_number" v-model="form.patient_number" type="number" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-3">
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
                                    <label for="school_postcode" class="block font-medium text-sm text-gray-700">PLZ der Einrichtung</label>
                                    <input id="school_postcode" v-model="form.school_postcode" type="number" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                <!-- Address -->
                                <div class="col-span-2">
                                    <label for="address" class="block font-medium text-sm text-gray-700">Adresse</label>
                                    <input id="address" v-model="form.address" type="text" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <!-- Birth Date -->
                                <div>
                                    <label for="birth_date" class="block font-medium text-sm text-gray-700">Geburtsdatum</label>
                                    <input id="birth_date" v-model="form.birth_date" type="date" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                </div>
                                <!-- Therapy For -->
                                <div>
                                    <label for="therapy_for" class="block font-medium text-sm text-gray-700">In Behandlung seit</label>
                                    <input id="therapy_for" v-model="form.therapy_for" type="date" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
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
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700">Patient erstellen</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Stile für das Formular */
input, textarea {
    transition: all 0.2s;
}

input:focus, textarea:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 1px #6366f1;
}
</style>

