<script setup lang="ts">
    import { useRoute } from 'vue-router';
    import BaseTemplate from "../../components/BaseTemplate.vue";

    const route = useRoute();
    const recordID = route.params.id;

    const header = {
        module: 'Show Borrowings',
        icon: 'nav-icon fas fa-solid fa-book-open',
        bread_crumbs: [
            {name: "Borrowings", is_active: false, path: '/borrowings'},
            {name: "Show Borrowings", is_active: true,},
        ]
    }
</script>

<template>
    <base-template :header="header">
        <template v-slot:button>
            <RouterLink :to="'/borrowings'" class="btn btn-danger font-weight-bolder mr-2"> Back </RouterLink>
            <RouterLink :to="'/borrowings/edit/' + recordID" class="btn btn-primary"> Edit </RouterLink>
        </template>

    </base-template>
</template>

<style scoped>
</style>