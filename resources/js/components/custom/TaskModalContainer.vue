<template>
  <div>
    <div ref="taskModalContainer" class="vld-parent">
      <div class="flex justify-between items-center mb-10">
        <span class="text-2xl font-semibold">Task #{{ task.id }}</span>

        <div class="flex items-center space-x-3">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-7 w-7 cursor-pointer"
            :class="task.completed ? 'text-green-400' : 'text-gray-400'"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            @click.stop="changeTaskStatus(task)"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6 cursor-pointer text-gray-700"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            @click="copyLinkToClipBoard"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
            />
          </svg>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6 cursor-pointer text-red-600"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            @click="removeTask"
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
              Assignee
            </label>
            <v-select
              v-model="task.assignee_id"
              :options="team.users"
              :reduce="user => user.id"
              label="name"
            >
              <template #option="{ name , photo_url }">
                <div class="flex items-center py-2">
                  <img :src="photo_url" class="rounded-full w-6 h-6 mr-2" />
                  <span> {{ name }} </span>
                </div>
              </template>
              <template #selected-option="{ name , photo_url }">
                <div class="flex items-center">
                  <img :src="photo_url" class="rounded-full w-6 h-6 mr-2" />
                  <span> {{ name }} </span>
                </div>
              </template>
            </v-select>
          </div>
          <div class="flex flex-col">
            <label class="my-3">
              Due Date
            </label>
            <Datepicker
              v-model="task.due_date"
              format="YYYY-MM-DD"
              value-type="format"
            />
          </div>
        </div>
        <div class="flex justify-between space-x-10">
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
          <div class="flex flex-1 flex-col">
            <label class="my-3">
              Priority
            </label>
            <v-select
              v-model="task.priority"
              :options="task_priorities"
              :reduce="priority => priority.value"
              label="name"
            >
              <template #option="{ name }">
                <span class="ml-2"> {{ name }} </span>
              </template>
              <template #selected-option="{ name }">
                <span class="ml-2"> {{ name }} </span>
              </template>
            </v-select>
          </div>
        </div>
        <div class="flex justify-between space-x-10">
          <div class="flex flex-col flex-1">
            <label class="my-3">
              Time
            </label>
            <input
              v-model="task.time"
              v-mask="'##:##'"
              type="text"
              class="mb-3 w-full focus:outline-none py-3 px-4 bg-white rounded-lg border focus:outline-none"
              placeholder="Time"
            />
          </div>
          <div class="flex flex-col flex-1">
            <label class="my-3">
              Time Estimation
            </label>
            <input
              v-model="task.time_estimation"
              v-mask="'##:##'"
              type="text"
              class="mb-3 w-full focus:outline-none py-3 px-4 bg-white rounded-lg border focus:outline-none"
              placeholder="Time Estimation"
            />
          </div>
        </div>
        <div class="flex flex-col">
          <label class="my-3">
            Description
          </label>
          <editor v-model="task.description" :options="options" theme="snow" />
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
            @click="editTask"
          >
            Save
          </button>
        </div>
      </div>
      <div
        class="mt-10 flex flex-col border rounded-xl h-96 border-gray-300 p-4"
      >
        <div class="flex h-full">
          <img :src="user.photo_url" class="rounded-full w-9 h-9 mr-3" />
          <editor :options="options" class="w-full h-52" theme="snow" />
        </div>
        <div class="flex justify-end">
          <button
            class="btn py-2 px-4
  bg-green-600 hover:bg-green-700 focus:ring-green-500 focus:ring-offset-green-200 text-white transition ease-in duration-200 text-center text-base font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 rounded-lg"
          >
            Comment
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex';
import { TASK_PRIORITY_TYPES } from '@/enums';

/* this.loader = this.$loading.show({
          loader: 'dots',
          opacity: 0.3,
          container: this.$refs.taskModalContainer,
          canCancel: true
        }); */

export default {
  name: 'TaskModalContainer',
  data() {
    return {
      task_priorities: Object.keys(TASK_PRIORITY_TYPES).map(priority => {
        return { name: priority.toUpperCase(), value: priority };
      }),
      options: {
        modules: {
          toolbar: [
            [{ size: [] }],
            [{ header: [1, 2, 3, 4, 5, 6, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ color: [] }, { background: [] }],
            [{ script: 'super' }, { script: 'sub' }],
            [{ header: '1' }, { header: '2' }, 'blockquote', 'code-block'],
            [
              { list: 'ordered' },
              { list: 'bullet' },
              { indent: '-1' },
              { indent: '+1' }
            ],
            ['image', 'video']
          ]
        }
      }
    };
  },
  computed: {
    ...mapState('tasks', {
      task: 'model'
    }),
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
      container: this.$refs.taskModalContainer
    });

    await this.fetchTask({
      id: this.$route.params.taskId,
      teamId: this.$route.params.id
    });

    loader.hide();
  },
  methods: {
    ...mapActions('tasks', {
      updateTask: 'update',
      fetchTask: 'show',
      deleteTask: 'destroy'
    }),
    ...mapActions('task_lists', {
      fetchTaskLists: 'index'
    }),
    copyLinkToClipBoard() {
      const el = document.createElement('textarea');

      el.value = window.location.href;
      el.setAttribute('readonly', '');
      el.style.position = 'absolute';
      el.style.left = '-9999px';

      document.body.appendChild(el);

      const selected =
        document.getSelection().rangeCount > 0
          ? document.getSelection().getRangeAt(0)
          : false;
      el.select();

      document.execCommand('copy');

      document.body.removeChild(el);

      if (selected) {
        document.getSelection().removeAllRanges();
        document.getSelection().addRange(selected);
      }

      this.$toast.info('Copied link to clipboard');
    },
    async editTask() {
      await this.updateTask({ form: this.task, teamId: this.$route.params.id });

      await this.fetchTaskLists({ teamId: this.$route.params.id });
    },
    async removeTask() {
      await this.deleteTask({
        id: this.task.id,
        teamId: this.$route.params.id
      });

      await this.fetchTaskLists({ teamId: this.$route.params.id });

      this.$emit('close');
    },
    async changeTaskStatus(task) {
      await this.updateTask({
        form: { ...task, completed: !task.completed },
        teamId: this.$route.params.id
      });

      await this.fetchTaskLists({ teamId: this.$route.params.id });
    }
  }
};
</script>

<style scoped></style>
