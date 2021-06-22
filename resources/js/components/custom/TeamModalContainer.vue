<template>
  <div>
    <div ref="teamModalContainer" class="vld-parent">
      <div class="flex justify-between items-center mb-10">
        <span class="text-2xl font-semibold">Team {{ team.name }}</span>

        <div class="flex items-center space-x-3">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-7 w-7 cursor-pointer text-blue-500"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            @click="editTeam"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"
            />
          </svg>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-7 w-7 cursor-pointer text-red-600"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            @click="removeTeam"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
            />
          </svg>
        </div>
      </div>
      <div class="space-y-5">
        <div class="flex justify-between space-x-10">
          <div class="flex flex-1 flex-col">
            <label class="my-3">
              Name
            </label>
            <input
              v-model="team.name"
              type="text"
              class="mb-3 w-full focus:outline-none py-3 px-4 bg-white rounded-lg border focus:outline-none"
              placeholder="New Task List"
            />
          </div>
        </div>
        <div class="flex items-center justify-between space-x-10">
          <div class="flex flex-1 flex-col">
            <label class="my-3">
              Invite People
            </label>
            <input
              v-model="form.email"
              type="text"
              class="mb-3 w-full focus:outline-none py-3 px-4 bg-white rounded-lg border focus:outline-none"
              placeholder="Invite Email"
              @input="clearError(errors.email)"
            />
            <div v-if="errors.email" class="text-red-500">
              {{ errors.email[0] }}
            </div>
          </div>
          <div class="flex flex-col mt-9">
            <button
              class="btn py-3 px-4
  bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 focus:ring-offset-blue-200 text-white transition ease-in duration-200 text-center text-base font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 rounded-lg"
              @click="inviteToTeam"
            >
              Invite to team
            </button>
          </div>
        </div>
        <div class="mt-10">
          <div class="text-xl font-semibold my-4">
            Members
          </div>
          <ul class="space-y-2">
            <li
              v-for="teamUser in team.users"
              :key="teamUser.id"
              class="flex space-x-2 items-center w-full rounded-lg p-4 border"
            >
              <img
                v-if="teamUser"
                :src="teamUser.photo_url"
                class="rounded-full w-6 h-6"
              />
              <div class="truncate font-semibold">
                {{ teamUser.name }}
              </div>
            </li>
          </ul>
        </div>

        <div class="flex justify-between !mt-10">
          <button
            class="btn py-2 px-4
  bg-red-600 hover:bg-red-700 focus:ring-red-500 focus:ring-offset-red-200 text-white transition ease-in duration-200 text-center text-base font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 rounded-lg"
            @click="$emit('close')"
          >
            Close
          </button>
          <button
            class="btn py-2 px-4
  bg-green-600 hover:bg-green-700 focus:ring-green-500 focus:ring-offset-green-200 text-white transition ease-in duration-200 text-center text-base font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 rounded-lg"
            @click="editTeam"
          >
            Save
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex';
import Form from 'vform';

export default {
  name: 'TeamModalContainer',
  props: ['teamId'],
  data() {
    return {
      form: {
        email: ''
      },
      errors: {
        email: null
      }
    };
  },
  computed: {
    ...mapState('teams', {
      team: 'model'
    }),
    ...mapGetters('auth', {
      user: 'user'
    })
  },
  async mounted() {
    const loader = this.$loading.show({
      loader: 'dots',
      opacity: 0.9,
      container: this.$refs.teamModalContainer
    });

    await this.fetchTeam(this.teamId);

    loader.hide();
  },
  methods: {
    ...mapActions('teams', {
      fetchTeam: 'show',
      fetchTeams: 'index',
      updateTeam: 'update',
      deleteTeam: 'destroy',
      sendInvite: 'sendInvite'
    }),
    clearError(error) {
      if (!error) return;

      error = null;
    },
    async editTeam() {
      await this.updateTeam(this.team);

      await this.fetchTeam(this.teamId);
    },
    async removeTeam() {
      await this.deleteTeam(this.teamId);

      await this.fetchTeams();

      this.$emit('close');
    },
    async inviteToTeam() {
      const res = await this.sendInvite({
        id: this.teamId,
        form: this.form
      });

      if (res.errors) {
        this.errors.email = res.errors.email;
      } else {
        this.errors.email = null;
      }
    }
  }
};
</script>

<style scoped></style>
