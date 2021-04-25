<template>
  <div>
    <div class="text-center">
      <div class="text-6xl font-thin text-gray-500 mt-16 mb-8" dusk="title">
        {{ title }}
      </div>

      <div>
        <a
          class="font-bold hover:underline"
          href="https://github.com/JhumanJ/laravel-vue-tailwind-spa"
          >JhumanJ/laravel-vue-tailwind-spa</a
        >
        <p class="text-center my-5">a fork from</p>
        <a
          class="font-bold hover:underline"
          href="https://github.com/cretueusebiu/laravel-vue-spa"
          >github.com/cretueusebiu/laravel-vue-spa</a
        >
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  layout: 'default',

  metaInfo() {
    return { title: this.$t('home') };
  },

  data: () => ({
    title: window.config.appName
  }),

  computed: mapGetters({
    authenticated: 'auth/check'
  })
};
</script>

<style scoped>
.top-right {
  position: absolute;
  right: 10px;
  top: 18px;
}
</style>
