<script setup lang="ts">
    import {ref, watch} from 'vue';
    import _ from 'lodash';

    const model = defineModel();

    const props = defineProps({
        'label': String,
        'is_required': Boolean,
        'errors': Array,
        'defaultImage': {type: String, default: '/defaultimage.png'},
        'previewWidth': { type: String, default: '150px' },
        'previewHeight': { type: String, default: '150px' },
        'defaultImageName' : { type: String, default: 'Choose image' }
    });

    const imagePreview = ref <string  | null> (props.defaultImage);
    const fileName = ref <string> (props.defaultImageName);

    const handleFileChange = (e: Event) => {
        const file = (e.target as HTMLInputElement).files?.[0] || null;
        model.value = file;

        if (file) {
            fileName.value = file.name;
        } else {
            fileName.value = 'Choose image';
        }
    }

    watch(model, (newFile) => {
        if (newFile) {
            const reader = new FileReader()

            reader.onload = (e) => {
                imagePreview.value = e.target?.result as string
            }

            reader.readAsDataURL(newFile);
        } else {
            imagePreview.value = null
        }
    })
</script>

<template>
    <div class="form-group">
        <label v-if="label" class="form-label"> {{ label }} <span v-if="is_required" class="text-danger">*</span> </label>

        <div class="my-3 text-center">
            <img :src="imagePreview" alt="default-image" class="img-thumbnail"
                 :style="{width: previewWidth, height: previewHeight, objectFit: 'fill'}">
        </div>

        <div class="custom-file">
            <input type="file" class="custom-file-input" accept="image/*" @change="handleFileChange">
            <label class="custom-file-label"> {{ fileName }} </label>
        </div>

        <small class="text-danger" v-if="errors" v-html="_.join(errors, '<br>')"></small>
    </div>
</template>

<style scoped>
</style>