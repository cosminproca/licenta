<template>
  <div class="grid grid-cols-6 gap-5 pt-5">
    <card v-for="team in teams" :key="team.id" class="w-64 h-32 cursor-pointer">
      <div
        class="flex flex-col space-y-2"
        @click="$router.push({ name: 'teams.show', params: { id: team.id } })"
      >
        <div class="flex items-center justify-between">
          <div class="text-2xl font-bold">{{ team.name }}</div>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6 cursor-pointer"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            @click.stop="showTaskModal(team.id)"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"
            />
          </svg>
        </div>
        <div class="font-light">
          Created by <span class="font-semibold">{{ team.owner.name }}</span>
        </div>
      </div>
    </card>
    <div
      class="flex flex-col space-y-3 p-10 w-64 hover:border hover:rounded-xl"
    >
      <h3 class="flex items-center">
        <span
          v-if="isAddingTeam === false"
          class="underline font-light"
          @click="displayAddTeamInput"
        >
          + Add New Team
        </span>
      </h3>
      <input
        v-if="isAddingTeam === true"
        ref="addTeam"
        v-model="teamModel.name"
        type="text"
        class="mb-3 w-42 focus:outline-none py-3 px-4 bg-white rounded-lg border focus:outline-none"
        placeholder="New Team"
        @focusout="addTeam"
        @keydown.enter="addTeam"
      />
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import TeamModalContainer from '@/components/custom/TeamModalContainer.vue';

export default {
  name: 'Dashboard',
  data() {
    return {
      teamModel: {
        owner_id: 0,
        name: ''
      },
      isAddingTeam: false
    };
  },
  computed: {
    ...mapGetters('teams', {
      teams: 'dataArray'
    }),
    ...mapGetters('auth', {
      user: 'user'
    })
  },
  async mounted() {
    await this.fetchTeams();
  },
  methods: {
    ...mapActions('teams', {
      fetchTeams: 'index',
      createTeam: 'store'
    }),
    displayAddTeamInput() {
      this.isAddingTeam = true;

      this.$nextTick(() => {
        this.$refs.addTeam.focus();
      });
    },
    async showTaskModal(teamId) {
      this.$sidebarModal.show(
        TeamModalContainer,
        {
          teamId
        },
        {
          width: 800
        }
      );
    },
    async addTeam() {
      if (this.teamModel.name === '') {
        this.isAddingTeam = false;
        return;
      }

      await this.createTeam({ ...this.teamModel, owner_id: this.user.id });
      await this.fetchTeams();

      this.teamModel = {
        owner_id: this.user.id,
        name: ''
      };
    }
  }
};
</script>

<style scoped></style>
