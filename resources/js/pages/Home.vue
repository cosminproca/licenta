<template>
  <div class="flex mt-6">
    <div class="w-full md:w-2/3 md:mx-auto md:max-w-md">
      <card :title="$t('home')">
        {{ $t('you_are_logged_in') }}
      </card>
    </div>
  </div>
</template>

<script>
export default {
  middleware: 'auth',

  metaInfo() {
    return { title: this.$t('home') };
  }
};
</script>
