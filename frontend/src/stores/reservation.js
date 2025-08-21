import { defineStore } from "pinia";
import _ from 'lodash';

export const useReservationStore = defineStore('reservation-store', {
    state: () => ({
        reservation: {},
        dt_options: {},
        errors: {},
    }),

    actions: {
        async getDTList(dtParams) {
            const response = await fetch('/api/reservations/get_list', {
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

        async getReservation(params, id) {
            this.resetErrors();

            const response = await fetch(`/api/reservations/get/${id}`, {
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    authorization: `Bearer ${localStorage.getItem("token")}`
                },
            });

            const jsonResponse = await response.json();

            if (response.ok) {
                this.reservation = jsonResponse.reservation;
            }
        },

        async createReservation(data) {
            this.resetErrors();

            const response = await fetch('/api/reservations/create', {
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
                this.router.push('/reservations');
            }
        },

        async updateReservation(data, id) {
            this.resetErrors();

            const response = await fetch(`/api/reservations/update/${id}`, {
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
                this.router.push(`/reservations`);
            }
        },

        async deleteReservation(id) {
            this.resetErrors();

            try {
                const response = await fetch(`/api/reservations/destroy/${id}`, {
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
})