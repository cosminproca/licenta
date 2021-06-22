<template>
  <div>
    <div ref="taskModalContainer" class="vld-parent">
      <div class="flex justify-between items-center mb-10">
        <div class="flex items-center space-x-2">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-7 w-7 cursor-pointer"
            :class="
              task.completed
                ? 'text-green-400 hover:text-gray-400'
                : 'text-gray-400 hover:text-green-400'
            "
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
          <span class="text-2xl font-semibold">Task #{{ task.id }}</span>
        </div>

        <div class="flex items-center space-x-3">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-7 w-7 cursor-pointer text-blue-500"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            @click="editTask"
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
            class="h-7 w-7 cursor-pointer text-gray-700"
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
            class="h-7 w-7 cursor-pointer text-red-600"
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
                <span> {{ name }} </span>
              </template>
              <template #selected-option="{ name }">
                <span> {{ name }} </span>
              </template>
            </v-select>
          </div>
        </div>
        <div class="flex justify-between space-x-10">
          <div class="flex flex-col flex-1">
            <label class="my-3">
              Name
            </label>
            <input
              v-model="task.name"
              type="text"
              class="mb-3 w-full py-3 px-4 bg-white rounded-lg border focus:outline-none"
              placeholder="New Task List"
            />
          </div>
          <div class="flex flex-col flex-1">
            <label class="my-3">
              Due Date
            </label>
            <Datepicker
              v-model="task.due_date"
              class="w-full"
              input-class="px-4 py-3 bg-white rounded-lg border focus:outline-none w-full"
              format="YYYY-MM-DD"
              value-type="format"
            />
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
        <div class="flex flex-col">
          <div class="text-xl font-semibold my-3">
            Sub Tasks
          </div>
          <draggable
            v-model="task.sub_tasks"
            v-bind="dragSubTaskOptions"
            class="flex flex-col space-y-4 h-full overflow-y-auto w-72"
            @update="updateTaskSubTasks"
          >
            <li
              v-for="subTask in task.sub_tasks"
              :key="subTask.id"
              class="cursor-pointer draggableItem h-24 bg-white flex justify-between items-center w-full rounded-lg py-3 px-4 border"
              @click="showSubTaskModal(subTask.id)"
            >
              <div class="flex flex-col w-full space-y-4">
                <div class="flex items-center justify-between">
                  <div class="flex items-center w-48">
                    <span class="truncate">
                      #{{ subTask.id }}: {{ subTask.name }}
                    </span>
                  </div>

                  <div class="flex items-center space-x-2">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-5 w-5 cursor-pointer"
                      :class="
                        subTask.completed
                          ? 'text-green-400 hover:text-gray-400'
                          : 'text-gray-400 hover:text-green-400'
                      "
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      @click.stop="changeSubTaskStatus(subTask)"
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
                      class="h-5 w-5 cursor-pointer text-red-600"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      @click.stop="removeSubTask(subTask.id)"
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
                <div class="flex items-center justify-between">
                  <img
                    v-if="subTask.assignee"
                    :src="subTask.assignee.photo_url"
                    class="rounded-full w-7 h-7"
                  />
                  <div
                    v-if="subTask.due_date && selectedSubTaskId !== subTask.id"
                    class="font-light text-sm w-full flex justify-end"
                    :class="
                      subTask.due_date > currentDay
                        ? 'text-gray-400 hover:text-gray-700'
                        : 'text-red-500 hover:text-red-700'
                    "
                    @click.stop="displayChangeDateInput(subTask.id)"
                  >
                    {{ subTask.due_date }}
                  </div>
                  <div v-else class="w-full flex justify-end">
                    <svg
                      v-if="selectedSubTaskId !== subTask.id"
                      xmlns="http://www.w3.org/2000/svg"
                      class="h-5 w-5 cursor-pointer text-gray-400 hover:text-gray-700"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      @click.stop="displayChangeDateInput(subTask.id)"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                      />
                    </svg>
                    <div v-else class="flex justify-end" @click.stop>
                      <Datepicker
                        :ref="`subTaskDateInputs-${subTask.id}`"
                        v-model="subTask.due_date"
                        class="w-36"
                        input-class="px-2 py-1 bg-white rounded-lg border focus:outline-none w-full"
                        format="YYYY-MM-DD"
                        value-type="format"
                        @change="changeSubTaskDate(subTask)"
                        @close="selectedSubTaskId = null"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </li>
            <div slot="footer" class="flex flex-col w-full mt-4">
              <input
                v-show="selectedTaskId === task.id"
                :ref="`subTaskFooterInputs-${task.id}`"
                v-model="subTaskModel.name"
                type="text"
                class="w-full mb-4 focus:outline-none py-3 px-4 bg-white rounded-lg border focus:outline-none"
                placeholder="New Sub Task"
                @focusout="selectedTaskId = null"
                @keydown.enter="addSubTask(task)"
                @blur="addSubTask(task)"
              />

              <button
                class="flex mb-4 text-gray-800 items-center justify-center cursor-pointer focus:outline-none bg-white w-full rounded-lg p-2 border"
                @click="displayFooterAddSubTaskInput(task.id)"
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
                <span class="text-sm font-medium">Add Sub Task</span>
              </button>
            </div>
          </draggable>
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
      <div class="mt-10">
        <div class="text-xl font-semibold my-4">
          Comments
        </div>
        <ul class="space-y-4">
          <li
            v-for="comment in comments"
            :key="comment.id"
            :class="
              comment.user.id === user.id
                ? 'bg-blue-300 text-white'
                : 'bg-gray-200'
            "
            class="flex flex-col w-full rounded-lg p-4 border"
          >
            <div
              class="flex items-center space-x-2 py-2"
              :class="
                comment.user.id === user.id
                  ? 'border-white border-b'
                  : 'border-black border-b'
              "
            >
              <img
                v-if="comment.user"
                :src="comment.user.photo_url"
                class="rounded-full w-6 h-6"
              />
              <div class="truncate font-semibold">
                {{ comment.user.name }}
              </div>
            </div>
            <div class="mt-3 font-medium" v-html="comment.text" />
            <div class="font-light italic text-right mt-3">
              Sent at {{ formatDate(comment.created_at) }}
            </div>
          </li>
        </ul>
      </div>
      <div
        class="mt-10 flex flex-col border rounded-xl h-96 border-gray-300 p-4"
      >
        <div class="flex h-full">
          <img :src="user.photo_url" class="rounded-full w-9 h-9 mr-3" />
          <editor
            v-model="commentModel.text"
            :options="options"
            class="w-full h-52"
            theme="snow"
          />
        </div>
        <div class="flex justify-end">
          <button
            class="btn py-2 px-4
  bg-green-600 hover:bg-green-700 focus:ring-green-500 focus:ring-offset-green-200 text-white transition ease-in duration-200 text-center text-base font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 rounded-lg"
            @click="sendComment"
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
import { format } from 'date-fns';
import SubTaskModalContainer from '@/components/custom/SubTaskModalContainer';

export default {
  name: 'TaskModalContainer',
  data() {
    return {
      commentModel: {
        task_id: 0,
        team_id: 0,
        user_id: 0,
        text: ''
      },
      subTaskModel: {
        task_id: 0,
        team_id: 0,
        name: '',
        tags: []
      },
      selectedTaskId: null,
      selectedSubTaskId: null,
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
    ...mapState('sub_tasks', {
      sub_task: 'model'
    }),
    ...mapState('teams', {
      team: 'model'
    }),
    ...mapGetters('comments', {
      comments: 'dataArray'
    }),
    ...mapGetters('auth', {
      user: 'user'
    }),
    currentDay() {
      return format(new Date(), 'yyyy-MM-dd');
    },
    dragSubTaskOptions() {
      return {
        animation: 200,
        draggable: '.draggableItem',
        disabled: false,
        ghostClass: 'moving-card',
        itemKey: 'id',
        tag: 'ul',
        group: 'sub_tasks'
      };
    }
  },
  async mounted() {
    const loader = this.$loading.show({
      loader: 'dots',
      opacity: 0.9,
      container: this.$refs.taskModalContainer
    });

    await this.fetchComments({
      teamId: this.$route.params.id,
      taskId: this.$route.params.taskId
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
      deleteTask: 'destroy',
      updateAllTaskSubTasks: 'updateAll'
    }),
    ...mapActions('comments', {
      updateComment: 'update',
      fetchComments: 'index',
      deleteComments: 'destroy',
      createComment: 'store'
    }),
    ...mapActions('sub_tasks', {
      createSubTask: 'store',
      updateSubTask: 'update',
      fetchSubTask: 'show',
      deleteSubTask: 'destroy'
    }),
    ...mapActions('task_lists', {
      fetchTaskLists: 'index'
    }),
    updateTaskSubTasks: _.debounce(async function() {
      const subTasks = this.task.sub_tasks.map((subTask, index) => {
        return {
          ...subTask,
          order_column: index + 1
        };
      });

      await this.updateAllTaskSubTasks({
        teamId: this.$route.params.id,
        id: this.$route.params.taskId,
        subTasks: subTasks
      });
    }, 100),
    formatDate(date) {
      return format(new Date(date), 'yyyy-MM-dd HH:mm');
    },
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
    async showSubTaskModal(subTaskId) {
      this.$sidebarModal.show(
        SubTaskModalContainer,
        {
          subTaskId
        },
        {
          width: 800
        },
        {
          'before-open': () => {},
          'before-close': () => {}
        }
      );
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
    displayChangeDateInput(subTaskId) {
      if (this.selectedSubTaskId === subTaskId) {
        this.selectedSubTaskId = null;
        return;
      }

      this.selectedSubTaskId = subTaskId;
    },
    async changeSubTaskDate(subTask) {
      await this.updateSubTask({
        form: subTask,
        teamId: this.$route.params.id,
        taskId: this.$route.params.taskId
      });

      this.selectedSubTaskId = null;

      await this.fetchTask({
        id: this.$route.params.taskId,
        teamId: this.$route.params.id
      });
    },
    async removeSubTask(subTaskId) {
      await this.deleteSubTask({
        id: subTaskId,
        teamId: this.$route.params.id,
        taskId: this.$route.params.taskId
      });

      await this.fetchTask({
        id: this.$route.params.taskId,
        teamId: this.$route.params.id
      });
    },
    async changeTaskStatus(task) {
      await this.updateTask({
        form: { ...task, completed: !task.completed },
        teamId: this.$route.params.id
      });

      await this.fetchTask({
        id: this.$route.params.taskId,
        teamId: this.$route.params.id
      });
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
    },
    async addSubTask(subTask) {
      if (this.subTaskModel.name === '') {
        return;
      }

      await this.createSubTask({
        form: {
          ...this.subTaskModel,
          team_id: this.$route.params.id,
          task_id: subTask.id
        },
        taskId: this.$route.params.taskId,
        teamId: this.$route.params.id
      });

      await this.fetchTask({
        id: this.$route.params.taskId,
        teamId: this.$route.params.id
      });

      this.subTaskModel = {
        task_id: this.$route.params.taskId,
        team_id: this.$route.params.id,
        name: '',
        tags: []
      };
    },
    displayFooterAddSubTaskInput(taskId) {
      if (this.selectedTaskId === taskId) {
        this.selectedTaskId = null;
        return;
      }
      this.selectedTaskId = taskId;

      this.$nextTick(() => {
        this.$refs[`subTaskFooterInputs-${taskId}`].focus();
      });
    },
    async sendComment() {
      await this.createComment({
        teamId: this.$route.params.id,
        taskId: this.$route.params.taskId,
        form: {
          ...this.commentModel,
          user_id: this.user.id,
          team_id: this.$route.params.id,
          task_id: this.$route.params.taskId
        }
      });

      await this.fetchComments({
        teamId: this.$route.params.id,
        taskId: this.$route.params.taskId
      });

      this.commentModel = {
        text: '',
        user_id: this.user.id,
        team_id: this.$route.params.id,
        task_id: this.$route.params.taskId
      };
    }
  }
};
</script>

<style scoped>
.mx-input {
  @apply py-5 px-4;
}
</style>
