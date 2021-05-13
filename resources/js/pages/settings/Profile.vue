<template>
  <card :title="$t('your_info')">
    <form @submit.prevent="update" @keydown="form.onKeydown($event)">
      <alert-success class="mb-5" :form="form" :message="$t('info_updated')" />

      <!-- Name -->
      <text-input
        v-model="form.name"
        name="name"
        :form="form"
        :label="$t('name')"
        :required="true"
      />

      <!-- Email -->
      <text-input
        v-model="form.email"
        name="email"
        :form="form"
        :label="$t('email')"
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
import { mapGetters } from 'vuex';

export default {
  scrollToTop: false,

  metaInfo() {
    return { title: this.$t('settings') };
  },

  data() {
    return {
      form: new Form({
        name: '',
        email: ''
      })
    };
  },

  computed: mapGetters({
    user: 'auth/user'
  }),

  created() {
    // Fill the form with user data.
    this.form.keys().forEach(key => {
      this.form[key] = this.user[key];
    });
  },

  methods: {
    async update() {
      const { data } = await this.form.patch('/api/settings/profile');

      this.$store.dispatch('auth/updateUser', { user: data });
    }
  }
};
</script>
