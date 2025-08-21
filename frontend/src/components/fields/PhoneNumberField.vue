<script setup lang="ts">
    import _ from 'lodash';
    import { onMounted, onBeforeUnmount } from 'vue';

    const model = defineModel<string>();

    const props = defineProps({
        phone_id: String,
        label: String,
        placeholder: String,
        is_required: Boolean,
        errors: Array
    });

    function handleInputChange(e: Event) {
        const target = e.target as HTMLInputElement;
        model.value = target.value;
    }

    onMounted(() => {
        const $input = $('#' + props.phone_id);

        $input.inputmask({
            mask: "(+639) 99-999-9999"
        });

        // Initialize model with current value
        model.value = $input.val() as string;

        // Listen to change and input events
        $input.on('input change', handleInputChange);
    });

    onBeforeUnmount(() => {
        const $input = $('#' + props.phone_id);
        $input.off('input change', handleInputChange);
    });
</script>

<template>
    <div class="form-group">
        <label> {{ label }} <span class="text-danger" v-if="is_required">*</span> </label>

        <input :id="phone_id" type="text" class="form-control" :placeholder="placeholder" :value="model">

        <small class="text-danger" v-if="errors" v-html="_.join(errors, '<br>')"></small>
    </div>
</template>
