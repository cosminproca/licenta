<template>
  <nav class="bg-white dark:bg-gray-800  shadow ">
    <div class="max-w-7xl mx-auto px-8">
      <div class="flex items-center justify-between h-16">
        <div class="flex items-center">
          <router-link
            :to="{ name: user ? 'home' : 'welcome' }"
            class="flex-shrink-0 md:text-2xl"
          >
            {{ appName }}
          </router-link>
          <LocaleDropdown class="md:ml-5" />
        </div>
        <div class="block">
          <div class="ml-4 flex items-center md:ml-6">
            <div class="ml-3 relative">
              <div class="relative inline-block text-left">
                <Dropdown v-if="user" dusk="nav-dropdown">
                  <template #trigger="{toggle}">
                    <button
                      id="dropdown-menu-button"
                      type="button"
                      class="flex items-center justify-center w-full rounded-md px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-50 hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-gray-500"
                      dusk="nav-dropdown-button"
                      @click.prevent="toggle()"
                    >
                      <img :src="user.photo_url" class="rounded-full w-6 h-6" />
                      <p class="ml-2">{{ user.name }}</p>
                    </button>
                  </template>

                  <router-link
                    :to="{ name: 'settings.profile' }"
                    class="block block px-4 py-2 text-md text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-100 dark:hover:text-white dark:hover:bg-gray-600 flex items-center"
                  >
                    <svg
                      class="w-4 h-4 mr-2"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                      />
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                      />
                    </svg>
                    {{ $t('settings') }}
                  </router-link>

                  <a
                    href="#"
                    class="block block px-4 py-2 text-md text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-100 dark:hover:text-white dark:hover:bg-gray-600 flex items-center"
                    @click.prevent="logout"
                  >
                    <svg
                      class="w-4 h-4 mr-2"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                      />
                    </svg>
                    {{ $t('logout') }}
                  </a>
                </Dropdown>
                <div v-else>
                  <router-link
                    :to="{ name: 'login' }"
                    class="text-gray-300 md:text-xl hover:text-gray-800 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium"
                    active-class="text-gray-800 dark:text-white"
                  >
                    {{ $t('login') }}
                  </router-link>
                  <router-link
                    :to="{ name: 'register' }"
                    class="text-gray-300 md:text-xl hover:text-gray-800 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium"
                    active-class="text-gray-800 dark:text-white"
                  >
                    {{ $t('register') }}
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script>
import { mapGetters } from 'vuex';
import LocaleDropdown from './LocaleDropdown';
import Dropdown from './common/Dropdown';

export default {
  components: {
    Dropdown,
    LocaleDropdown
  },

  data() {
    return {
      appName: window.config.appName
    };
  },

  computed: mapGetters({
    user: 'auth/user'
  }),

  methods: {
    async logout() {
      // Log out the user.
      await this.$store.dispatch('auth/logout');

      // Redirect to login.
      this.$router.push({ name: 'login' });
    }
  }
};
</script>
