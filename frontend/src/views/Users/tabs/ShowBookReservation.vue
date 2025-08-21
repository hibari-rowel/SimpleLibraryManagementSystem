<script setup lang="ts">
    import {useRouter} from "vue-router";
    import { useReservationStore } from "@/stores/reservation.js";

    import Datatable from "../../../components/Datatable.vue";

    const props = defineProps({
        'user_id': String,
    });

    const reservationStore = useReservationStore();
    const router = useRouter();
    const datatableID = 'user-reservations-list';

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
            searchPlaceholder: "Book Name",
        },
        ajax: async (data, callback, settings) => {
            try {
                data.user_id = props.user_id;
                data.status = ['pending'];

                await reservationStore.getDTList(data);
                callback(reservationStore.dt_options);
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
                width: "30%",
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
                width: "20%",
                title: 'Status',
                render: function(data, type, full) {
                    return data;
                }
            },
            {
                data: 'reservation_date',
                targets: 2,
                orderable: true,
                visible: true,
                width: "20%",
                title: 'Reservation Date',
                render: function(data, type, full) {
                    return data;
                }
            },
            {
                data: 'created_at',
                targets: 3,
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
            const table = document.querySelector('#user-reservations-list');

            if (!table.__eventBound) {
                table.addEventListener('click', (e) => {
                    e.preventDefault();

                    const showBookLink = e.target.closest('.show-book-link');
                    if (showBookLink) {
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
            <th>Reservation Date</th>
            <th>Created At</th>
        </template>
    </datatable>
</template>

<style scoped>
</style>