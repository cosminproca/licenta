<template>
  <div class="flex mt-10">
    <div class="w-full md:w-2/3 md:mx-auto md:max-w-md">
      <card :title="$t('login')">
        <form @submit.prevent="login" @keydown="form.onKeydown($event)">
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

          <!-- Remember Me -->
          <div class="relative flex items-center mt-8 mb-6">
            <checkbox
              v-model="remember"
              class="w-full md:w-1/2"
              name="remember"
            >
              {{ $t('remember_me') }}
            </checkbox>

            <div class="w-full md:w-1/2 text-right">
              <router-link
                :to="{ name: 'password.request' }"
                class="text-xs font-thin text-gray-500 sm:text-sm hover:text-gray-700"
              >
                {{ $t('forgot_password') }}
              </router-link>
            </div>
          </div>

          <!-- Submit Button -->
          <v-button class="w-full" :loading="form.busy">
            {{ $t('login') }}
          </v-button>

          <!-- GitHub Login Button -->
          <LoginWithGithub class="w-full mt-4" />
        </form>
      </card>
    </div>
  </div>
</template>

<script>
import Form from 'vform';
import Cookies from 'js-cookie';
import LoginWithGithub from '@/components/LoginWithGithub';

export default {
  components: {
    LoginWithGithub
  },

  middleware: 'guest',

  metaInfo() {
    return { title: this.$t('login') };
  },

  data() {
    return {
      form: new Form({
        email: '',
        password: ''
      }),
      remember: false
    };
  },

  methods: {
    async login() {
      // Submit the form.
      const { data } = await this.form.post('/api/login');

      // Save the token.
      await this.$store.dispatch('auth/saveToken', {
        token: data.token,
        remember: this.remember
      });

      // Fetch the user.
      await this.$store.dispatch('auth/fetchUser');

      // Redirect home.
      this.redirect();
    },

    redirect() {
      const intendedUrl = Cookies.get('intended_url');

      if (intendedUrl) {
        Cookies.remove('intended_url');
        this.$router.push({ path: intendedUrl });
      } else {
        this.$router.push({ name: 'home' });
      }
    }
  }
};
</script>
