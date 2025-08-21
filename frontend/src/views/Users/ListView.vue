<script setup lang="ts">
    import { useRouter } from 'vue-router';
    import Swal from 'sweetalert2';
    import { useUserStore } from "@/stores/user.js";

    import BaseTemplate from "../../components/BaseTemplate.vue";
    import Datatable from "../../components/Datatable.vue";

    const router = useRouter();

    const userStore = useUserStore();

    const header = {
        module: 'Users',
        icon: 'nav-icon fas fa-solid fa-user',
        bread_crumbs: [
            {name: "Users", is_active: true,},
        ],
    }

    const datatableID = 'users-list';

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
        order: [[4, 'desc']],
        language: {
            searchPlaceholder: "First Name, Last Name",
        },
        ajax: async (data, callback, settings) => {
            try {
                await userStore.getDTList(data);
                callback(userStore.dt_options);
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
                data: 'name',
                targets: 1,
                orderable: true,
                visible: true,
                width: "40%",
                title: 'Name',
                render: function(data, type, full) {
                    return `<a href="#" class="show-link" data-id="${full.id}"> ${data} </a>`;
                }
            },
            {
                data: 'membership_type',
                targets: 2,
                orderable: true,
                visible: true,
                width: "15%",
                title: 'Membership Type',
                render: function(data, type, full) {
                    return data;
                }
            },
            {
                data: 'status',
                targets: 3,
                orderable: true,
                visible: true,
                width: "15%",
                title: 'Status',
                render: function(data, type, full) {
                    return data;
                }
            },
            {
                data: 'created_at',
                targets: 4,
                orderable: true,
                visible: true,
                width: "20%",
                title: 'Created At',
                render: function(data, type, full) {
                    return data;
                }
            },
        ],
        drawCallback: function () {
            const table = document.querySelector('#users-list');

            if (!table.__eventBound) {
                table.addEventListener('click', (e) => {
                    const editLink = e.target.closest('.edit-link');
                    const showLink = e.target.closest('.show-link');
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
                        router.push(`/users/edit/${id}`);
                    }

                    if (showLink) {
                        e.preventDefault();
                        const id = showLink.getAttribute('data-id');
                        router.push(`/users/show/${id}`);
                    }

                    if (deleteLink) {
                        e.preventDefault();
                        const id = deleteLink.getAttribute('data-id');

                        defaultSweetAlert.fire({
                            title: 'Warning!',
                            icon: 'warning',
                            text: 'Are you sure you want to delete this User?',
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
                                    await userStore.deleteUser(id);
                                    Swal.close();

                                    defaultSweetAlert.fire({
                                        title: 'Deleted!',
                                        text: 'The user has been successfully deleted.',
                                        icon: 'success',
                                        didOpen: () => {
                                            Swal.hideLoading()
                                        }
                                    });

                                    $('#users-list').DataTable().ajax.reload(null, false);
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
    };
</script>

<template>
    <base-template :header="header">
        <template v-slot:button>
            <RouterLink :to="'/users/create'" class="btn font-weight-bolder btn-primary">
                Create
            </RouterLink>
        </template>

        <datatable :table_id="datatableID" :dt_props="datatableProps">
            <template v-slot:t_headers>
                <th></th>
                <th>Name</th>
                <th>Membership Type</th>
                <th>Status</th>
                <th>Created At</th>
            </template>
        </datatable>
    </base-template>
</template>

<style scoped>
</style>