<template>
  <div class="grid grid-cols-6 gap-5 pt-5">
    <card v-for="team in teams" :key="team.id" class="w-64 h-32 cursor-pointer">
      <router-link :to="{ name: 'teams.show', params: { id: team.id } }">
        <div class="text-2xl font-bold">{{ team.name }}</div>
        <div class="font-light">
          Created by <span class="font-semibold">{{ team.owner.name }}</span>
        </div>
      </router-link>
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
    <!--    <draggable
      v-model="sections"
      v-bind="dragSectionListOptions"
      class="flex flex-col space-y-3 p-10 hover:border hover:rounded-xl"
    >
      <template #item="{ element }">
        <div class="text-xl font-semibold mb-3">
          {{ element.name }}
        </div>
        <draggable
          v-model="taskInputs"
          class="h-screen overflow-y-auto"
          v-bind="dragTaskListOptions"
        >
          <template #item="{ element }">
            <li class="cursor-pointer bg-white w-72 rounded-lg p-4 mb-3 border">
              {{ element.name }}
            </li>
            -*
          </template>
          <template #footer>
            <div class="flex flex-col">
              <input
                v-show="selectedSectionId === section.id"
                :ref="
                  el => {
                    taskInputs[section.id] = el;
                  }
                "
                v-model="task"
                type="text"
                class="mb-3 w-72 focus:outline-none py-3 px-4 bg-white rounded-lg border focus:outline-none"
                placeholder="New Task"
                @focusout="selectedSectionId = null"
                @keydown.enter="addTask(section)"
              />

              <button
                class="flex text-gray-800 items-center justify-center cursor-pointer focus:outline-none bg-white w-72 rounded-lg p-2 mb-3 border"
                @click="displayAddTaskInput(section.id)"
              >
                <svg
                  class="w-6 h-6"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                >
                  <path
                    fill-rule="evenodd"
                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                    clip-rule="evenodd"
                  />
                </svg>
                <span class="text-sm font-medium">Add Task</span>
              </button>
            </div>
          </template>
        </draggable>
      </template>
    </draggable>
    <div
      class="flex flex-col space-y-3 p-10 w-96 hover:border hover:rounded-xl"
    >
      <h3 class="flex items-center">
        <span
          v-if="isAddingSection === false"
          class="underline font-light"
          @click="displayAddSectionInput"
          >+ Add Section</span
        >
      </h3>
      <input
        v-if="isAddingSection === true"
        ref="addSection"
        v-model="section"
        type="text"
        class="mb-3 w-72 focus:outline-none py-3 px-4 bg-white rounded-lg border focus:outline-none"
        placeholder="New Section"
        @focusout="addSection"
        @keydown.enter="addSection"
      />
    </div>-->
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

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
