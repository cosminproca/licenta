<template>
  <div
    v-if="hasError"
    class="absolute text-sm text-red-500 -bottom-6"
    v-html="errorMessage"
  ></div>
</template>

<script>
export default {
  name: 'HasError',
  props: {
    form: {
      type: Object,
      required: true
    },
    field: {
      type: String,
      required: true
    }
  },
  computed: {
    hasError() {
      return this.form.errors.has(this.field);
    },
    errorMessage() {
      if (this.hasError) {
        return this.form.errors.get(this.field);
      }
      return null;
    }
  }
};
</script>
