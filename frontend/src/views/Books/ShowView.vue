<script setup lang="ts">
    import { useRoute } from 'vue-router';
    import { onMounted } from "vue";
    import { useBookStore } from "@/stores/book.js";

    import BaseTemplate from "../../components/BaseTemplate.vue";

    const route = useRoute();
    const recordID = route.params.id;
    const bookStore = useBookStore();

    const header = {
        module: 'Show Books',
        icon: 'nav-icon fas fa-solid fa-book',
        bread_crumbs: [
            {name: "Books", is_active: false, path: '/books'},
            {name: "Show Books", is_active: true,},
        ]
    }

    onMounted(() => {
        bookStore.is_loading = true;

        setTimeout(() => {
            bookStore.getBook({}, recordID)
        }, 500);
    });
</script>

<template>
    <base-template :header="header">
        <template v-slot:button>
            <RouterLink :to="'/books'" class="btn btn-danger font-weight-bolder mr-2"> Back </RouterLink>

            <div class="btn-group">
                <RouterLink :to="'/books/edit/' + recordID" class="btn btn-primary"> Edit </RouterLink>

                <button type="button" class="btn btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown">
                    <span class="sr-only"> Toggle Dropdown </span>
                </button>

                <div class="dropdown-menu dropdown-menu-right" role="menu">
                    <a class="dropdown-item" href="#"> Delete </a>
                    <a class="dropdown-item" href="#"> Borrow Book </a>
                </div>
            </div>
        </template>

        <div class="row">
            <div class="col-md-3">
                <div class="card card-primary">
                    <div class="card-header">
                        <h1 class="card-title font-weight-bolder"> Book Information </h1>
                    </div>

                    <div class="card-body" v-if="bookStore.is_loading">
                        <div class="row text-center">
                            <div class="col-md-5 text-center mb-1">
                                <div class="skeleton skeleton-img"></div>
                            </div>

                            <div class="col-md-7 text-md-left mt-1">
                                <div class="col-12">
                                    <div class="skeleton skeleton-title mb-1"></div>
                                    <div class="skeleton skeleton-subtitle"></div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-12">
                                <p>
                                    <span class="font-weight-bolder"> Category: </span> &nbsp;
                                    <span class="skeleton skeleton-category d-inline-block align-middle mt-0"></span>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <p class="font-weight-bolder mb-0"> Description: </p>
                                <div class="skeleton skeleton-paragraph"></div>
                                <div class="skeleton skeleton-paragraph"></div>
                                <div class="skeleton skeleton-paragraph"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body" v-else>
                        <div class="row text-center">
                            <div class="col-12 col-sm-12 col-md-5 text-center mb-1">
                                <img class="book-image border" :src="bookStore.book.image_url" alt="">
                            </div>

                            <div class="col-12 col-sm-12 col-md-7 text-md-left mt-1">
                                <div class="col-12">
                                    <h4 class="font-weight-bold mb-0">{{ bookStore.book.name }}</h4>
                                    <p class="text-muted"> by: {{ bookStore.book.author }}</p>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-12">
                                <p>
                                    <span class="font-weight-bolder">Category:</span> &nbsp;
                                    <span class="text-muted">{{ bookStore.book.category_name }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <p class="font-weight-bolder mb-0"> Description: </p>
                                <p class="text-muted text-justify scrollable-description-body">
                                    {{ bookStore.book.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <a class="nav-link active" href="#reservation_schedules" data-toggle="tab"> Reservation Schedule </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="#borrow_history" data-toggle="tab"> Borrow History </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content">
                            <div class="active tab-pane" id="reservation_schedules">
                                {{ bookStore.book.total_copies }}
                            </div>

                            <div class="tab-pane" id="borrow_history">
                                borrow_history
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </base-template>
</template>

<style scoped>
    .scrollable-description-body {
        max-height: 300px;
        overflow-y: auto;
        /*scrollbar-width: none;*/
        -ms-overflow-style: none;
    }

    .scrollable-description-body::-webkit-scrollbar {
        display: none;
    }

    .book-image {
        height: 200px;
        width: 150px;
    }

    .text-justify {
        text-align: justify;
    }

    .skeleton {
        background: linear-gradient(90deg, #e0e0e0 25%, #f0f0f0 37%, #e0e0e0 63%);
        background-size: 400% 100%;
        animation: shimmer 1.2s ease-in-out infinite;
        border-radius: 4px;
    }

    .skeleton-img {
        width: 100%;
        height: 200px;
    }

    .skeleton-title {
        height: 20px;
        width: 100%;
    }

    .skeleton-subtitle {
        height: 14px;
        width: 60%;
        margin-top: 10px;
    }

    .skeleton-paragraph {
        height: 14px;
        width: 100%;
        margin-top: 10px;
    }

    .skeleton-category {
        height: 17px;
        width: 50%;
        margin-top: 10px;
    }

    @keyframes shimmer {
        0% {
            background-position: -400px 0;
        }
        100% {
            background-position: 400px 0;
        }
    }
</style>