<template>
  <div
    class="flex flex-col w-full bg-white rounded-lg shadow"
    :class="{ 'px-4 py-8 sm:px-6 md:px-8 lg:px-10': padding }"
  >
    <slot :title="title" name="card-title">
      <div
        v-if="title"
        class="self-center mb-6 text-xl font-semibold text-gray-600 sm:text-2xl dark:text-white"
      >
        {{ title }}
      </div>
    </slot>
    <slot />
  </div>
</template>

<script>
export default {
  name: 'Card',

  props: {
    padding: {
      type: Boolean,
      default: true
    },
    title: {
      type: String,
      default: null
    }
  }
};
</script>
