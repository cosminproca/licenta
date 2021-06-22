<template>
  <button
    :type="nativeType"
    :disabled="loading"
    class="btn py-2 px-4
    bg-blue-500 hover:bg-blue-600 focus:ring-blue-400 focus:ring-offset-blue-200 text-white transition ease-in duration-200 text-center text-base font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 rounded-lg"
  >
    <div v-show="!loading">
      <slot />
    </div>
    <svg
      v-show="loading"
      xmlns="http://www.w3.org/2000/svg"
      class="h-6 w-6 text-white animate-spin mx-auto"
      fill="none"
      viewBox="0 0 24 24"
      stroke="currentColor"
    >
      <path
        stroke-linecap="round"
        stroke-linejoin="round"
        stroke-width="2"
        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
      />
    </svg>
  </button>
</template>

<script>
export default {
  name: 'VButton',

  props: {
    color: {
      type: String,
      default: 'blue'
    },

    nativeType: {
      type: String,
      default: 'submit'
    },

    loading: {
      type: Boolean,
      default: false
    }
  }
};
</script>
