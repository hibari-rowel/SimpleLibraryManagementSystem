<script setup lang="ts">
    const model = defineModel();

    const props = defineProps({
        'label': String,
        'placeholder': String,
        'is_required': Boolean,
    });
</script>

<template>
    <div class="form-group">
        <label>{{ label }} <span class="text-danger" v-if="is_required">*</span></label>
        <input type="password" class="form-control" :placeholder="placeholder" v-model="model">
    </div>
</template>

<style scoped>
</style>