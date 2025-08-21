import {createRouter, createWebHistory} from 'vue-router';
import { useAuthStore } from "@/stores/auth";

import LoginView from '../views/Auth/LoginView.vue';
import RegisterView from '../views/Auth/RegisterView.vue';
import DashboardView from '../views/Dashboard/DashboardView.vue';

import BooksListView from '../views/Books/ListView.vue';
import BooksCreateView from '../views/Books/CreateView.vue';
import BooksShowView from '../views/Books/ShowView.vue';
import BooksEditView from '../views/Books/EditView.vue';

import CategoriesListView from '../views/Categories/ListView.vue';
import CategoriesCreateView from '../views/Categories/CreateView.vue';
import CategoriesShowView from '../views/Categories/ShowView.vue';
import CategoriesEditView from '../views/Categories/EditView.vue';

import BorrowingsListView from '../views/Borrowings/ListView.vue';
import BorrowingsCreateView from '../views/Borrowings/CreateView.vue';
import BorrowingsShowView from '../views/Borrowings/ShowView.vue';
import BorrowingsEditView from '../views/Borrowings/EditView.vue';

import FinesListView from '../views/Fines/ListView.vue';
import FinesCreateView from '../views/Fines/CreateView.vue';
import FinesShowView from '../views/Fines/ShowView.vue';
import FinesEditView from '../views/Fines/EditView.vue';

import ReservationsListView from '../views/Reservations/ListView.vue';
import ReservationsCreateView from '../views/Reservations/CreateView.vue';
import ReservationsShowView from '../views/Reservations/ShowView.vue';
import ReservationsEditView from '../views/Reservations/EditView.vue';

import UsersListView from '../views/Users/ListView.vue';
import UsersCreateView from '../views/Users/CreateView.vue';
import UsersShowView from '../views/Users/ShowView.vue';
import UsersEditView from '../views/Users/EditView.vue';

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'login',
            component: LoginView,
            meta: { requiresAuth: false },
        },
        {
            path: '/register',
            name: 'register',
            component: RegisterView,
            meta: { requiresAuth: false },
        },
        {
            path: '/dashboard',
            name: 'dashboard',
            component: DashboardView,
            meta: { requiresAuth: true },
        },
        {
            path: '/books',
            meta: { requiresAuth: true },
            children: [
                { path: '', component: BooksListView },
                { path: 'create', component: BooksCreateView },
                { path: 'show/:id', component: BooksShowView },
                { path: 'edit/:id', component: BooksEditView },
            ],
        },
        {
            path: '/categories',
            meta: { requiresAuth: true },
            children: [
                { path: '', component: CategoriesListView },
                { path: 'create', component: CategoriesCreateView },
                { path: 'show/:id', component: CategoriesShowView },
                { path: 'edit/:id', component: CategoriesEditView },
            ],
        },
        {
            path: '/borrowings',
            meta: { requiresAuth: true },
            children: [
                { path: '', component: BorrowingsListView },
                { path: 'create', component: BorrowingsCreateView },
                { path: 'edit/:id', component: BorrowingsEditView },
            ],
        },
        {
            path: '/fines',
            meta: { requiresAuth: true },
            children: [
                { path: '', component: FinesListView },
                { path: 'create', component: FinesCreateView },
                { path: 'show/:id', component: FinesShowView },
                { path: 'edit/:id', component: FinesEditView },
            ],
        },
        {
            path: '/reservations',
            meta: { requiresAuth: true },
            children: [
                { path: '', component: ReservationsListView },
                { path: 'create', component: ReservationsCreateView },
                { path: 'show/:id', component: ReservationsShowView },
                { path: 'edit/:id', component: ReservationsEditView },
            ],
        },
        {
            path: '/users',
            meta: { requiresAuth: true },
            children: [
                { path: '', component: UsersListView },
                { path: 'create', component: UsersCreateView },
                { path: 'show/:id', component: UsersShowView },
                { path: 'edit/:id', component: UsersEditView },
            ],
        },
    ],
})

router.beforeEach( async (to, from, next) => {
    const authStore = useAuthStore();
    await authStore.fetchUser();

    if (!authStore.isAuthenticated && to.meta.requiresAuth) {
        next('/');
    } else if (authStore.isAuthenticated && !to.meta.requiresAuth) {
        next('/dashboard');
    } else {
        next();
    }
});

export default router
