<script setup>
    import { reactive, ref } from "vue";
    import SideNavLinks from "../core/sidenavs/SideNavLinks.js";

    const sideNavLinks = ref(SideNavLinks);
</script>

<template>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <RouterLink :to="'/dashboard'" class="brand-link">
            <img src="/src/vendor/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">AdminLTE 3</span>
        </RouterLink>

        <!-- Sidebar -->
        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- TODO: Add Show/Hide function base on user type -->
                    <li class="nav-item" v-for="(side_nav_link, index) in sideNavLinks">
                        <RouterLink :to="side_nav_link.path" class="nav-link" activeClass="active">
                            <i :class="side_nav_link.icon_class"></i> <p> {{ side_nav_link.name }} </p>
                        </RouterLink>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
</template>

<style scoped>
</style>

