<script setup lang="ts">
    import { useRoute } from 'vue-router';
    import {onMounted, reactive, ref, watch} from "vue";
    import _ from 'lodash';
    import { useReservationStore } from "@/stores/reservation.js";
    import { useBookStore } from "@/stores/book.js";
    import { useUserStore } from "@/stores/user.js";
    import type { ReservationFormInterface }  from '../../core/interface/ReservationFormInterface.ts';

    import BaseTemplate from "../../components/BaseTemplate.vue";
    import Dropdown from '../../components/fields/DropdownField.vue';
    import Date from '../../components/fields/DateField.vue';
    import TextArea from '../../components/fields/TextareaField.vue';
    import ReservationStatusDropdownList from "../../core/dropdowns/ReservationStatusDropdownList.js";

    const route = useRoute();
    const recordID = route.params.id;
    const reservationStore = useReservationStore();
    const bookStore = useBookStore();
    const userStore = useUserStore();
    const defaultPreviewImg = '/defaultimage.png';
    const previewImg = ref(defaultPreviewImg);
    const header = {
        module: 'Edit Reservations',
        icon: 'nav-icon fas fa-solid fa-calendar-alt',
        bread_crumbs: [
            {name: "Reservations", is_active: false, path: '/reservations'},
            {name: "Edit Reservations", is_active: true,},
        ]
    }

    const formData = reactive <ReservationFormInterface> ({
        user_id: '',
        book_id: '',
        status: '',
        reservation_date: '',
        description: '',
    });

    const handleBookChange = (selectedId: string) => {
        const selectedBook = bookStore.dropdown.find(book => book.name === selectedId);

        previewImg.value = (!_.isEmpty(selectedBook) && !_.isEmpty(selectedBook.image_url))
            ? selectedBook.image_url
            : defaultPreviewImg;
    }

    const submitForm = (formData: ReservationFormInterface) => {
        reservationStore.updateReservation(formData, recordID);
    }

    watch(
        () => reservationStore.reservation,
        (newReservation) => {
            if (newReservation) {
                Object.assign(formData, {
                    user_id: newReservation.user_id || '',
                    book_id: newReservation.book_id || '',
                    status: newReservation.status || '',
                    reservation_date: newReservation.reservation_date || '',
                    description: newReservation.description || '',
                });
            }
        }
    );

    watch(
        () => formData.book_id,
        (newVal) => {
            handleBookChange(newVal);
        }
    );

    onMounted(() => {
        bookStore.getCategoryDropdownList();
        userStore.getCategoryDropdownList();
        reservationStore.getReservation({}, recordID);
    });
</script>

<template>
    <base-template :header="header">
        <template v-slot:button>
            <RouterLink :to="'/reservations'" class="btn btn-danger font-weight-bolder mr-2"> Cancel </RouterLink>
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
                                                  :errors="reservationStore.errors.book_id" v-model="formData.book_id"/>
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
                                                  :errors="reservationStore.errors.user_id" v-model="formData.user_id"/>
                                    </div>

                                    <div class="col-6">
                                        <Dropdown :dropdown_id="'status'" :label="'Select Status'" :is_required="true"
                                                  :placeholder="'Select Status'" :dropdown_list="ReservationStatusDropdownList"
                                                  :errors="reservationStore.errors.status" v-model="formData.status"/>
                                    </div>

                                    <div class="col-6">
                                        <Date :label="'Reservation Date'" :placeholder="'Reservation Date'" :is_required="true"
                                              :is_disabled="false" :errors="reservationStore.errors.reservation_date"
                                              v-model="formData.reservation_date"/>
                                    </div>

                                    <div class="col-12">
                                        <TextArea :label="'Description'" :placeholder="'Description'" :is_required="false"
                                                  :is_disabled="false" :errors="reservationStore.errors.description"
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