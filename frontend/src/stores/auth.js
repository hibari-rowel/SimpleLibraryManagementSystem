import { defineStore } from "pinia";
import _ from 'lodash';

export const useAuthStore = defineStore('auth-store', {
    state: () => ({
        user: null,
        errors: {},
    }),

    getters: {
        isAuthenticated: (state) => !_.isEmpty(state.user),
    },

    actions: {
        async register(data) {
            this.errors = {};

            const response = await fetch('/api/register', {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(data),
            });

            const jsonResponse = await response.json();

            if (!_.isEmpty(jsonResponse.errors)) {
                this.errors = jsonResponse.errors;
            } else {
                this.router.push('/');
            }
        },

        async login(data) {
            this.errors = {};

            const response = await fetch('/api/login', {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(data),
            });

            const jsonResponse = await response.json();

            if (!_.isEmpty(jsonResponse.errors)) {
                this.errors = jsonResponse.errors;
            } else {
                localStorage.setItem('token', jsonResponse.token);
                this.user = jsonResponse.user;
                this.router.push('/dashboard');
            }
        },

        async logout() {
            const response = await fetch('/api/logout', {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                    authorization: `Bearer ${localStorage.getItem("token")}`
                },
            });

            const jsonResponse = await response.json();

            if (response.ok) {
                this.user = null;
                this.errors = {};
                localStorage.removeItem('token');
                this.router.push('/');
            }
        },

        async fetchUser() {
            if (!_.isEmpty(localStorage.getItem("token")) && _.isEmpty(this.user)) {
                const response = await fetch('/api/current_user', {
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
            }
        },

        resetErrors() {
            this.errors = {};
        }
    }
});