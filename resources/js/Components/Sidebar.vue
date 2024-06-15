<template>
    <div class="sidebar">
        <ul>
            <li
                v-for="patient in patients"
                :key="patient.id"
                @click="selectPatient(patient)"
                :class="{'bg-gray-200': selectedPatient && selectedPatient.id === patient.id}"
            >
                {{ patient.name }}
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: {
        patients: Array,
        selectedPatient: Object,
    },
    methods: {
        selectPatient(patient) {
            this.$emit('patientSelected', patient);
        },
    },
};
</script>

<style>
/* CSS für Sidebar */
.sidebar {
    width: 250px;
    position: fixed;
    height: 100%;
    background-color: #f8f9fa;
    padding: 10px;
    box-shadow: 2px 0 5px rgba(0,0,0,0.1);
    overflow-y: auto;
    z-index: 1000;
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
    background-color: #e9ecef;
}

.bg-gray-200 {
    background-color: #e9ecef;
}

@media (max-width: 768px) {
    .sidebar {
        position: absolute;
        left: -250px;
        transition: left 0.3s;
    }

    .sidebar.active {
        left: 0;
    }
}
</style>
