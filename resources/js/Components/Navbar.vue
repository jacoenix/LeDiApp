<template>
    <nav>
        <ul v-if="selectedPatient">
            <li @click="navigateTo('info')">Informationen</li>
            <li @click="navigateTo('documents')">Dokumente</li>
            <!-- Weitere Bereiche -->
        </ul>
    </nav>
</template>

<script>
export default {
    props: {
        selectedPatient: Object,
    },
    methods: {
        navigateTo(section) {
            this.$inertia.get(route(`patient.${section}`, this.selectedPatient.id));
        },
    },
};
</script>

<style>
/* CSS für Navbar */
nav {
    background-color: #343a40;
    color: white;
    padding: 10px;
}

nav ul {
    list-style-type: none;
    padding: 0;
    display: flex;
}

nav li {
    padding: 10px;
    cursor: pointer;
}

nav li:hover {
    background-color: #495057;
}
</style>
