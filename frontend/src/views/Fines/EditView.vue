<script setup lang="ts">
    import { useRoute } from 'vue-router';
    import BaseTemplate from "../../components/BaseTemplate.vue";

    const route = useRoute();
    const recordID = route.params.id;

    const header = {
        module: 'Edit Fines',
        icon: 'nav-icon fas fa-solid fa-tags',
        bread_crumbs: [
            // {name: "Dashboard3", is_active: true, path: "path-to-page"}
        ]
    }
</script>

<template>
    <base-template :header="header">
        I'm Edit Fines
    </base-template>
</template>

<style scoped>
</style>