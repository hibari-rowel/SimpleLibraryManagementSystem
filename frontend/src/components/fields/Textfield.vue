<script setup lang="ts">
    import _ from 'lodash';

    const model = defineModel();

    const props = defineProps({
        'label': String,
        'placeholder': String,
        'is_required': Boolean,
        'is_disabled': Boolean,
        'errors': Array,
    });
</script>

<template>
    <div class="form-group">
        <label>{{ label }} <span class="text-danger" v-if="is_required">*</span></label>

        <input type="text" class="form-control" :placeholder="placeholder" v-model="model" :disabled="is_disabled">

        <small class="text-danger" v-if="errors" v-html="_.join(errors, '<br>')"></small>
    </div>
</template>

<style scoped>
</style>