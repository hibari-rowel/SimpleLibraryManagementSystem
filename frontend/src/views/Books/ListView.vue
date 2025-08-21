<script setup lang="ts">
    import { reactive, onMounted, onUnmounted, watch, ref } from 'vue';
    import { useBookStore } from "@/stores/book.js";
    import { useCategoryStore } from "@/stores/category.js";
    import _ from 'lodash';

    import BaseTemplate from "../../components/BaseTemplate.vue";

    import BookSortByDropdownList from "../../core/dropdowns/BookSortByDropdownList.js";
    import OrderByDropdownList from "../../core/dropdowns/OrderByDropdownList.js";

    const bookStore = useBookStore();
    const categoryStore = useCategoryStore();
    const isFilterShown = ref(false);
    const isSmallScreen = ref(window.innerWidth < 760);
    let bookFilterDebounceTimeout;
    const bookFilter = reactive({
        name: '',
        category_id: '',
        order_by: 'name',
        order: 'asc',
    });

    const header = {
        module: 'Books',
        icon: 'nav-icon fas fa-solid fa-book',
        bread_crumbs: [
            {name: "Books", is_active: true,}
        ]
    }

    const bookFilterSearch = () => {
        clearTimeout(bookFilterDebounceTimeout);

        bookFilterDebounceTimeout = setTimeout(() => {
            bookStore.getList(bookFilter);
        }, 800)
    }

    const handleResize = () => {
        isSmallScreen.value = window.innerWidth < 760;
    };

    watch(
        () => [bookFilter.name],
        bookFilterSearch
    );

    watch(
        () => [bookFilter.category_id, bookFilter.order_by, bookFilter.order],
        () => {
                bookStore.getList(bookFilter);
            }
    );

    onMounted(() => {
        categoryStore.getCategoryDropdownList();
        bookStore.getList(bookFilter);
        window.addEventListener('resize', handleResize);
    });

    onUnmounted(() => {
        window.removeEventListener('resize', handleResize);
    });
</script>

<template>
    <base-template :header="header">
        <template v-slot:button>
            <RouterLink :to="'/books/create'" class="btn font-weight-bolder btn-primary">
                Create
            </RouterLink>
        </template>

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-4 mr-0 mb-2 mb-md-0 px-0 px-sm-0">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search Books" aria-label="Search Books" v-model="bookFilter.name">

                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 col-md-1 m-0 text-left text-sm-right text-md-right"></div>

                    <div class="col-12 col-sm-12 col-md-6 ml-auto px-0 px-sm-0">
                        <div class="row">
                            <div class="col-12 col-sm-11 col-md-11 pr-sm-0 mb-2" style="overflow: hidden;">
                                <transition :name="isSmallScreen ? 'slide-down' : 'slide-left'">
                                    <div v-if="isFilterShown" class="row">
                                        <div class="col-12 col-sm-5 col-md-5 mb-2 mb-md-0">
                                            <div class="input-group">
                                                <div class="input-group-append">
                                                    <h5 class="input-group-text"> Filter By: </h5>
                                                </div>

                                                <select class="custom-select form-control-border" id="exampleSelectBorder" v-model="bookFilter.category_id">
                                                    <option value="" disabled selected hidden> Category </option>
                                                    <option v-for="(dropdown, index) in categoryStore.dropdown" :key="index" :value="dropdown.name">
                                                        {{ dropdown.label }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12 col-sm-7 col-md-7">
                                            <div class="input-group">
                                                <div class="input-group-append">
                                                    <h5 class="input-group-text"> Sort By: </h5>
                                                </div>

                                                <select class="custom-select form-control-border mr-1" v-model="bookFilter.order_by">
                                                    <option v-for="(dropdown, index) in BookSortByDropdownList" :key="index" :value="dropdown.name">
                                                        {{ dropdown.label }}
                                                    </option>
                                                </select>

                                                <select class="custom-select form-control-border" v-model="bookFilter.order">
                                                    <option v-for="(dropdown, index) in OrderByDropdownList" :key="index" :value="dropdown.name">
                                                        {{ dropdown.label }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </transition>
                            </div>

                            <div class="col-12 col-sm-1 col-md-1 ml-0 pl-sm-0 mb-2 text-left text-sm-right text-md-right">
                                <button class="btn btn-default" @click="isFilterShown = !isFilterShown">
                                    <i class="fas fa-filter"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body scrollable-body" v-if="bookStore.is_loading">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-4 col-xl-4" v-for="n in 6" :key="n">
                        <div class="card hover-shadow transition-shadow">
                            <div class="card-body book-card-body">
                                <div class="row">
                                    <!-- Skeleton Image -->
                                    <div class="col-5 d-flex align-items-center justify-content-center">
                                        <div class="skeleton skeleton-img"></div>
                                    </div>

                                    <!-- Skeleton Text -->
                                    <div class="col-7">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="skeleton skeleton-title mb-2"></div>
                                                <div class="skeleton skeleton-subtitle"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body scrollable-body" v-else>
                <div v-if="_.isEmpty(bookStore.book_list)" class="row my-2">
                    <div class="col-12 text-center">
                        <h4 class="font-weight-bolder"> No Book Records Found </h4>
                    </div>
                </div>

                <div v-else class="row">
                    <div v-for="(book, index) in bookStore.book_list" class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                        <RouterLink :to="'/books/show/' + book.id" class="text-decoration-none">
                            <div class="card hover-shadow transition-shadow">
                                <div class="card-body book-card-body">
                                    <div class="row">
                                        <div class="col-5 col-sm-4 col-md-4 text-center">
                                            <img class="book-image border" :src="book.image_url" alt="">
                                        </div>
                                        <div class="col-7 col-sm-8 col-md-8">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h4 class="font-weight-bold mb-0">{{ book.name }}</h4>
                                                    <p class="text-muted">{{ book.author }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </RouterLink>
                    </div>
                </div>
            </div>
        </div>
    </base-template>
</template>

<style scoped>
    .book-image {
        height: 200px;
        width: 100%;
    }

    .scrollable-body {
        max-height: 600px;
        overflow-y: auto;
    }

    .book-card-body {
        height: 240px;
        overflow-y: hidden;
    }

    .hover-shadow:hover {
        box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.15);
    }

    .transition-shadow {
        transition: box-shadow 0.1s ease-in-out;
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

    @keyframes shimmer {
        0% {
            background-position: -400px 0;
        }
        100% {
            background-position: 400px 0;
        }
    }

    .slide-left-enter-active,
    .slide-left-leave-active {
        transition: all 0.4s ease;
    }

    .slide-left-enter-from {
        transform: translateX(100%);
        opacity: 0;
    }

    .slide-left-enter-to {
        transform: translateX(0);
        opacity: 1;
    }

    .slide-left-leave-from {
        transform: translateX(0);
        opacity: 1;
    }

    .slide-left-leave-to {
        transform: translateX(100%);
        opacity: 0;
    }

    .slide-down-enter-active,
    .slide-down-leave-active {
        transition: all 0.4s ease;
    }

    .slide-down-enter-from {
        transform: translateY(-100%);
        opacity: 0;
    }

    .slide-down-enter-to {
        transform: translateY(0);
        opacity: 1;
    }

    .slide-down-leave-from {
        transform: translateY(0);
        opacity: 1;
    }

    .slide-down-leave-to {
        transform: translateY(-100%);
        opacity: 0;
    }
</style>