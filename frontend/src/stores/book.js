import { toRaw } from 'vue';
import { defineStore } from "pinia";
import _ from 'lodash';

export const useBookStore = defineStore('book-store', {
    state: () => ({
        book: {},
        book_list: {},
        is_loading: true,
        errors: {},
        dropdown: [{name: '', label: ''}],
    }),

    actions: {
        async createBooks(data) {
            this.resetErrors();

            const formData = this.getFormData(data);

            debugger;

            const response = await fetch('/api/books/create', {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    authorization: `Bearer ${localStorage.getItem("token")}`
                },
                body: formData,
            });

            const jsonResponse = await response.json();

            if (!_.isEmpty(jsonResponse.errors)) {
                this.errors = jsonResponse.errors;
            } else {
                this.resetErrors();
                this.router.push('/books');
            }
        },

        async getList(params) {
            this.is_loading = true;
            this.book_list = {};

            const response = await fetch('/api/books/get_list', {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    authorization: `Bearer ${localStorage.getItem("token")}`
                },
                body: JSON.stringify(params),
            });

            const jsonResponse = await response.json();

            if (response.ok) {
                this.book_list = jsonResponse.data;
            }

            this.is_loading = false;
        },

        async getBook(params, id) {
            this.is_loading = true;
            this.book = {};

            const response = await fetch(`/api/books/get/${id}`, {
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    authorization: `Bearer ${localStorage.getItem("token")}`
                },
            });

            const jsonResponse = await response.json();

            if (response.ok) {
                this.book = jsonResponse.book;
            }

            this.is_loading = false;
        },

        async updateBook(data, id) {
            this.resetErrors();

            const formData = this.getFormData(data);
            formData.append('_method', 'PUT');

            // Debug formData
            for (const [key, value] of formData.entries()) {
                console.log(`${key}:`, value);
            }

            const response = await fetch(`/api/books/update/${id}`, {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    authorization: `Bearer ${localStorage.getItem("token")}`
                },
                body: formData,
            });

            const jsonResponse = await response.json();

            if (!_.isEmpty(jsonResponse.errors)) {
                this.errors = jsonResponse.errors;
            } else {
                this.resetErrors();
                this.router.push(`/books/show/${id}`);
            }
        },

        async deleteBook(data) {
            this.resetErrors();
        },

        getFormData(data) {
            const rawData = toRaw(data);
            const formData = new FormData();

            for (const [key, value] of Object.entries(rawData)) {
                if (key === 'image') {
                    if (value instanceof File) {
                        formData.append(key, value);
                    }
                } else {
                    formData.append(key, (value !== null && value !== undefined && value !== '') ? value : '');
                }
            }

            return formData;
        },

        async getCategoryDropdownList() {
            this.dropdown = [];

            const response = await fetch(`/api/books/get_dropdown_list`, {
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