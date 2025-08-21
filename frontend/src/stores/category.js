import { defineStore } from "pinia";
import _ from 'lodash';

export const useCategoryStore = defineStore('category-store', {
    state: () => ({
        category: {},
        dt_options: {},
        errors: {},
        dropdown: [{name: '', label: ''}],
    }),

    actions: {
        async getDTList(dtParams) {
            const response = await fetch('/api/categories/get_list', {
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

        async getCategory(params, id) {
            this.category = {};

            const response = await fetch(`/api/categories/get/${id}`, {
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    authorization: `Bearer ${localStorage.getItem("token")}`
                },
            });

            const jsonResponse = await response.json();

            if (response.ok) {
                this.category = jsonResponse.category;
            }
        },

        async createCategory(data) {
            this.resetErrors();

            const response = await fetch('/api/categories/create', {
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
                this.router.push('/categories');
            }
        },

        async updateCategory(data, id) {
            this.resetErrors();

            const response = await fetch(`/api/categories/update/${id}`, {
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
                this.router.push(`/categories/show/${id}`);
            }
        },

        async deleteCategory(id) {
            this.resetErrors();

            try {
                const response = await fetch(`/api/categories/destroy/${id}`, {
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

            const response = await fetch(`/api/categories/get_dropdown_list`, {
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
    }
});