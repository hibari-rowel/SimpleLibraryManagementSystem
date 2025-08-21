<script setup>
  import { RouterLink, RouterView } from 'vue-router';
  import { onMounted } from 'vue';
  import { useAuthStore } from "@/stores/auth";

  const authStore = useAuthStore();
</script>

<template>
  <RouterView />
</template>

<style scoped>

</style>
