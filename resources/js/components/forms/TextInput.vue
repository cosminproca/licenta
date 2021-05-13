<template>
  <div class="relative mb-6">
    <label v-if="label" :for="id ? id : name" class="text-gray-700">
      {{ label }}
      <span v-if="required" class="text-red-500 required-dot">*</span>
    </label>
    <input
      :id="id ? id : name"
      :value="value"
      :type="nativeType"
      :class="{ 'ring-red-500 ring-2': form.errors.has(name) }"
      class="rounded-lg border-transparent flex-1 appearance-none border border-gray-300 w-full py-2 px-4 bg-white text-gray-700 placeholder-gray-400 shadow-sm text-base focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent"
      :name="name"
      :placeholder="placeholder"
      @input="$emit('input', $event.target.value)"
    />
    <has-error :form="form" :field="name" />
  </div>
</template>

<script>
export default {
  name: 'TextInput',

  props: {
    id: { type: String, default: null },
    nativeType: { type: String, default: 'text' },
    name: { type: String, required: true },
    value: { type: String, default: '' },
    label: { type: String, required: false, default: '' },
    form: { type: Object, required: true },
    required: { type: Boolean, default: false },
    placeholder: { type: String, default: null }
  },

  data() {
    return {};
  },

  watch: {},

  created() {},

  methods: {}
};
</script>
