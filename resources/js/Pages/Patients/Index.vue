<template>
    <div>
        <h1>Klienten</h1>
        <ul>
            <li v-for="patient in patients" :key="patient.id">
                {{ patient.last_name }} {{ patient.first_name }}
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: {
        patients: Array,
    },
};
</script>
