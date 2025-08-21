<script setup lang="ts">
    import {useRouter} from "vue-router";
    import Swal from "sweetalert2";
    import { useBorrowingStore } from "@/stores/borrowing.js";

    import BaseTemplate from "../../components/BaseTemplate.vue";
    import Datatable from "../../components/Datatable.vue";

    const header = {
        module: 'Borrowings',
        icon: 'nav-icon fas fa-solid fa-book-open',
        bread_crumbs: [
            {name: "Borrowings", is_active: true,}
        ]
    }

    const router = useRouter();

    const borrowingStore = useBorrowingStore();

    const datatableID = 'borrowings-list';

    const datatableProps = {
        paging: true,
        searching: true,
        autoWidth: true,
        responsive: true,
        destroy: true,
        deferRender: true,
        processing:false,
        serverSide: true,
        searchDelay: 1000,
        pageLength: 5,
        lengthMenu: [5, 10, 20],
        order: [[7, 'desc']],
        language: {
            searchPlaceholder: "User Name, Book Name",
        },
        ajax: async (data, callback, settings) => {
            try {
                await borrowingStore.getDTList(data);
                callback(borrowingStore.dt_options);
            } catch (err) {
                callback({
                    draw: data.draw,
                    data: [],
                    recordsTotal: 0,
                    recordsFiltered: 0,
                });
            }
        },
        columnDefs: [
            {
                data: null,
                targets: 0,
                orderable: false,
                visible: true,
                width: "10%",
                title: '',
                render: function(data, type, full) {
                    return `<a href="#" class="edit-link btn btn-primary mr-2" data-id="${full.id}">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" class="delete-link btn btn-danger mr-2" data-id="${full.id}">
                                <i class="fas fa-trash"></i>
                            </a>
                            `;
                }
            },
            {
                data: 'user_name',
                targets: 1,
                orderable: true,
                visible: true,
                width: "20%",
                title: 'User',
                render: function(data, type, full) {
                    return `<a href="#" class="show-user-link" data-id="${full.user_id}"> ${data} </a>`;
                }
            },
            {
                data: 'book_name',
                targets: 2,
                orderable: true,
                visible: true,
                width: "20%",
                title: 'Book',
                render: function(data, type, full) {
                    return `<a href="#" class="show-book-link" data-id="${full.book_id}"> ${data} </a>`;
                }
            },
            {
                data: 'status',
                targets: 3,
                orderable: true,
                visible: true,
                width: "10%",
                title: 'Status',
                render: function(data, type, full) {
                    return data;
                }
            },
            {
                data: 'borrow_date',
                targets: 4,
                orderable: true,
                visible: true,
                width: "10%",
                title: 'Date Borrowed',
                render: function(data, type, full) {
                    return data;
                }
            },
            {
                data: 'due_date',
                targets: 5,
                orderable: true,
                visible: true,
                width: "10%",
                title: 'Date Due',
                render: function(data, type, full) {
                    return data;
                }
            },
            {
                data: 'return_date',
                targets: 6,
                orderable: true,
                visible: true,
                width: "10%",
                title: 'Date Returned',
                render: function(data, type, full) {
                    return data;
                }
            },
            {
                data: 'created_at',
                targets: 7,
                orderable: true,
                visible: true,
                width: "10%",
                title: 'Created At',
                render: function(data, type, full) {
                    return data;
                }
            },
        ],
        drawCallback: function () {
            const table = document.querySelector('#borrowings-list');

            if (!table.__eventBound) {
                table.addEventListener('click', (e) => {
                    const editLink = e.target.closest('.edit-link');
                    const showUserLink = e.target.closest('.show-user-link');
                    const showBookLink = e.target.closest('.show-book-link');
                    const deleteLink = e.target.closest('.delete-link');

                    const defaultSweetAlert = Swal.mixin({
                        customClass: {
                            confirmButton: "btn btn-lg btn-primary p-2",
                            cancelButton: "btn btn-lg btn-danger p-2"
                        },
                    });

                    if (editLink) {
                        e.preventDefault();
                        const id = editLink.getAttribute('data-id');
                        router.push(`/borrowings/edit/${id}`);
                    }

                    if (showUserLink) {
                        e.preventDefault();
                        const id = showUserLink.getAttribute('data-id');
                        router.push(`/users/show/${id}`);
                    }

                    if (showBookLink) {
                        e.preventDefault();
                        const id = showBookLink.getAttribute('data-id');
                        router.push(`/books/show/${id}`);
                    }

                    if (deleteLink) {
                        e.preventDefault();
                        const id = deleteLink.getAttribute('data-id');

                        defaultSweetAlert.fire({
                            title: 'Warning!',
                            icon: 'warning',
                            text: 'Are you sure you want to delete this Record?',
                            confirmButtonText: 'Confirm',
                            showCancelButton: true,
                        }).then(async (result) => {
                            if (result.isConfirmed) {
                                defaultSweetAlert.fire({
                                    title: 'Deleting...',
                                    allowOutsideClick: false,
                                    didOpen: () => {
                                        Swal.showLoading()
                                    }
                                })

                                try {
                                    await borrowingStore.deleteBorrowing(id);
                                    Swal.close();

                                    defaultSweetAlert.fire({
                                        title: 'Deleted!',
                                        text: 'The record has been successfully deleted.',
                                        icon: 'success',
                                        didOpen: () => {
                                            Swal.hideLoading()
                                        }
                                    });

                                    $('#borrowings-list').DataTable().ajax.reload(null, false);
                                } catch (err) {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: err.message || 'Something went wrong.',
                                        icon: 'error'
                                    });
                                }
                            }
                        });
                    }
                });

                table.__eventBound = true;
            }
        }
    }
</script>

<template>
    <base-template :header="header">
        <template v-slot:button>
            <RouterLink :to="'/borrowings/create'" class="btn font-weight-bolder btn-primary">
                Create
            </RouterLink>
        </template>

        <datatable :table_id="datatableID" :dt_props="datatableProps">
            <template v-slot:t_headers>
                <th></th>
                <th>User</th>
                <th>Book</th>
                <th>Status</th>
                <th>Date Borrowed</th>
                <th>Date Due</th>
                <th>Date Returned</th>
                <th>Created At</th>
            </template>
        </datatable>
    </base-template>
</template>

<style scoped>
</style>