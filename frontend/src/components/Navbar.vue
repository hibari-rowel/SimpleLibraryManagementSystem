<script setup>
    import { useAuthStore } from "@/stores/auth";

    const authStore = useAuthStore();

    const logout = () => {
        authStore.logout();
    }
</script>

<template>
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
                <!-- TODO: Add Profile pic here -->
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">
                        <!-- TODO: Add Profile Info Here -->
                        Menu
                    </span>

                    <div class="dropdown-divider"></div>

                    <RouterLink :to="'/users/show/' + authStore.user.id" class="dropdown-item">
                        <span class="col-1"> <i class="fas fa-user"></i> </span>
                        <span class="col-11"> Profile </span>
                    </RouterLink>

                    <div class="dropdown-divider"></div>

                    <a href="#" class="dropdown-item" @click="logout">
                        <span class="col-1"> <i class="fas fa-sign-out-alt"></i> </span>
                        <span class="col-11"> Logout </span>
                    </a>
                </div>
            </li>
        </ul>
    </nav>
</template>

<style scoped>
</style>