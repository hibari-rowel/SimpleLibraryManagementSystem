<script setup lang="ts">
    import BaseTemplate from "../../components/BaseTemplate.vue"

    const header = {
        module: 'Fines',
        icon: 'nav-icon fas fa-solid fa-tags',
        bread_crumbs: [
            // {name: "Dashboard3", is_active: true, path: "path-to-page"}
        ]
    }
</script>

<template>
    <base-template :header="header">
        I'm Fines
    </base-template>
</template>

<style scoped>
</style>