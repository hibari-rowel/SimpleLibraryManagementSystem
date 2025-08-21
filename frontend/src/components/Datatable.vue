<script setup lang="ts">
    import {onBeforeUnmount, onMounted} from 'vue';

    const props = defineProps({
        'table_id': String,
        'dt_props': Object,
    });

    let table = null;

    onMounted(() => {
        table = $('#' + props.table_id).DataTable(props.dt_props);
    });

    onBeforeUnmount(() => {
        table.destroy();
    });
</script>

<template>
    <div class="card">
        <div class="card-body">
            <table :id="table_id" class="table table-bordered table-hover">
                <thead>
                <tr>
                    <slot name="t_headers"></slot>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</template>

<style scoped>
</style>