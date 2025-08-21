<script setup lang="ts">
    import Navbar from "../components/Navbar.vue";
    import Sidebar from "../components/Sidebar.vue";
    import _ from 'lodash';

    defineProps({
        'header': Object,
    });

</script>

<template>
    <div class="wrapper">
        <Navbar/>

        <Sidebar/>

        <div class="content-wrapper">
            <!-- Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-6 col-sm-6">
                            <h1 class="m-0 mb-2">
                                <span v-if="!_.isEmpty(header.icon)" class="mr-2"> <i :class="header.icon"></i> </span>
                                <span class="font-weight-bolder"> {{ header.module }} </span>
                            </h1>
                        </div>

                        <div class="col-6 col-sm-6 text-right">
                            <slot name="button"></slot>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-12">
                            <ol class="breadcrumb" v-if="!_.isEmpty(header.bread_crumbs)">
                                <li class="breadcrumb-item">
                                    <RouterLink :to="'/dashboard'">
                                        <i class="fas fa-home"></i>
                                    </RouterLink>
                                </li>

                                <li class="breadcrumb-item" v-for="(bread_crumb, index) in header.bread_crumbs"
                                    :class="{active: bread_crumb.is_active}">
                                    <span v-if="_.isEmpty(bread_crumb.path)"> {{ bread_crumb.name }} </span>
                                    <RouterLink v-else :to="bread_crumb.path"> {{ bread_crumb.name }} </RouterLink>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <slot></slot>
                </div>
            </section>
        </div>
    </div>
</template>

<style scoped>
</style>