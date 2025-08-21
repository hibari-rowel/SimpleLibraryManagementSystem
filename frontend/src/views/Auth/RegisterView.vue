<script setup lang="ts">
    import { onBeforeRouteLeave } from 'vue-router';
    import { reactive } from 'vue';
    import { useAuthStore } from "@/stores/auth";
    import _ from 'lodash';

    const authStore = useAuthStore();

    interface RegisterForm {
        first_name: string,
        middle_name: string,
        last_name: string,
        email: string,
        phone_number: string,
        address: string,
        user_type: string,
        membership_type: string,
        status: string,
        password: string,
        confirm_password: string,
    }

    const formData = reactive <RegisterForm> ({
        first_name: '',
        middle_name: '',
        last_name: '',
        email: '',
        phone_number: '',
        address: '',
        user_type: 'member',
        membership_type: 'regular',
        status: 'active',
        password: '',
        confirm_password: '',
    });

    const submitForm = (formData: RegisterForm) => {
        authStore.register(formData);
    }

    onBeforeRouteLeave(() => {
        authStore.resetErrors();
    });
</script>

<template>
    <!-- TODO: Make this HTTP-Only Cookies -->
    <!-- TODO: Add Loader to button -->
    <!-- TODO: Add Confirmation message upon successful register -->
    <div class="register-page">
        <div class="register-box">
            <div class="card card-outline card-danger">
                <!-- Logo -->
                <div class="card-header text-center">
                    <!-- TODO: Add Logo here -->
                    <a href="../../index2.html" class="h1"><b>Admin</b>LTE</a>
                </div>

                <div class="card-body">
                    <h4 class="login-box-msg">Sign Up</h4>

                    <form @submit.prevent="submitForm(formData)">
                        <div class="row mb-3">
                            <div class="col-4 m-0 p-0">
                                <input type="text" class="form-control" :class="{'is-invalid': authStore.errors.first_name}"
                                       placeholder="First name" v-model="formData.first_name">

                                <small class="text-danger" v-if="authStore.errors.first_name"
                                       v-html="_.join(authStore.errors.first_name, '<br>')">
                                </small>
                            </div>

                            <div class="col-4 m-0 p-0">
                                <input type="text" class="form-control" :class="{'is-invalid': authStore.errors.middle_name}"
                                       placeholder="Middle name" v-model="formData.middle_name">

                                <small class="text-danger" v-if="authStore.errors.middle_name"
                                       v-html="_.join(authStore.errors.middle_name, '<br>')">
                                </small>
                            </div>

                            <div class="col-4 m-0 p-0">
                                <div class="input-group">
                                    <input type="text" class="form-control" :class="{'is-invalid': authStore.errors.last_name}"
                                           placeholder="Last name" v-model="formData.last_name">

                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-user"></span>
                                        </div>
                                    </div>
                                </div>

                                <small class="text-danger" v-if="authStore.errors.last_name"
                                       v-html="_.join(authStore.errors.last_name, '<br>')">
                                </small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 m-0 p-0">
                                <div class="input-group">
                                    <input type="email" class="form-control" :class="{'is-invalid': authStore.errors.email}"
                                           placeholder="Email" v-model="formData.email">

                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-envelope"></span>
                                        </div>
                                    </div>
                                </div>

                                <small class="text-danger" v-if="authStore.errors.email"
                                       v-html="_.join(authStore.errors.email, '<br>')">
                                </small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 m-0 p-0">
                                <!-- TODO: Add Input Masking -->
                                <div class="input-group">
                                    <input type="text" class="form-control" :class="{'is-invalid': authStore.errors.phone_number}"
                                           placeholder="Phone Number" v-model="formData.phone_number">

                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-phone"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <small class="text-danger" v-if="authStore.errors.phone_number"
                                   v-html="_.join(authStore.errors.phone_number, '<br>')">
                            </small>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 m-0 p-0">
                                <div class="input-group">
                                    <input type="text" class="form-control" :class="{'is-invalid': authStore.errors.address}"
                                           placeholder="Address" v-model="formData.address">

                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-map-marker-alt"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <small class="text-danger" v-if="authStore.errors.address"
                                   v-html="_.join(authStore.errors.address, '<br>')">
                            </small>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 m-0 p-0">
                                <div class="input-group mb-1">
                                    <input type="password" class="form-control" :class="{'is-invalid': authStore.errors.password}"
                                           placeholder="Password" v-model="formData.password">

                                    <div class="input-group-append">
                                        <!-- TODO: Add show password function -->
                                        <div class="input-group-text">
                                            <span class="fas fa-lock"></span>
                                        </div>
                                    </div>
                                </div>

                                <!-- TODO: Make me dynamic -->
<!--                                <div class="progress progress-xs" v-if="!_.isEmpty(formData.password)">-->
<!--                                    <div class="progress-bar bg-danger" role="progressbar"-->
<!--                                         aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 40%">-->
<!--                                    </div>-->
<!--                                </div>-->

                                <small class="text-danger" v-if="authStore.errors.password"
                                       v-html="_.join(authStore.errors.password, '<br>')">
                                </small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 m-0 p-0">
                                <div class="input-group">
                                    <input type="password" class="form-control" :class="{'is-invalid': authStore.errors.confirm_password}"
                                           placeholder="Retype password" v-model="formData.confirm_password">

                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-lock"></span>
                                        </div>
                                    </div>
                                </div>

                                <small class="text-danger" v-if="authStore.errors.confirm_password"
                                       v-html="_.join(authStore.errors.confirm_password, '<br>')">
                                </small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Register</button>
                    </form>
                </div>

                <div class="card-footer">
                    <RouterLink :to="'/'" class="text-center">I already have an Account</RouterLink>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
</style>