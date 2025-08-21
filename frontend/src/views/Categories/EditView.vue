<script setup lang="ts">
    import { onBeforeRouteLeave, useRoute } from 'vue-router';
    import {onMounted, reactive, watch} from "vue";
    import { useCategoryStore } from "@/stores/category.js";
    import type { CategoryFormInterface }  from '../../core/interface/CategoryFormInterface.ts';

    import BaseTemplate from "../../components/BaseTemplate.vue";
    import Text from '../../components/fields/Textfield.vue';
    import TextArea from '../../components/fields/TextareaField.vue';

    const route = useRoute();
    const recordID = route.params.id;

    const categoryStore = useCategoryStore();

    const formData = reactive <CategoryFormInterface> ({
        name: '',
        description: '',
    });

    const header = {
        module: 'Edit Category',
        icon: 'nav-icon fas fa-solid fa-layer-group',
        bread_crumbs: [
            {name: "Categories", is_active: false, path: '/categories'},
            {name: "Edit Category", is_active: true,},
        ]
    }

    onMounted(() => {
        categoryStore.getCategory({}, recordID);
    });

    watch(
        () => categoryStore.category,
        (newCategory) => {
            if (newCategory) {
                Object.assign(formData, {
                    name: newCategory.name || '',
                    description: newCategory.description || '',
                });
            }
        },
        { immediate: true }
    );

    const submitForm = (formData: CategoryFormInterface) => {
        categoryStore.updateCategory(formData, recordID);
    }

    onBeforeRouteLeave(() => {
        categoryStore.resetErrors();
    });
</script>

<template>
    <base-template :header="header">
        <template v-slot:button>
            <RouterLink :to="'/categories/show/' + recordID" class="btn btn-danger font-weight-bolder mr-2"> Cancel </RouterLink>
            <button class="btn btn-primary font-weight-bolder" @click="submitForm(formData)"> Save </button>
        </template>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <Text :label="'Name'" :placeholder="'Name'" :is_required="true" :is_disabled="false"
                              :errors="categoryStore.errors.name" v-model="formData.name"/>
                    </div>

                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <TextArea :label="'Description'" :placeholder="'Description'" :is_required="false" :is_disabled="false"
                                  :errors="categoryStore.errors.description" v-model="formData.description"/>
                    </div>
                </div>
            </div>
        </div>
    </base-template>
</template>

<style scoped>
</style>