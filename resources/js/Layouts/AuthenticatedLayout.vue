<template>
    <div class="min-h-screen flex flex-col bg-gray-100">
        <nav class="bg-white border-b border-gray-100 w-full fixed top-0 z-10">
            <div class="max-w-full px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center">
                        <button @click="toggleSidebar" class="toggle-sidebar-button mr-4 text-gray-500 hover:text-gray-700">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        <div class="shrink-0 flex items-center">
                            <Link :href="route('dashboard')" class="flex items-center space-x-4">
                                <ApplicationLogo class="block h-12 w-auto fill-current text-gray-800" />
                                <span class="text-3xl font-bold text-gray-800">Ledi</span>
                            </Link>
                        </div>
                        <div class="hidden space-x-6 md:space-x-8 lg:space-x-12 sm:-my-px sm:ml-4 md:ml-10 lg:ml-24 sm:flex">
                            <NavLink :href="route('dashboard')" :active="route().current('dashboard')" class="text-lg">Dashboard</NavLink>
                            <NavLink :href="route('notes')" :active="route().current('notes')" class="text-lg">Notizen</NavLink>
                            <NavLink v-if="selectedPatient" :href="route('patient.info', selectedPatient.id)" :active="route().current('patient.info')" class="text-lg">Informationen</NavLink>
                            <NavLink v-if="selectedPatient" :href="route('documents.index', selectedPatient.id)" :active="route().current('documents.index')" class="text-lg">Dokumentenverwaltung</NavLink>
                            <NavLink v-if="selectedPatient" :href="route('sessions.index', selectedPatient.id)" :active="route().current('sessions.index')" class="text-lg">Sitzungen</NavLink>
                        </div>
                    </div>
                    <div class="hidden sm:flex sm:items-center sm:ml-auto">
                        <div class="ml-3 relative">
                            <Link :href="route('logout')" method="post" as="button" class="bg-red-500 text-white font-bold py-1.5 px-3 rounded-lg hover:bg-red-700 text-lg">
                                Ausloggen
                            </Link>
                        </div>
                    </div>
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')" class="text-lg">Dashboard</ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('notes')" :active="route().current('notes')" class="text-lg">Notizen</ResponsiveNavLink>
                    <ResponsiveNavLink v-if="selectedPatient" :href="route('patient.info', selectedPatient.id)" :active="route().current('patient.info')" class="text-lg">Informationen</ResponsiveNavLink>
                    <ResponsiveNavLink v-if="selectedPatient" :href="route('documents.index', selectedPatient.id)" :active="route().current('documents.index')" class="text-lg">Dokumentenverwaltung</ResponsiveNavLink>
                    <ResponsiveNavLink v-if="selectedPatient" :href="route('sessions.index', selectedPatient.id)" :active="route().current('sessions.index')" class="text-lg">Sitzungen</ResponsiveNavLink>
                </div>
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">{{ auth.name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ auth.email }}</div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <ResponsiveNavLink :href="route('profile.edit')" class="text-lg">Profil</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('logout')" method="post" as="button" class="text-lg bg-red-500 text-white">Ausloggen</ResponsiveNavLink>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex flex-1 pt-20" @click="closeSidebarOnClick">
            <aside :class="['sidebar', { active: sidebarVisible }]">
                <div class="relative mb-4">
                    <input
                        type="text"
                        placeholder="Suche Klienten"
                        v-model="searchQuery"
                        class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                    />
                </div>
                <ul>
                    <li v-for="patient in sortedAndFilteredPatients" :key="patient.id" @click="selectPatient(patient)" :class="{'bg-gray-200': selectedPatient && selectedPatient.id === patient.id}">
                        {{ patient.last_name }} {{ patient.first_name }}
                    </li>
                </ul>
                <Dropdown class="mt-4">
                    <template #trigger>
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                            Inaktive Klienten
                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L10 12.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </template>
                    <template #content>
                        <div class="w-48">
                            <DropdownLink v-for="patient in sortedInactivePatients" :key="patient.id" :href="route('patient.info', patient.id)">
                                {{ patient.last_name }} {{ patient.first_name }}
                            </DropdownLink>
                        </div>
                    </template>
                </Dropdown>
            </aside>

            <div :class="['main-content-wrapper flex-1 transition-all', { 'ml-64': sidebarVisible, 'ml-0': !sidebarVisible }]">
                <header class="bg-white shadow" v-if="$slots.header">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <slot name="header" />
                    </div>
                </header>
                <main class="main-content">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onBeforeUnmount, onMounted, ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Inertia } from "@inertiajs/inertia";

const showingNavigationDropdown = ref(false);
const sidebarVisible = ref(window.innerWidth >= 1024);

const { props } = usePage();
const patients = ref(props.patients || []);
const inactivePatients = ref(props.inactivePatients || []);
const selectedPatient = ref(props.selectedPatient || null);
const auth = ref(props.auth);
const searchQuery = ref('');

const sortedAndFilteredPatients = computed(() => {
    let sortedPatients = patients.value.slice().sort((a, b) => {
        if (a.last_name < b.last_name) {
            return -1;
        }
        if (a.last_name > b.last_name) {
            return 1;
        }
        return 0;
    });

    if (!searchQuery.value) {
        return sortedPatients;
    }

    return sortedPatients.filter(patient =>
        patient.first_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        patient.last_name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const sortedInactivePatients = computed(() => {
    return inactivePatients.value.slice().sort((a, b) => {
        if (a.last_name < b.last_name) {
            return -1;
        }
        if (a.last_name > b.last_name) {
            return 1;
        }
        return 0;
    });
});

const selectPatient = (patient) => {
    selectedPatient.value = patient;
    Inertia.get(route('patient.info', patient.id), { preserveScroll: true });
};

const toggleSidebar = (event) => {
    event.stopPropagation();
    sidebarVisible.value = !sidebarVisible.value;
};

const closeSidebarOnClick = (event) => {
    if (window.innerWidth < 1024 && !event.target.closest('.sidebar') && !event.target.closest('.toggle-sidebar-button')) {
        sidebarVisible.value = false;
    }
};

const handleResize = () => {
    if (window.innerWidth >= 1024) {
        sidebarVisible.value = true;
    } else {
        sidebarVisible.value = false;
    }
};

onMounted(() => {
    window.addEventListener('resize', handleResize);
    handleResize();
});

onBeforeUnmount(() => {
    window.removeEventListener('resize', handleResize);
});
</script>

<style>
.min-h-screen {
    display: flex;
    flex-direction: column;
}

.sidebar {
    width: 250px;
    background-color: #f8f9fa;
    padding: 10px;
    box-shadow: 2px 0 5px rgba(0,0,0,0.1);
    overflow-y: auto;
    height: calc(100vh - 5rem);
    position: fixed;
    top: 5rem;
}

.sidebar ul {
    list-style-type: none;
    padding: 0;
}

.sidebar li {
    padding: 10px;
    cursor: pointer;
}

.sidebar li:hover {
    background-color: #66B3FF;
    color: white;
    border-radius: 4px;
}

.bg-gray-200 {
    background-color: #3B82F6;
    color: white;
    border-radius: 4px;
}

@media (max-width: 1024px) {
    .sidebar {
        left: -250px;
        transition: left 0.3s;
        z-index: 1000;
    }

    .sidebar.active {
        left: 0;
    }

    .toggle-sidebar-button {
        display: block;
    }
}

.toggle-sidebar-button {
    display: none;
}

@media (max-width: 1024px) {
    .toggle-sidebar-button {
        display: block;
    }
}

.main-content-wrapper {
    transition: margin-left 0.3s;
}

.main-content-wrapper.ml-64 {
    margin-left: 250px;
}

.main-content-wrapper.ml-0 {
    margin-left: 0;
}

.main-content {
    padding: 20px;
    max-width: 100%;
}
</style>
