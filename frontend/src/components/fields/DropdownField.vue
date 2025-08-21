<script setup lang="ts">
    import { onMounted, onBeforeUnmount, watch, nextTick } from 'vue';
    import _ from 'lodash';

    const modelValue = defineModel<string>();

    const props = defineProps({
        dropdown_id: String,
        label: String,
        placeholder: String,
        is_required: Boolean,
        dropdown_list: Array as () => Array<{ name: string, label: string }>,
        errors: Array,
    });

    const initializeSelect = () => {
        const $select = $('#' + props.dropdown_id);

        $select.select2({ theme: 'bootstrap4' });

        // Sync model with Select2
        $select.val(modelValue.value).trigger('change');

        $select.on('change', function () {
            modelValue.value = $(this).val() as string;
        });
    };

    onMounted(async () => {
        await nextTick();

        // Set default only if empty and dropdown has items
        if (!modelValue.value && props.dropdown_list.length > 0) {
            modelValue.value = props.dropdown_list[0].name;
        }

        initializeSelect();
    });

    onBeforeUnmount(() => {
        $('#' + props.dropdown_id).off('change').select2('destroy');
    });

    watch(modelValue, (newValue) => {
        const $select = $('#' + props.dropdown_id);
        if ($select.length > 0) {
            $select.val(newValue).trigger('change');
        }
    });

    // Re-init select2 if dropdown list is updated dynamically
    watch(
        () => props.dropdown_list,
        async (newList) => {
            if (newList.length > 0 && !modelValue.value) {
                modelValue.value = newList[0].name;
            }
            await nextTick();
            initializeSelect();
        },
        { immediate: true }
    );
</script>

<template>
    <div class="form-group">
        <label>{{ label }} <span class="text-danger" v-if="is_required">*</span></label>

        <select class="form-control" :id="dropdown_id">
            <option v-for="(dropdown, index) in dropdown_list" :key="index" :value="dropdown.name">
                {{ dropdown.label }}
            </option>
        </select>

        <small class="text-danger" v-if="errors" v-html="_.join(errors, '<br>')"></small>
    </div>
</template>
