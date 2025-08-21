<script setup lang="ts">
    import { onBeforeRouteLeave } from 'vue-router';
    import { reactive } from 'vue';
    import { useAuthStore } from "@/stores/auth";
    import _ from 'lodash';

    const authStore = useAuthStore();

    interface LoginForm {
        email: string,
        password: string,
    }

    const formData = reactive <LoginForm> ({
        email: '',
        password: '',
    });

    const submitForm = (formData: LoginForm) => {
        authStore.login(formData);
    }

    onBeforeRouteLeave(() => {
        authStore.resetErrors();
    });
</script>

<template>
    <div class="login-page">
        <div class="login-box">
            <div class="card card-outline card-danger">
                <!-- Logo -->
                <div class="card-header text-center">
                    <!-- TODO: Add Logo -->
                    <a href="../../index2.html" class="h1"><b>Admin</b>LTE</a>
                </div>

                <div class="card-body">
                    <h4 class="login-box-msg">Sign In</h4>

                    <form @submit.prevent="submitForm(formData)">
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
                                <div class="input-group">
                                    <input type="password" class="form-control" :class="{'is-invalid': authStore.errors.password}"
                                           placeholder="Password" v-model="formData.password">

                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <span class="fas fa-lock"></span>
                                        </div>
                                    </div>
                                </div>

                                <small class="text-danger" v-if="authStore.errors.password"
                                       v-html="_.join(authStore.errors.password, '<br>')">
                                </small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-8">
                                <div class="icheck-primary">
                                    <input type="checkbox" id="remember">
                                    <label for="remember">
                                        Remember Me
                                    </label>
                                </div>
                            </div>

                            <div class="col-4">
                                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-footer">
                    <p class="mb-0">
                        <RouterLink :to="'/register'" class="text-center">Register a new Account</RouterLink>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
</style>