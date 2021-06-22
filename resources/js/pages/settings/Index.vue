<template>
  <div class="flex mt-6 md:max-w-2xl md:mx-auto">
    <div class="w-full md:w-1/3 md:pr-4">
      <card :padding="false">
        <ul>
          <li v-for="tab in tabs" :key="tab.route">
            <router-link
              :to="{ name: tab.route }"
              class="px-6 py-4 flex items-center text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded"
              active-class="text-blue-600 bg-blue-50 hover:bg-blue-50"
            >
              <template v-if="tab.route == 'settings.profile'">
                <svg
                  class="w-6 h-6 mr-2"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
              </template>
              <template v-else-if="tab.route == 'settings.password'">
                <svg
                  class="w-6 h-6 mr-2"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke="currentColor"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                  />
                </svg>
              </template>
              <span class="ml-2">
                {{ tab.name }}
              </span>
            </router-link>
          </li>
        </ul>
      </card>
    </div>

    <div class="w-full md:w-2/3">
      <transition name="fade" mode="out-in">
        <router-view />
      </transition>
    </div>
  </div>
</template>

<script>
export default {
  middleware: 'auth',

  computed: {
    tabs() {
      return [
        {
          name: this.$t('profile'),
          route: 'settings.profile'
        },
        {
          name: this.$t('password'),
          route: 'settings.password'
        }
      ];
    }
  }
};
</script>
