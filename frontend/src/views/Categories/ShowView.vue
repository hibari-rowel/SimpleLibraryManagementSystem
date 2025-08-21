<script setup lang="ts">
    import {onMounted, reactive, watch} from "vue";
    import {onBeforeRouteLeave, useRoute} from 'vue-router';
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
        module: 'Show Category',
        icon: 'nav-icon fas fa-solid fa-layer-group',
        bread_crumbs: [
            {name: "Categories", is_active: false, path: '/categories'},
            {name: "Show Category", is_active: true,},
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

    onBeforeRouteLeave(() => {
        categoryStore.resetErrors();
    });
</script>

<template>
    <base-template :header="header">
        <template v-slot:button>
            <RouterLink :to="'/categories'" class="btn btn-danger font-weight-bolder mr-2"> Back </RouterLink>
            <RouterLink :to="'/categories/edit/' + recordID" class="btn btn-primary font-weight-bolder"> Edit </RouterLink>
        </template>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <Text :label="'Name'" :placeholder="'Name'" :is_required="true" :is_disabled="true"
                              :errors="categoryStore.errors.name" v-model="formData.name"/>
                    </div>

                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <TextArea :label="'Description'" :placeholder="'Description'" :is_required="false" :is_disabled="true"
                                  :errors="categoryStore.errors.description" v-model="formData.description"/>
                    </div>
                </div>
            </div>
        </div>
    </base-template>
</template>

<style scoped>
</style>