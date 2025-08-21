<script setup lang="ts">
    import { useRoute } from 'vue-router';
    import { onMounted, ref } from "vue";
    import { useUserStore } from "@/stores/user.js";

    import BaseTemplate from "../../components/BaseTemplate.vue";
    import ShowBorrowedBooksView from "@/views/Users/tabs/ShowBorrowedBooksView.vue";
    import ShowBorrowHistoryView from "@/views/Users/tabs/ShowBorrowHistoryView.vue";
    import ShowBookReservation from "@/views/Users/tabs/ShowBookReservation.vue";

    const route = useRoute();
    const recordID = route.params.id;
    const activeTab = ref('borrowed_books');
    const userStore = useUserStore();

    const header = {
        module: 'Show User',
        icon: 'nav-icon fas fa-solid fa-user',
        bread_crumbs: [
            {name: "Users", is_active: false, path: '/users'},
            {name: "Show User", is_active: true,},
        ]
    }

    const onTabChange = (value) => {
        activeTab.value = value;
    }

    onMounted(() => {
        userStore.getUser({}, recordID);
    });
</script>

<template>
    <base-template :header="header">
        <template v-slot:button>
            <RouterLink :to="'/users'" class="btn btn-danger font-weight-bolder mr-2"> Back </RouterLink>
            <RouterLink :to="'/users/edit/' + recordID" class="btn btn-primary font-weight-bolder"> Edit </RouterLink>
        </template>

        <div class="row">
            <!-- Profile Info -->
            <div class="col-md-3">
                <!-- Profile Name and Pic -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <i class="img-circle far fa-user fa-4x"></i>
                        </div>

                        <h3 class="profile-username text-center">{{ userStore.user.full_name }}</h3>

                        <p class="text-muted text-center">Software Engineer</p>
                    </div>
                </div>

                <!-- Profile Details -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Personal Information</h3>
                    </div>

                    <div class="card-body">
                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong>
                        <p class="text-muted"> {{ userStore.user.full_address }} </p>

                        <hr>

                        <strong><i class="fas fa-calendar mr-1"></i> Birth Date</strong>
                        <p class="text-muted"> {{ userStore.user.date_of_birth }} </p>

                        <hr>

                        <strong><i class="fas fa-phone mr-1"></i> Phone Number</strong>
                        <p class="text-muted"> {{ userStore.user.phone_number }} </p>

                        <hr>

                        <strong><i class="far fa-envelope mr-1"></i> Email</strong>
                        <p class="text-muted"> {{ userStore.user.email }} </p>
                    </div>
                </div>
            </div>

            <!-- Books and Other Info -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item" @click="onTabChange('borrowed_books')">
                                <a class="nav-link active" href="#borrowed_books" data-toggle="tab"> Currently Borrowed </a>
                            </li>

                            <li class="nav-item" @click="onTabChange('borrow_history')">
                                <a class="nav-link" href="#borrow_history" data-toggle="tab"> Borrow History </a>
                            </li>

                            <li class="nav-item" @click="onTabChange('fines_overdue')">
                                <a class="nav-link" href="#fines_overdue" data-toggle="tab"> Fines and Overdue </a>
                            </li>

                            <li class="nav-item" @click="onTabChange('book_reservations')">
                                <a class="nav-link" href="#password_settings" data-toggle="tab"> Book Reservations</a>
                            </li>

                            <li class="nav-item" @click="onTabChange('password_settings')">
                                <a class="nav-link" href="#password_settings" data-toggle="tab"> Password Settings </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content">
                            <div class="active tab-pane" v-if="activeTab === 'borrowed_books'">
                                <show-borrowed-books-view :user_id="recordID"/>
                            </div>

                            <div class="active tab-pane" v-if="activeTab === 'borrow_history'">
                                <show-borrow-history-view :user_id="recordID"/>
                            </div>

                            <div class="active tab-pane" v-else-if="activeTab === 'fines_overdue'">
                                fines_overdue
                            </div>

                            <div class="active tab-pane" v-else-if="activeTab === 'book_reservations'">
                                <show-book-reservation :user_id="recordID"/>
                            </div>

                            <div class="active tab-pane" v-else-if="activeTab === 'password_settings'">
                                password_settings
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