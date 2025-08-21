<script setup lang="ts">
    import {useRouter} from "vue-router";
    import Swal from "sweetalert2";
    import { useCategoryStore } from "@/stores/category.js";

    import BaseTemplate from "../../components/BaseTemplate.vue";
    import Datatable from "../../components/Datatable.vue";

    const router = useRouter();

    const categoryStore = useCategoryStore();

    const header = {
        module: 'Categories',
        icon: 'nav-icon fas fa-solid fa-layer-group',
        bread_crumbs: [
            {name: "Categories", is_active: true,}
        ]
    }

    const datatableID = 'categories-list';

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
        order: [[3, 'desc']],
        language: {
            searchPlaceholder: "Name",
        },
        ajax: async (data, callback, settings) => {
            try {
                await categoryStore.getDTList(data);
                callback(categoryStore.dt_options);
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
                width: "30%",
                title: 'Name',
                render: function(data, type, full) {
                    return `<a href="#" class="show-link" data-id="${full.id}"> ${data} </a>`;
                }
            },
            {
                data: 'description',
                targets: 2,
                orderable: true,
                visible: true,
                width: "35%",
                title: 'Description',
                render: function(data, type, full) {
                    return data;
                }
            },
            {
                data: 'created_at',
                targets: 3,
                orderable: true,
                visible: true,
                width: "25%",
                title: 'Created At',
                render: function(data, type, full) {
                    return data;
                }
            },
        ],
        drawCallback: function () {
            const table = document.querySelector('#categories-list');

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
                        router.push(`/categories/edit/${id}`);
                    }

                    if (showLink) {
                        e.preventDefault();
                        const id = showLink.getAttribute('data-id');
                        router.push(`/categories/show/${id}`);
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
                                    await categoryStore.deleteCategory(id);
                                    Swal.close();

                                    defaultSweetAlert.fire({
                                        title: 'Deleted!',
                                        text: 'The record has been successfully deleted.',
                                        icon: 'success',
                                        didOpen: () => {
                                            Swal.hideLoading()
                                        }
                                    });

                                    $('#categories-list').DataTable().ajax.reload(null, false);
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
            <RouterLink :to="'/categories/create'" class="btn font-weight-bolder btn-primary">
                Create
            </RouterLink>
        </template>

        <datatable :table_id="datatableID" :dt_props="datatableProps">
            <template v-slot:t_headers>
                <th></th>
                <th>Name</th>
                <th>Description</th>
                <th>Created At</th>
            </template>
        </datatable>
    </base-template>
</template>

<style scoped>
</style>