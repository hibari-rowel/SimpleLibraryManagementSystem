import { defineStore } from "pinia";
import _ from 'lodash';

export const useBorrowingStore = defineStore('borrowing-store', {
    state: () => ({
        borrowing: {},
        dt_options: {},
        errors: {},
    }),

    actions: {
        async getDTList(dtParams) {
            const response = await fetch('/api/borrowings/get_list', {
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

        async getBorrowing(params, id) {
            this.resetErrors();

            const response = await fetch(`/api/borrowings/get/${id}`, {
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    authorization: `Bearer ${localStorage.getItem("token")}`
                },
            });

            const jsonResponse = await response.json();

            if (response.ok) {
                this.borrowing = jsonResponse.borrowing;
            }
        },

        async createBorrowing(data) {
            this.resetErrors();

            const response = await fetch('/api/borrowings/create', {
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
                this.router.push('/borrowings');
            }
        },

        async updateBorrowing(data, id) {
            this.resetErrors();

            const response = await fetch(`/api/borrowings/update/${id}`, {
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
                this.router.push(`/borrowings`);
            }
        },

        async deleteBorrowing(id) {
            this.resetErrors();

            try {
                const response = await fetch(`/api/borrowings/destroy/${id}`, {
                    method: "DELETE",
                    headers: {
                        Accept: "application/json",
                        "Content-Type": "application/json",
                        authorization: `Bearer ${localStorage.getItem("token")}`
                    },
                });

                const jsonResponse = await response.json();

                if (!response.ok) {
                    throw new Error(jsonResponse.message || 'An error occurred while deleting the record.');
                }
            } catch (err) {
                throw err;
            }
        },

        resetErrors() {
            this.errors = {};
        },
    }
});