<script setup lang="ts">
    import _ from 'lodash';

    const model = defineModel();

    const props = defineProps({
        'label': String,
        'placeholder': String,
        'is_required': Boolean,
        'is_disabled': Boolean,
        'errors': Array,
        'min': Number,
        'step': Number,
    });
</script>

<template>
    <div class="form-group">
        <label>{{ label }} <span class="text-danger" v-if="is_required">*</span></label>

        <input type="number" class="form-control" v-model="model" :min="min" :step="step" :placeholder="placeholder"
               :disabled="is_disabled"/>

        <small class="text-danger" v-if="errors" v-html="_.join(errors, '<br>')"></small>
    </div>
</template>

<style scoped>
</style>