<script setup lang="ts">
    import {useRouter} from "vue-router";
    import { useBorrowingStore } from "@/stores/borrowing.js";

    import Datatable from "../../../components/Datatable.vue";

    const props = defineProps({
        'user_id': String,
    });

    const datatableID = 'users-borrowing-list';
    const borrowingStore = useBorrowingStore();
    const router = useRouter();

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
        order: [[5, 'desc']],
        language: {
            searchPlaceholder: "Book Name",
        },
        ajax: async (data, callback, settings) => {
            try {
                data.user_id = props.user_id;
                data.status = ['borrowed', 'overdue'];

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
                data: 'book_name',
                targets: 0,
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
                targets: 1,
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
                targets: 2,
                orderable: true,
                visible: true,
                width: "15%",
                title: 'Date Borrowed',
                render: function(data, type, full) {
                    return data;
                }
            },
            {
                data: 'due_date',
                targets: 3,
                orderable: true,
                visible: true,
                width: "15%",
                title: 'Date Due',
                render: function(data, type, full) {
                    return data;
                }
            },
            {
                data: 'return_date',
                targets: 4,
                orderable: true,
                visible: true,
                width: "15%",
                title: 'Date Returned',
                render: function(data, type, full) {
                    return data;
                }
            },
            {
                data: 'created_at',
                targets: 5,
                orderable: true,
                visible: true,
                width: "15%",
                title: 'Created At',
                render: function(data, type, full) {
                    return data;
                }
            },
        ],
        drawCallback: function () {
            const table = document.querySelector('#users-borrowing-list');

            if (!table.__eventBound) {
                table.addEventListener('click', (e) => {
                    const showBookLink = e.target.closest('.show-book-link');

                    if (showBookLink) {
                        e.preventDefault();
                        const id = showBookLink.getAttribute('data-id');
                        router.push(`/books/show/${id}`);
                    }
                });

                table.__eventBound = true;
            }
        }
    }
</script>

<template>
    <datatable :table_id="datatableID" :dt_props="datatableProps">
        <template v-slot:t_headers>
            <th>Book</th>
            <th>Status</th>
            <th>Date Borrowed</th>
            <th>Date Due</th>
            <th>Date Returned</th>
            <th>Created At</th>
        </template>
    </datatable>
</template>

<style scoped>

</style>