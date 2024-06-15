<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import AuthenticatedLayout
    from '@/Layouts/AuthenticatedLayout.vue';
import {mdiDelete, mdiDownload, mdiPencil} from "@mdi/js";

const { props } = usePage();
const folders = ref(props.folders);
const currentFolder = ref(props.currentFolder || { documents: [] });
const path = ref(props.path);
const documents = ref(props.documents || []);
const selectedPatient = ref(props.selectedPatient);
const patients = ref(props.patients);
const auth = ref(props.auth);

const showFolderModal = ref(false);
const showDocumentModal = ref(false);

const newFolder = ref({
    name: '',
});

const newDocument = ref({
    name: '',
    file: null,
});
const editFolder = ref({
    id: null,
    name: ''
});

const showEditFolderModal = ref(false);

const handleEditFolder = (folder) => {
    editFolder.value = { ...folder };
    showEditFolderModal.value = true;
};

const updateFolder = async () => {
    try {
        const response = await axios.put(route('folders.update', { folder: editFolder.value.id, patient: selectedPatient.value.id }), editFolder.value);
        const updatedFolder = response.data;
        const index = folders.value.findIndex(folder => folder.id === updatedFolder.id);
        if (index !== -1) {
            folders.value[index] = updatedFolder;
        }
        showEditFolderModal.value = false;
    } catch (error) {
        console.error(error);
        alert('Fehler beim Aktualisieren des Ordners');
    }
};

const editDocument = ref({
    id: null,
    name: ''
});

const showEditDocumentModal = ref(false);

const handleEditDocument = (document) => {
    editDocument.value = { ...document };
    showEditDocumentModal.value = true;
};

const updateDocument = async () => {
    try {
        const response = await axios.put(route('documents.update', { document: editDocument.value.id, patient: selectedPatient.value.id }), editDocument.value);
        const updatedDocument = response.data;
        const index = documents.value.findIndex(doc => doc.id === updatedDocument.id);
        if (index !== -1) {
            documents.value[index] = updatedDocument;
        }
        showEditDocumentModal.value = false;
    } catch (error) {
        console.error(error);
        alert('Fehler beim Aktualisieren des Dokuments');
    }
};
const deleteDocument = async (documentId) => {
    if (confirm('Möchten Sie dieses Dokument wirklich löschen?')) {
        try {
            await axios.delete(route('documents.destroy', { document: documentId, patient: selectedPatient.value.id }));
            documents.value = documents.value.filter(doc => doc.id !== documentId);
        } catch (error) {
            console.error(error);
            alert('Fehler beim Löschen des Dokuments');
        }
    }
};

const deleteFolder = async (folderId) => {
    if (confirm('Möchten Sie diesen Ordner wirklich löschen?')) {
        try {
            await axios.delete(route('folders.destroy', { folder: folderId, patient: selectedPatient.value.id }));
            folders.value = folders.value.filter(folder => folder.id !== folderId);
        } catch (error) {
            console.error(error);
            alert('Fehler beim Löschen des Ordners');
        }
    }
};
const createFolder = async () => {
    try {
        const response = await axios.post(route('folders.store', {
            patient: selectedPatient.value.id,
            folder: currentFolder.value ? currentFolder.value.id : undefined
        }), newFolder.value);
        const createdFolder = response.data;
        folders.value.push(createdFolder);
        showFolderModal.value = false;
        newFolder.value.name = '';
    } catch (error) {
        console.error(error);
        alert('Fehler beim Erstellen des Ordners');
    }
};

const handleFileChange = (event) => {
    newDocument.value.file = event.target.files[0];
};

const uploadDocument = async () => {
    const data = new FormData();
    data.append('name', newDocument.value.name);
    data.append('file', newDocument.value.file);

    try {
        const response = await axios.post(route('documents.store', {
            patient: selectedPatient.value.id,
            folder: currentFolder.value ? currentFolder.value.id : undefined
        }), data);
        const createdDocument = response.data;
        if (!currentFolder.value.documents) {
            currentFolder.value.documents = [];
        }
        currentFolder.value.documents.push(createdDocument);
        documents.value.push(createdDocument); // Füge das neue Dokument zur Liste der Dokumente hinzu
        showDocumentModal.value = false;
        newDocument.value.name = '';
        newDocument.value.file = null;
    } catch (error) {
        console.error(error);
        alert('Fehler beim Hochladen des Dokuments');
    }
};
const downloadFolderAsZip = async (folderId) => {
    try {
        const response = await axios.get(route('folders.downloadZip', { folder: folderId, patient: selectedPatient.value.id }), {
            responseType: 'blob'
        });
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'folder.zip');
        document.body.appendChild(link);
        link.click();
    } catch (error) {
        console.error(error);
        alert('Sie können keinen leeren Ordner herunterladen!');
    }
};
const downloadDocument = async (document) => {
    try {
        const response = await axios.get(route('documents.download', {
            patient: selectedPatient.value.id,
            folder: currentFolder.value.id,
            document: document.id
        }), {
            responseType: 'blob'
        });

        const blob = new Blob([response.data], { type: response.headers['content-type'] });
        const url = window.URL.createObjectURL(blob);
        const link = window.document.createElement('a');
        link.href = url;
        link.setAttribute('download', document.name);
        window.document.body.appendChild(link);
        link.click();
        window.document.body.removeChild(link);
    } catch (error) {
        console.error(error);
        alert('Fehler beim Herunterladen des Dokuments');
    }
};



</script>
<template>
    <AuthenticatedLayout :auth="auth" :selected-patient="selectedPatient" :patients="patients">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <nav>
                            <ul class="breadcrumb flex text-gray-600 text-xl">
                                <li v-for="(folder, index) in path" :key="folder.id" class="flex items-center">
                                    <Link :href="route('documents.index', { patient: selectedPatient.id, folder: folder.id })" class="hover:underline">
                                        {{ folder.name }}
                                    </Link>
                                    <span v-if="index < path.length - 1"> / </span>
                                </li>
                            </ul>
                        </nav>
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold">Ordner</h3>
                            <ul class="mt-4 space-y-2">
                                <li v-for="folder in folders" :key="folder.id" class="bg-gray-100 p-4 rounded-md shadow flex justify-between items-center">
                                    <Link :href="route('documents.index', { patient: selectedPatient.id, folder: folder.id })" class="text-blue-600 hover:underline">
                                        {{ folder.name }}
                                    </Link>
                                    <div class="flex space-x-2">
                                        <div class="flex space-x-2">
                                            <button @click="downloadFolderAsZip(folder.id)" class="text-blue-600 hover:underline">
                                                <svg class="w-6 h-6" :viewBox="'0 0 24 24'">
                                                    <path :d="mdiDownload" fill="currentColor" />
                                                </svg>
                                            </button>
                                            <button @click="handleEditFolder(folder)" class="text-yellow-600 hover:underline">
                                                <svg class="w-6 h-6" :viewBox="'0 0 24 24'">
                                                    <path :d="mdiPencil" fill="currentColor" />
                                                </svg>
                                            </button>
                                            <button @click="deleteFolder(folder.id)" class="text-red-600 hover:underline">
                                                <svg class="w-6 h-6" :viewBox="'0 0 24 24'">
                                                    <path :d="mdiDelete" fill="currentColor" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <button @click="showFolderModal = true" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 mt-4">Neuen Ordner erstellen</button>
                        </div>
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold">Dokumente</h3>
                            <ul class="mt-4 space-y-2">
                                <li v-for="document in documents" :key="document.id" class="bg-gray-100 p-4 rounded-md shadow flex justify-between items-center">
                                    <div>
                                        <a @click.prevent="downloadDocument(document)" class="hover:underline cursor-pointer">{{ document.name }}</a>
                                    </div>
                                    <div class="flex space-x-2">
                                        <div class="flex space-x-2">
                                            <button @click="handleEditDocument(document)" class="text-yellow-600 hover:underline">
                                                <svg class="w-6 h-6" :viewBox="'0 0 24 24'">
                                                    <path :d="mdiPencil" fill="currentColor" />
                                                </svg>
                                            </button>
                                            <button @click="deleteDocument(document.id)" class="text-red-600 hover:underline">
                                                <svg class="w-6 h-6" :viewBox="'0 0 24 24'">
                                                    <path :d="mdiDelete" fill="currentColor" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                            <button @click="showDocumentModal = true" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 mt-4">Neues Dokument hochladen</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ordner erstellen Modal -->
        <div v-if="showFolderModal" class="modal fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="modal-content bg-white p-6 rounded-lg shadow-lg">
                <span @click="showFolderModal = false" class="close absolute top-2 right-4 text-2xl cursor-pointer">&times;</span>
                <form @submit.prevent="createFolder">
                    <label for="folder-name" class="block text-sm font-medium text-gray-700">Ordner Name</label>
                    <input type="text" v-model="newFolder.name" id="folder-name" required class="mt-2 mb-4 p-2 border rounded-md w-full">
                    <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 w-full">Erstellen</button>
                </form>
            </div>
        </div>

        <!-- Dokument hochladen Modal -->
        <div v-if="showDocumentModal" class="modal fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="modal-content bg-white p-6 rounded-lg shadow-lg">
                <span @click="showDocumentModal = false" class="close absolute top-2 right-4 text-2xl cursor-pointer">&times;</span>
                <form @submit.prevent="uploadDocument">
                    <label for="document-name" class="block text-sm font-medium text-gray-700">Dokument Name</label>
                    <input type="text" v-model="newDocument.name" id="document-name" required class="mt-2 mb-4 p-2 border rounded-md w-full">
                    <label for="document-file" class="block text-sm font-medium text-gray-700">Datei</label>
                    <input type="file" @change="handleFileChange" id="document-file" required class="mt-2 mb-4 p-2 border rounded-md w-full">
                    <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 w-full">Hochladen</button>
                </form>
            </div>
        </div>
        <!-- Ordner umbenennen Modal -->
        <div v-if="showEditFolderModal" class="modal fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="modal-content bg-white p-6 rounded-lg shadow-lg">
                <span @click="showEditFolderModal = false" class="close absolute top-2 right-4 text-2xl cursor-pointer">&times;</span>
                <form @submit.prevent="updateFolder">
                    <label for="edit-folder-name" class="block text-sm font-medium text-gray-700">Neuer Ordner Name</label>
                    <input type="text" v-model="editFolder.name" id="edit-folder-name" required class="mt-2 mb-4 p-2 border rounded-md w-full">
                    <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 w-full">Aktualisieren</button>
                </form>
            </div>
        </div>

        <!-- Dokument umbenennen Modal -->
        <div v-if="showEditDocumentModal" class="modal fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div class="modal-content bg-white p-6 rounded-lg shadow-lg">
                <span @click="showEditDocumentModal = false" class="close absolute top-2 right-4 text-2xl cursor-pointer">&times;</span>
                <form @submit.prevent="updateDocument">
                    <label for="edit-document-name" class="block text-sm font-medium text-gray-700">Neuer Dokument Name</label>
                    <input type="text" v-model="editDocument.name" id="edit-document-name" required class="mt-2 mb-4 p-2 border rounded-md w-full">
                    <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 w-full">Aktualisieren</button>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
.modal {
    display: flex;
    justify-content: center;
    align-items: center;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    position: relative;
}

.close {
    position: absolute;
    top: 10px;
    right: 20px;
    font-size: 24px;
    cursor: pointer;
}

.breadcrumb {
    list-style: none;
    display: flex;
}

.breadcrumb li {
    display: flex;
    align-items: center;
}

.breadcrumb li a {
    text-decoration: none;
    color: #3B82F6;
}

.breadcrumb li a:hover {
    text-decoration: underline;
}

.bg-blue-500 {
    background-color: #3B82F6;
}

.hover\:bg-blue-700:hover {
    background-color: #2563EB;
}

.text-blue-600 {
    color: #2563EB;
}

.hover\:underline:hover {
    text-decoration: underline;
}
</style>
