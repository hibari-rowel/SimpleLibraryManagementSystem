<script setup lang="ts">
    import { onBeforeRouteLeave } from "vue-router";
    import { reactive } from 'vue';
    import { useUserStore } from "@/stores/user.js";
    import type { UserFormInterface } from '../../core/interface/UserFormInterface.ts';

    import UserTypeDropdownList from "../../core/dropdowns/UserTypeDropdownList.js";
    import MembershipTypeDropdownList from "../../core/dropdowns/MembershipTypeDropdownList.js";
    import UserStatusDropdownList from "../../core/dropdowns/UserStatusDropdownList.js";

    import BaseTemplate from "../../components/BaseTemplate.vue";
    import Text from '../../components/fields/Textfield.vue';
    import Password from '../../components/fields/PasswordField.vue';
    import Email from '../../components/fields/EmailField.vue';
    import Date from '../../components/fields/DateField.vue';
    import Dropdown from '../../components/fields/DropdownField.vue';
    import PhoneNumber from '../../components/fields/PhoneNumberField.vue';

    const userStore = useUserStore();

    const formData = reactive <UserFormInterface> ({
        first_name: '',
        middle_name: '',
        last_name: '',
        email: '',
        phone_number: '',
        street_address: '',
        city_address: '',
        postal_code_address: '',
        state_address: '',
        country_address: '',
        date_of_birth: '',
        user_type: '',
        membership_type: '',
        status: '',
        password: '',
        confirm_password: '',
    });

    const header = {
        module: 'Create User',
        icon: 'nav-icon fas fa-solid fa-user',
        bread_crumbs: [
            {name: "Users", is_active: false, path: '/users'},
            {name: "Create User", is_active: true,},
        ]
    }

    const submitForm = (formData: UserFormInterface) => {
        userStore.createUser(formData);
    }

    onBeforeRouteLeave(() => {
        userStore.resetErrors();
    });
</script>

<template>
    <base-template :header="header">
        <template v-slot:button>
            <RouterLink :to="'/users'" class="btn btn-danger font-weight-bolder mr-2"> Cancel </RouterLink>
            <button class="btn btn-primary font-weight-bolder" @click="submitForm(formData)"> Save </button>
        </template>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                        <div class="card border-top border-primary mb-md-0">
                            <div class="card-body box-profile">
                                <div class="text-center mb-4">
                                    <i class="far fa-user fa-4x"></i>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                        <Text :label="'First Name'" :placeholder="'First Name'" :is_required="true"
                                              :is_disabled="false" :errors="userStore.errors.first_name"
                                              v-model="formData.first_name"/>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                        <Text :label="'Middle Name'" :placeholder="'Middle Name'" :is_required="true"
                                              :is_disabled="false" :errors="userStore.errors.middle_name"
                                              v-model="formData.middle_name"/>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                        <Text :label="'Last Name'" :placeholder="'Last Name'" :is_required="true"
                                              :is_disabled="false" :errors="userStore.errors.last_name"
                                              v-model="formData.last_name"/>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <Date :label="'Date of Birth'" :placeholder="'Date of Birth'" :is_required="true"
                                              :is_disabled="false" :errors="userStore.errors.date_of_birth"
                                              v-model="formData.date_of_birth"/>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <Email :label="'Email Address'" :placeholder="'Email Address'" :is_required="true"
                                               :errors="userStore.errors.email" v-model="formData.email"/>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                        <PhoneNumber :phone_id="'phone_number'" :label="'Phone Number'" :is_required="true"
                                                     :placeholder="'Phone Number'" :errors="userStore.errors.phone_number"
                                                     v-model="formData.phone_number"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
                        <div class="card card-default border-top border-primary">
                            <div class="card-header">
                                <h3 class="card-title font-weight-bolder">Address</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                        <Text :label="'Street'" :placeholder="'Street'" :is_required="true"
                                              :is_disabled="false" :errors="userStore.errors.street_address"
                                              v-model="formData.street_address"/>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                        <Text :label="'City'" :placeholder="'City'" :is_required="true"
                                              :is_disabled="false" :errors="userStore.errors.city_address"
                                              v-model="formData.city_address"/>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                        <Text :label="'Postal Code'" :placeholder="'Postal Code'" :is_required="true"
                                              :is_disabled="false" :errors="userStore.errors.postal_code_address"
                                              v-model="formData.postal_code_address"/>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                        <Text :label="'State'" :placeholder="'State'" :is_required="true"
                                              :is_disabled="false" :errors="userStore.errors.state_address"
                                              v-model="formData.state_address"/>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                                        <Text :label="'Country'" :placeholder="'Country'" :is_required="true"
                                              :is_disabled="false" :errors="userStore.errors.country_address"
                                              v-model="formData.country_address"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-default border-top border-primary">
                            <div class="card-header">
                                <h3 class="card-title font-weight-bolder">Account Status</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-6">
                                        <Dropdown :dropdown_id="'user_type'" :label="'User Type'" :is_required="true"
                                                  :placeholder="'User Type'" :dropdown_list="UserTypeDropdownList"
                                                  :errors="userStore.errors.user_type" v-model="formData.user_type"/>
                                    </div>

                                    <div class="col-6">
                                        <Dropdown :dropdown_id="'membership_type'" :label="'Membership Type'" :is_required="true"
                                                  :placeholder="'Membership Type'" :dropdown_list="MembershipTypeDropdownList"
                                                  :errors="userStore.errors.membership_type" v-model="formData.membership_type"/>
                                    </div>

                                    <div class="col-6">
                                        <Dropdown :dropdown_id="'status'" :label="'Active Status'" :is_required="true"
                                                  :placeholder="'Active Status'" :dropdown_list="UserStatusDropdownList"
                                                  :errors="userStore.errors.status" v-model="formData.status"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-default border-top border-primary">
                            <div class="card-header">
                                <h3 class="card-title font-weight-bolder">Password</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <Password :label="'Password'" :placeholder="'Password'" :is_required="true"
                                                  v-model="formData.password"/>
                                    </div>

                                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                        <Password :label="'Confirm Password'" :placeholder="'Confirm Password'" :is_required="true"
                                                  v-model="formData.confirm_password"/>
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