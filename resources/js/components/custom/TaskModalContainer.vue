<template>
  <div>
    <div class="flex justify-between items-center mb-3">
      <span class="text-2xl font-semibold">Task #{{ task.id }}</span>
      <svg
        xmlns="http://www.w3.org/2000/svg"
        class="h-5 w-5 cursor-pointer"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        @click="removeTask"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="2"
          d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
        />
      </svg>
    </div>
    <div class="flex flex-col">
      <label class="my-3">
        Name
      </label>
      <input
        v-model="task.name"
        type="text"
        class="mb-3 w-full focus:outline-none py-3 px-4 bg-white rounded-lg border focus:outline-none"
        placeholder="New Task List"
      />
    </div>
    <div class="flex flex-col mb-3">
      <label class="my-3">
        Description
      </label>
      <textarea
        v-model="task.description"
        type="text"
        class="mb-3 w-full py-3 px-4 bg-white rounded-lg border focus:outline-none"
        placeholder="New Task List"
      />
    </div>
    <div class="flex flex-col mb-3">
      <label class="my-3">
        Due Date
      </label>
      <input
        v-model="task.due_date"
        type="date"
        class="mb-3 w-full focus:outline-none py-3 px-4 bg-white rounded-lg border focus:outline-none"
      />
    </div>
    <div class="flex flex-col mb-3">
      <label class="my-3">
        Priority
      </label>
      <select
        v-model="task.priority"
        class="mb-3 w-full py-3 px-4 bg-white rounded-lg border"
      >
        <option :value="null" disabled>Please select an option</option>
        <option :value="TASK_PRIORITY_TYPES.urgent">
          {{ TASK_PRIORITY_TYPES.urgent.toUpperCase() }}
        </option>
        <option :value="TASK_PRIORITY_TYPES.high">
          {{ TASK_PRIORITY_TYPES.high.toUpperCase() }}
        </option>
        <option :value="TASK_PRIORITY_TYPES.medium"
          >{{ TASK_PRIORITY_TYPES.medium.toUpperCase() }}
        </option>
        <option :value="TASK_PRIORITY_TYPES.low">
          {{ TASK_PRIORITY_TYPES.low.toUpperCase() }}
        </option>
      </select>
    </div>
    <div class="flex justify-between">
      <button
        class="btn py-2 px-4
  bg-green-600 hover:bg-green-700 focus:ring-green-500 focus:ring-offset-green-200 text-white transition ease-in duration-200 text-center text-base font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 rounded-lg"
        @click="editTask"
      >
        Save
      </button>
      <button
        class="btn py-2 px-4
  bg-red-600 hover:bg-red-700 focus:ring-red-500 focus:ring-offset-red-200 text-white transition ease-in duration-200 text-center text-base font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 rounded-lg"
        @click="$emit('close')"
      >
        Close
      </button>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex';
import { TASK_PRIORITY_TYPES } from '@/enums';

export default {
  name: 'TaskModalContainer',
  data() {
    return {
      TASK_PRIORITY_TYPES
    };
  },
  computed: {
    ...mapState('tasks', {
      task: 'model'
    })
  },
  methods: {
    ...mapActions('tasks', {
      updateTask: 'update',
      deleteTask: 'destroy'
    }),
    ...mapActions('task_lists', {
      fetchTaskLists: 'index'
    }),
    editTask() {
      this.updateTask({ form: this.task, teamId: this.$route.params.id });
      this.fetchTaskLists({ teamId: this.$route.params.id });
    },
    removeTask() {
      this.deleteTask({ id: this.task.id, teamId: this.$route.params.id });
      this.$emit('close');
      this.fetchTaskLists({ teamId: this.$route.params.id });
    }
  }
};
</script>

<style scoped></style>
