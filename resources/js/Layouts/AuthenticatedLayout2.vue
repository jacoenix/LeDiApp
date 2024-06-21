<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Inertia } from '@inertiajs/inertia';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

const showingNavigationDropdown = ref(false);

const { props } = usePage();

const toggleSidebar = (event) => {
    event.stopPropagation();
    sidebarVisible.value = !sidebarVisible.value;
};

const closeSidebar = (event) => {
    if (!event.target.closest('.sidebar') && !event.target.closest('.toggle-sidebar-button')) {
        sidebarVisible.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', closeSidebar);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', closeSidebar);
});
</script>
<template>
    <div class="min-h-screen flex flex-col bg-gray-100">
        <nav class="bg-white border-b border-gray-100 w-full fixed top-0 z-10">
            <div class="max-w-full px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20">
                    <div class="flex items-center">
                        <div class="shrink-0 flex items-center">
                            <Link :href="route('dashboard')" class="flex items-center space-x-4">
                                <ApplicationLogo class="block h-12 w-auto fill-current text-gray-800" />
                                <span class="text-3xl font-bold text-gray-800">Ledi</span>
                            </Link>
                        </div>
                        <div class="hidden space-x-6 md:space-x-8 lg:space-x-12 sm:-my-px sm:ml-4 md:ml-10 lg:ml-24 sm:flex">
                            <NavLink :href="route('dashboard')" :active="route().current('dashboard')" class="text-lg">Dashboard</NavLink>
                            <NavLink :href="route('patients.create')" :active="route().current('patients.create')" class="text-lg">Klient erstellen</NavLink>
                            <NavLink :href="route('sessions.create')" :active="route().current('sessions.create')" class="text-lg">Sitzungen verwalten</NavLink>
                            <NavLink :href="route('diagnoses.index')" :active="route().current('diagnoses.index')" class="text-lg">Diagnosen verwalten</NavLink>
                            <NavLink v-if="selectedPatient" :href="route('patient.info', selectedPatient.id)" :active="route().current('patient.info')" class="text-lg">Informationen</NavLink>
                            <NavLink v-if="selectedPatient" :href="route('patient.documents', selectedPatient.id)" :active="route().current('patient.documents')" class="text-lg">Dokumente</NavLink>
                        </div>
                    </div>
                    <div class="hidden sm:flex sm:items-center sm:ml-auto">
                        <div class="ml-3 relative">
                            <Link :href="route('logout')" method="post" as="button" class="bg-red-500 text-white font-bold py-1.5 px-3 rounded-lg hover:bg-red-700 text-lg">
                                Ausloggen
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')" class="text-lg">Dashboard</ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('patients.create')" :active="route().current('patients.create')" class="text-lg">Klient erstellen</ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('sessions.create')" :active="route().current('sessions.create')" class="text-lg">Sitzungen verwalten</ResponsiveNavLink>
                    <ResponsiveNavLink :href="route('diagnoses.index')" :active="route().current('diagnoses.index')" class="text-lg">Diagnosen verwalten</ResponsiveNavLink>
                    <ResponsiveNavLink v-if="selectedPatient" :href="route('patient.info', selectedPatient.id)" :active="route().current('patient.info')" class="text-lg">Informationen</ResponsiveNavLink>
                    <ResponsiveNavLink v-if="selectedPatient" :href="route('patient.documents', selectedPatient.id)" :active="route().current('patient.documents')" class="text-lg">Dokumente</ResponsiveNavLink>
                </div>
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">{{ props.auth.user.name }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ props.auth.user.email }}</div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <ResponsiveNavLink :href="route('profile.edit')" class="text-lg">Profil</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('logout')" method="post" as="button" class="text-lg bg-red-500 text-white">Ausloggen</ResponsiveNavLink>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex flex-1 pt-20"> <!-- Add pt-20 to offset the fixed navbar height -->
            <div class="flex-1 p-4">
                <main>
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>

<style>
.min-h-screen {
    display: flex;
    flex-direction: column;
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
