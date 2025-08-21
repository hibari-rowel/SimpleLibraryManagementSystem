<script setup lang="ts">
    import { useRoute } from 'vue-router';
    import BaseTemplate from "../../components/BaseTemplate.vue";

    const route = useRoute();
    const recordID = route.params.id;

    const header = {
        module: 'Show Reservations',
        icon: 'nav-icon fas fa-solid fa-calendar-alt',
        bread_crumbs: [
            // {name: "Dashboard3", is_active: true, path: "path-to-page"}
        ]
    }
</script>

<template>
    <base-template :header="header">
        I'm Show Reservations {{ recordID }}
    </base-template>
</template>

<style scoped>
</style>