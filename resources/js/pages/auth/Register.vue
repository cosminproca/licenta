<template>
  <div class="flex mt-10">
    <div class="w-full md:w-2/3 md:mx-auto md:max-w-md">
      <card v-if="mustVerifyEmail" :title="$t('register')">
        <div class="text-green-500">
          {{ $t('verify_email_address') }}
        </div>
      </card>
      <card v-else :title="$t('register')">
        <form @submit.prevent="register" @keydown="form.onKeydown($event)">
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
          <v-button class="w-full mt-4" :loading="form.busy">
            {{ $t('register') }}
          </v-button>

          <!-- GitHub Register Button -->
          <LoginWithGithub />
        </form>
      </card>
    </div>
  </div>
</template>

<script>
import Form from 'vform';
import LoginWithGithub from '@/components/LoginWithGithub';

export default {
  components: {
    LoginWithGithub
  },

  middleware: 'guest',

  metaInfo() {
    return { title: this.$t('register') };
  },

  data() {
    return {
      form: new Form({
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      }),
      mustVerifyEmail: false
    };
  },

  methods: {
    async register() {
      // Register the user.
      const { data } = await this.form.post('/api/register');

      // Must verify email fist.
      if (data.status) {
        this.mustVerifyEmail = true;
      } else {
        // Log in the user.
        const {
          data: { token }
        } = await this.form.post('/api/login');

        // Save the token.
        await this.$store.dispatch('auth/saveToken', { token });

        // Update the user.
        await this.$store.dispatch('auth/updateUser', { user: data });

        // Redirect home.
        this.$router.push({ name: 'home' });
      }
    }
  }
};
</script>
