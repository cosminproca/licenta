<template>
  <div class="flex mt-6">
    <div class="w-full md:w-2/3 md:mx-auto md:max-w-md">
      <card class="text-center">
        <h3 class="mb-4 font-extralight text-3xl text-gray-900">
          {{ $t('page_not_found') }}
        </h3>

        <div class="links">
          <router-link
            :to="{ name: 'welcome' }"
            class="hover:underline text-gray-700"
          >
            {{ $t('go_home') }}
          </router-link>
        </div>
      </card>
    </div>
  </div>
</template>

<script>
export default {
  name: 'NotFound'
};
</script>
