<script setup>
import { ref } from 'vue';
import { usePage, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Inertia } from '@inertiajs/inertia';

const { props } = usePage();
const folders = ref(props.folders);
const patientId = ref(props.patientId);
const folderForm = useForm({ name: '', patient_id: patientId.value });

const createFolder = () => {
    folderForm.post(route('folders.store'), {
        onSuccess: (page) => {
            folders.value.push(page.props.newFolder);
            folderForm.reset('name');
        },
        onError: (errors) => {
            console.error(errors);
        }
    });
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
</script>

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
                                <input type="hidden" v-model="folderForm.patient_id" />
                                <button type="submit" class="bg-blue-500 text-white p-2 rounded ml-2">Create Folder</button>
                            </form>
                        </div>

                        <div>
                            <ul>
                                <li v-for="folder in folders" :key="folder.id" class="border rounded p-2 mb-2">
                                    <div class="flex justify-between items-center">
                                        <div>{{ folder.name }}</div>
                                        <button @click="deleteFolder(folder.id)" class="text-red-500">Delete</button>
                                    </div>
                                    <ul class="ml-4">
                                        <li v-for="child in folder.children" :key="child.id" class="border rounded p-2 mt-2">
                                            <div class="flex justify-between items-center">
                                                <div>{{ child.name }}</div>
                                                <button @click="deleteFolder(child.id)" class="text-red-500">Delete</button>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
/* Customize styles here for better look and feel */
</style>
