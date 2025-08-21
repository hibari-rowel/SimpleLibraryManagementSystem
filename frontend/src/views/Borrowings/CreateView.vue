<script setup lang="ts">
    import {reactive, ref, onMounted, watch} from "vue";
    import _ from 'lodash';
    import { useBookStore } from "@/stores/book.js";
    import { useBorrowingStore } from "@/stores/borrowing.js";
    import { useUserStore } from "@/stores/user.js";
    import type { BorrowingFormInterface }  from '../../core/interface/BorrowingFormInterface.ts';

    import BaseTemplate from "../../components/BaseTemplate.vue"
    import Dropdown from '../../components/fields/DropdownField.vue';
    import Text from '../../components/fields/Textfield.vue';
    import TextArea from '../../components/fields/TextareaField.vue';
    import Date from '../../components/fields/DateField.vue';

    import BorrowingStatusDropdownList from "../../core/dropdowns/BorrowingStatusDropdownList.js";

    const borrowingStore = useBorrowingStore();
    const bookStore = useBookStore();
    const userStore = useUserStore();
    const defaultPreviewImg = '/defaultimage.png';
    const previewImg = ref(defaultPreviewImg);

    const header = {
        module: 'Create Borrowings',
        icon: 'nav-icon fas fa-solid fa-book-open',
        bread_crumbs: [
            {name: "Borrowings", is_active: false, path: '/borrowings'},
            {name: "Create Borrowings", is_active: true,},
        ]
    }

    const formData = reactive <BorrowingFormInterface> ({
        user_id: '',
        book_id: '',
        borrow_date: '',
        due_date: '',
        return_date: '',
        status: '',
        description: '',
    });

    const submitForm = (formData: BorrowingFormInterface) => {
        borrowingStore.createBorrowing(formData);
    }

    const handleBookChange = (selectedId: string) => {
        const selectedBook = bookStore.dropdown.find(book => book.name === selectedId);

        previewImg.value = (!_.isEmpty(selectedBook) && !_.isEmpty(selectedBook.image_url))
            ? selectedBook.image_url
            : defaultPreviewImg;
    }

    watch(
        () => formData.book_id,
        (newVal) => {
            handleBookChange(newVal);
        }
    );

    onMounted(() => {
        bookStore.getCategoryDropdownList();
        userStore.getCategoryDropdownList();
    });
</script>

<template>
    <base-template :header="header">
        <template v-slot:button>
            <RouterLink :to="'/borrowings'" class="btn btn-danger font-weight-bolder mr-2"> Cancel </RouterLink>
            <button class="btn btn-primary font-weight-bolder" @click="submitForm(formData)"> Save </button>
        </template>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-3">
                        <div class="card border-top border-primary mb-md-0">
                            <div class="card-body box-profile">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="my-3 text-center">
                                            <img :src="previewImg" alt="default-book-image"
                                                 :style="{width: '200px', height: '300px', objectFit: 'fill'}">
                                        </div>

                                        <Dropdown :dropdown_id="'book_id'" :label="'Select Book'" :is_required="true"
                                                  :placeholder="'Select Book'" :dropdown_list="bookStore.dropdown"
                                                  :errors="borrowingStore.errors.book_id" v-model="formData.book_id"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-9">
                        <div class="card border-top border-primary mb-md-0">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <Dropdown :dropdown_id="'user_id'" :label="'Select User'" :is_required="true"
                                                  :placeholder="'Select User'" :dropdown_list="userStore.dropdown"
                                                  :errors="borrowingStore.errors.user_id" v-model="formData.user_id"/>
                                    </div>

                                    <div class="col-6">
                                        <Dropdown :dropdown_id="'status'" :label="'Select Status'" :is_required="true"
                                                  :placeholder="'Select Status'" :dropdown_list="BorrowingStatusDropdownList"
                                                  :errors="borrowingStore.errors.status" v-model="formData.status"/>
                                    </div>

                                    <div class="col-6">
                                        <Date :label="'Date Borrowed'" :placeholder="'Date Borrowed'" :is_required="true"
                                              :is_disabled="false" :errors="borrowingStore.errors.borrow_date"
                                              v-model="formData.borrow_date"/>
                                    </div>

                                    <div class="col-6">
                                        <Date :label="'Date Due'" :placeholder="'Date Due'" :is_required="true"
                                              :is_disabled="false" :errors="borrowingStore.errors.due_date"
                                              v-model="formData.due_date"/>
                                    </div>

                                    <div class="col-6">
                                        <Date :label="'Date Returned'" :placeholder="'Date Returned'" :is_required="false"
                                              :is_disabled="false" :errors="borrowingStore.errors.return_date"
                                              v-model="formData.return_date"/>
                                    </div>

                                    <div class="col-12">
                                        <TextArea :label="'Description'" :placeholder="'Description'" :is_required="false"
                                              :is_disabled="false" :errors="borrowingStore.errors.description"
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
    .image-preview {
        height: 300px;
        width: 200px;
        objectFit: 'fill'
    }
</style>