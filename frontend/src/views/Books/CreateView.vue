<script setup lang="ts">
    import { onBeforeRouteLeave } from "vue-router";
    import { reactive, onMounted } from 'vue';
    import { useBookStore } from "@/stores/book.js";
    import { useCategoryStore } from "@/stores/category.js";
    import type { BookFormInterface } from '../../core/interface/BookFormInterface.ts';

    import BaseTemplate from "../../components/BaseTemplate.vue";
    import Text from '../../components/fields/Textfield.vue';
    import TextArea from '../../components/fields/TextareaField.vue';
    import Number from '../../components/fields/NumberField.vue';
    import Date from '../../components/fields/DateField.vue';
    import ImageUpload from '../../components/fields/ImageUploadField.vue';
    import Dropdown from '../../components/fields/DropdownField.vue';


    const bookStore = useBookStore();
    const categoryStore = useCategoryStore();

    const formData = reactive <BookFormInterface> ({
        name: '',
        description: '',
        author: '',
        category_id: '',
        publisher: '',
        publication_year: '',
        total_copies: 0,
        shelf_location: '',
        image: null,
    });

    const header = {
        module: 'Create Books',
        icon: 'nav-icon fas fa-solid fa-book',
        bread_crumbs: [
            {name: "Books", is_active: false, path: '/books'},
            {name: "Create Books", is_active: true,},
        ]
    }

    onMounted(() => {
        categoryStore.getCategoryDropdownList();
    });

    const submitForm = (formData: BookFormInterface) => {
        bookStore.createBooks(formData);
    }

    onBeforeRouteLeave(() => {
        bookStore.resetErrors();
    });
</script>

<template>
    <base-template :header="header">
        <template v-slot:button>
            <RouterLink :to="'/books'" class="btn btn-danger font-weight-bolder mr-2"> Cancel </RouterLink>
            <button class="btn btn-primary font-weight-bolder" @click="submitForm(formData)"> Save </button>
        </template>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                        <div class="card border-top border-primary mb-md-0">
                            <div class="card-body box-profile">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <ImageUpload :label="'Book Cover Image'" :previewWidth="'200px'" :is_required="false"
                                                     :previewHeight="'300px'" :errors="bookStore.errors.image"
                                                     v-model="formData.image"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 col-md-9 col-lg-9 col-xl-9">
                        <div class="card card-default border-top border-primary">
                            <div class="card-header">
                                <h3 class="card-title font-weight-bolder"> Book Information </h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <Text :label="'Name'" :placeholder="'Book Name'" :is_required="true"
                                              :is_disabled="false" :errors="bookStore.errors.name"
                                              v-model="formData.name"/>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <Text :label="'Shelf Location'" :placeholder="'Shelf Location'" :is_required="false"
                                              :is_disabled="false" :errors="bookStore.errors.shelf_location"
                                              v-model="formData.shelf_location"/>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <Dropdown :dropdown_id="'category_id'" :label="'Category'" :is_required="true"
                                                  :placeholder="'Category'" :dropdown_list="categoryStore.dropdown"
                                                  :errors="bookStore.errors.category_id" v-model="formData.category_id"/>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <Text :label="'Author'" :placeholder="'Author'" :is_required="false"
                                              :is_disabled="false" :errors="bookStore.errors.author"
                                              v-model="formData.author"/>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <Text :label="'Publisher'" :placeholder="'Publisher'" :is_required="false"
                                              :is_disabled="false" :errors="bookStore.errors.publisher"
                                              v-model="formData.publisher"/>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <Date :label="'Publication Year'" :placeholder="'Publication Year'" :is_required="false"
                                              :is_disabled="false" :errors="bookStore.errors.publication_year"
                                              v-model="formData.publication_year"/>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <Number :label="'Total Copies'" :placeholder="'Total Copies'" :is_required="false"
                                                :min="0" :step="0" :is_disabled="false" :errors="bookStore.errors.total_copies"
                                                v-model="formData.total_copies"/>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <TextArea :label="'Description'" :placeholder="'Description'" :is_required="false"
                                                  :is_disabled="false" :errors="bookStore.errors.description"
                                                  v-model="formData.description"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </base-template>
</template>

<style scoped>
</style>