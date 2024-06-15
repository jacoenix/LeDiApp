<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">File Explorer</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="mb-6">
                            <form @submit.prevent="createFolder" class="flex items-center">
                                <input v-model="folderForm.name" type="text" placeholder="Folder name" class="border rounded p-2 flex-1" />
                                <button type="submit" class="bg-blue-500 text-white p-2 rounded ml-2">Create Folder</button>
                            </form>
                        </div>

                        <div class="mb-6">
                            <form @submit.prevent="uploadDocument" enctype="multipart/form-data" class="flex items-center">
                                <input v-model="documentForm.name" type="text" placeholder="Document name" class="border rounded p-2 flex-1" />
                                <input @change="e => documentForm.file = e.target.files[0]" type="file" class="border rounded p-2 ml-2" />
                                <button type="submit" class="bg-green-500 text-white p-2 rounded ml-2">Upload Document</button>
                            </form>
                        </div>

                        <div>
                            <ul>
                                <li v-for="folder in folders" :key="folder.id" class="border rounded p-2 mb-2">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <Link :href="route('folders.show', folder.id)" class="text-blue-500">{{ folder.name }}</Link>
                                        </div>
                                        <button @click="deleteFolder(folder.id)" class="text-red-500">Delete</button>
                                    </div>
                                    <ul class="ml-4">
                                        <li v-for="document in folder.documents" :key="document.id" class="flex justify-between items-center border-b">
                                            <div @click="previewDocument(document)" class="cursor-pointer text-blue-500">{{ document.name }}</div>
                                            <div>
                                                <a :href="route('documents.download', document.id)" class="bg-gray-500 text-white p-1 rounded">Download</a>
                                                <button @click="deleteDocument(document.id)" class="text-red-500 ml-2">Delete</button>
                                            </div>
                                        </li>
                                        <li v-for="child in folder.children" :key="child.id" class="ml-4 border rounded p-2 mt-2">
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <Link :href="route('folders.show', child.id)" class="text-blue-500">{{ child.name }}</Link>
                                                </div>
                                                <button @click="deleteFolder(child.id)" class="text-red-500">Delete</button>
                                            </div>
                                            <ul class="ml-4">
                                                <li v-for="document in child.documents" :key="document.id" class="flex justify-between items-center border-b">
                                                    <div @click="previewDocument(document)" class="cursor-pointer text-blue-500">{{ document.name }}</div>
                                                    <div>
                                                        <a :href="route('documents.download', document.id)" class="bg-gray-500 text-white p-1 rounded">Download</a>
                                                        <button @click="deleteDocument(document.id)" class="text-red-500 ml-2">Delete</button>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Preview Modal -->
        <div v-if="selectedDocument" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white p-4 rounded shadow-lg">
                <img :src="`/storage/${selectedDocument.file_path}`" alt="Preview" class="max-w-full h-auto" />
                <button @click="closePreview" class="bg-red-500 text-white p-2 rounded mt-2">Close</button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { usePage, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import axios from 'axios';

const { props } = usePage();
const folders = ref(props.folders);
const documents = ref([]);
const parentFolder = ref(props.parentFolder || null);
const patientId = ref(props.patientId);
const patients = ref(props.patients || []);
const selectedPatient = ref(props.selectedPatient || null);

const folderForm = useForm({ name: '', parent_id: parentFolder.value ? parentFolder.value.id : null, patient_id: patientId.value });
const documentForm = useForm({ name: '', file: null, folder_id: parentFolder.value ? parentFolder.value.id : null });
const selectedDocument = ref(null);

const createFolder = async () => {
    try {
        const response = await axios.post(route('folders.store'), folderForm);
        folders.value.push(response.data.folder);
        folderForm.reset('name');
    } catch (error) {
        console.error(error);
    }
};

const uploadDocument = async () => {
    try {
        const formData = new FormData();
        formData.append('name', documentForm.name);
        formData.append('file', documentForm.file);
        formData.append('folder_id', documentForm.folder_id);

        const response = await axios.post(route('documents.store'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });

        const newDocument = response.data.document;
        documents.value.push(newDocument);
        documentForm.reset('name', 'file');
    } catch (error) {
        console.error(error);
    }
};

const addFolder = (folder) => {
    folders.value.push(folder);
};

const addDocument = (document) => {
    documents.value.push(document);
};

const deleteFolder = (folderId) => {
    if (confirm('Are you sure you want to delete this folder?')) {
        Inertia.delete(route('folders.destroy', folderId), {
            onSuccess: () => {
                folders.value = folders.value.filter(folder => folder.id !== folderId);
            }
        });
    }
};

const deleteDocument = (documentId) => {
    if (confirm('Are you sure you want to delete this document?')) {
        Inertia.delete(route('documents.destroy', documentId), {
            onSuccess: () => {
                documents.value = documents.value.filter(document => document.id !== documentId);
            }
        });
    }
};

const previewDocument = (document) => {
    if (document.file_path.endsWith('.jpg') || document.file_path.endsWith('.png') || document.file_path.endsWith('.jpeg')) {
        selectedDocument.value = document;
    } else {
        window.location.href = route('documents.download', document.id);
    }
};

const closePreview = () => {
    selectedDocument.value = null;
};
</script>

<style>
/* Customize styles here for better look and feel */
</style>
