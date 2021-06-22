<template>
  <div>
    <div ref="subTaskModalContainer" class="vld-parent">
      <div class="flex justify-between items-center mb-10">
        <div class="flex items-center space-x-2">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-7 w-7 cursor-pointer"
            :class="
              sub_task.completed
                ? 'text-green-400 hover:text-gray-400'
                : 'text-gray-400 hover:text-green-400'
            "
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            @click.stop="changeSubTaskStatus(sub_task)"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
          <span class="text-2xl font-semibold">SubTask #{{ sub_task.id }}</span>
        </div>

        <div class="flex items-center space-x-3">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-7 w-7 cursor-pointer text-blue-500"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            @click="editSubTask"
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
            @click="removeSubTask"
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
              v-model="sub_task.name"
              type="text"
              class="mb-3 w-full focus:outline-none py-3 px-4 bg-white rounded-lg border focus:outline-none"
              placeholder="New Task List"
            />
          </div>
          <div class="flex flex-1 flex-col">
            <label class="my-3">
              Due Date
            </label>
            <Datepicker
              v-model="sub_task.due_date"
              class="w-full"
              input-class="px-4 py-3 bg-white rounded-lg border focus:outline-none w-full"
              format="YYYY-MM-DD"
              value-type="format"
            />
          </div>
        </div>
        <div class="flex justify-between space-x-10">
          <div class="flex flex-1 flex-col">
            <label class="my-3">
              Assignee
            </label>
            <v-select
              v-model="sub_task.assignee_id"
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
        </div>
        <div class="flex flex-col">
          <label class="my-3">
            Description
          </label>
          <editor
            v-model="sub_task.description"
            :options="options"
            theme="snow"
          />
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
            @click="editSubTask"
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

export default {
  name: 'SubTaskModalContainer',
  props: ['subTaskId'],
  data() {
    return {
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
    ...mapState('sub_tasks', {
      sub_task: 'model'
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
      container: this.$refs.subTaskModalContainer
    });

    await this.fetchSubTask({
      id: this.subTaskId,
      teamId: this.$route.params.id,
      taskId: this.$route.params.taskId
    });

    loader.hide();
  },
  methods: {
    ...mapActions('tasks', {
      updateTask: 'update',
      fetchTask: 'show',
      deleteTask: 'destroy'
    }),
    ...mapActions('sub_tasks', {
      updateSubTask: 'update',
      fetchSubTask: 'show',
      deleteSubTask: 'destroy'
    }),
    ...mapActions('task_lists', {
      fetchTaskLists: 'index'
    }),
    async editSubTask() {
      await this.updateSubTask({
        form: this.sub_task,
        teamId: this.$route.params.id,
        taskId: this.$route.params.taskId
      });

      await this.fetchTask({
        id: this.$route.params.taskId,
        teamId: this.$route.params.id
      });
    },
    async removeSubTask() {
      await this.deleteSubTask({
        id: this.subTaskId,
        teamId: this.$route.params.id,
        taskId: this.$route.params.taskId
      });

      await this.fetchTask({
        id: this.$route.params.taskId,
        teamId: this.$route.params.id
      });

      this.$emit('close');
    },
    async changeSubTaskStatus(subTask) {
      await this.updateSubTask({
        form: { ...subTask, completed: !subTask.completed },
        teamId: this.$route.params.id,
        taskId: this.$route.params.taskId
      });

      await this.fetchTask({
        id: this.$route.params.taskId,
        teamId: this.$route.params.id
      });
    }
  }
};
</script>

<style scoped></style>
