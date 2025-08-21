import { defineStore } from "pinia";
import _ from 'lodash';

export const useUserStore = defineStore('user-store', {
    state: () => ({
        user: {},
        dt_options: {},
        errors: {},
        dropdown: [{name: '', label: ''}],
    }),

    actions: {
        async getDTList(dtParams) {
            const response = await fetch('/api/users/get_list', {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    authorization: `Bearer ${localStorage.getItem("token")}`
                },
                body: JSON.stringify(dtParams),
            });

            const jsonResponse = await response.json();

            if (response.ok) {
                this.dt_options = jsonResponse;
            }
        },

        async getUser(params, id) {
            this.user = {};

            const response = await fetch(`/api/users/get/${id}`, {
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    authorization: `Bearer ${localStorage.getItem("token")}`
                },
            });

            const jsonResponse = await response.json();

            if (response.ok) {
                this.user = jsonResponse.user;
            }
        },

        async createUser(data) {
            this.resetErrors();

            const response = await fetch('/api/users/create', {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    authorization: `Bearer ${localStorage.getItem("token")}`
                },
                body: JSON.stringify(data),
            });

            const jsonResponse = await response.json();

            if (!_.isEmpty(jsonResponse.errors)) {
                this.errors = jsonResponse.errors;
            } else {
                this.resetErrors();
                this.router.push('/users');
            }
        },

        async updateUser(data, id) {
            this.resetErrors();

            const response = await fetch(`/api/users/update/${id}`, {
                method: "PUT",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    authorization: `Bearer ${localStorage.getItem("token")}`
                },
                body: JSON.stringify(data),
            });

            const jsonResponse = await response.json();

            if (!_.isEmpty(jsonResponse.errors)) {
                this.errors = jsonResponse.errors;
            } else {
                this.resetErrors();
                this.router.push(`/users/show/${id}`);
            }
        },

        async deleteUser(id) {
            this.resetErrors();

            try {
                const response = await fetch(`/api/users/destroy/${id}`, {
                    method: "DELETE",
                    headers: {
                        Accept: "application/json",
                        "Content-Type": "application/json",
                        authorization: `Bearer ${localStorage.getItem("token")}`
                    },
                });

                const jsonResponse = await response.json();

                if (!response.ok) {
                    throw new Error(jsonResponse.message || 'An error occurred while deleting the user.');
                }
            } catch (err) {
                throw err;
            }
        },

        async getCategoryDropdownList() {
            this.dropdown = [];

            const response = await fetch(`/api/users/get_dropdown_list`, {
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    authorization: `Bearer ${localStorage.getItem("token")}`
                },
            });

            const jsonResponse = await response.json();

            if (response.ok) {
                this.dropdown = _.concat(this.dropdown, jsonResponse.dropdown_list);
            }
        },

        resetErrors() {
            this.errors = {};
        },
    },
});