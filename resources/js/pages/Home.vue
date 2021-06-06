<template>
  <div class="flex mt-6">
    <Dashboard class="w-full mx-6" />
  </div>
</template>

<script>
import Dashboard from '@/components/custom/Dashboard';

export default {
  components: {
    Dashboard
  },
  middleware: 'auth',
  metaInfo() {
    return { title: this.$t('home') };
  }
};
</script>
