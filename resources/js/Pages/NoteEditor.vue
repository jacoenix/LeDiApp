<template>
    <AuthenticatedLayout>
        <Head title="Notizen" />
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- Benutzer-Icon zum Erstellen eines neuen Patienten -->
                        <div class="flex flex-col items-center">
                            <span class="text-xl font-bold text-gray-800">Notizen</span>
                            <br>
                            <Editor id="edit_description"  v-model="noteContent" editorStyle="height: 380px" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                            <br>
                            <button @click="saveNote" type="button" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700">
                                Notizen speichern
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import {Head, usePage} from '@inertiajs/vue3';
import axios from 'axios';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Editor from 'primevue/editor';

// Die Notiz aus Inertia-Props laden
const page = usePage();
const noteContent = ref(page.props.note.content);

const saveNote = async () => {
    try {
        await axios.post('/note', { content: noteContent.value });
        alert('Notiz erfolgreich gespeichert!');
    } catch (error) {
        console.error(error);
        alert('Fehler beim Speichern der Notiz.');
    }
};
</script>
