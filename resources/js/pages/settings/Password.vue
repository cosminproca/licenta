<template>
  <card :title="$t('your_password')">
    <form @submit.prevent="update" @keydown="form.onKeydown($event)">
      <alert-success
        class="mb-5"
        :form="form"
        :message="$t('password_updated')"
      />

      <!-- Password -->
      <text-input
        v-model="form.password"
        class="mt-8"
        native-type="password"
        name="password"
        :form="form"
        :label="$t('password')"
        :required="true"
      />

      <!-- Password Confirmation-->
      <text-input
        v-model="form.password_confirmation"
        class="mt-8"
        native-type="password"
        name="password_confirmation"
        :form="form"
        :label="$t('confirm_password')"
        :required="true"
      />

      <!-- Submit Button -->
      <v-button :loading="form.busy" type="success">
        {{ $t('update') }}
      </v-button>
    </form>
  </card>
</template>

<script>
import Form from 'vform';

export default {
  scrollToTop: false,

  metaInfo() {
    return { title: this.$t('settings') };
  },

  data() {
    return {
      form: new Form({
        password: '',
        password_confirmation: ''
      })
    };
  },

  methods: {
    async update() {
      await this.form.patch('/api/settings/password');

      this.form.reset();
    }
  }
};
</script>
