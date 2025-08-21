<script setup lang="ts">
    import BaseTemplate from "../../components/BaseTemplate.vue"

    const header = {
        module: 'Dashboard',
        icon: 'nav-icon fas fa-tachometer-alt',
        bread_crumbs: [
            // {name: "Dashboard3", is_active: true, path: "path-to-page"}
        ]
    }
</script>

<template>
    <base-template :header="header">
        I'm dashboard
    </base-template>
</template>

<style scoped>
</style>