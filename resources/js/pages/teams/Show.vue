<template>
  <div class="flex flex-1 space-x-16 p-10 overflow-x-scroll">
    <draggable
      v-model="task_lists"
      v-bind="dragTaskListOptions"
      class="flex space-x-16"
      @change="updateTaskLists"
    >
      <card
        v-for="task_list in task_lists"
        :key="task_list.id"
        class="w-96 max-h-[calc(100vh-160px)] draggableItem hover:border hover:rounded-xl"
      >
        <div
          v-if="editedTaskListId !== task_list.id"
          class="flex justify-between items-center mb-10"
          @click="displayEditTaskListInput(task_list.id)"
        >
          <span class="text-xl font-semibold truncate">
            {{ task_list.name }}
          </span>
          <div class="flex items-center">
            <svg
              class="w-6 h-6 cursor-pointer mr-2"
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 20 20"
              fill="currentColor"
              @click.stop="displayHeaderAddTaskInput(task_list.id)"
            >
              <path
                fill-rule="evenodd"
                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                clip-rule="evenodd"
              />
            </svg>

            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6 cursor-pointer text-red-600"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
              @click.stop="removeTaskList(task_list.id)"
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
        <input
          v-if="editedTaskListId === task_list.id"
          :ref="`taskListInputs-${task_list.id}`"
          v-model="task_list.name"
          type="text"
          class="mb-4 w-full focus:outline-none py-3 px-4 mb-10 bg-white rounded-lg border focus:outline-none"
          placeholder="Task List"
          @focusout="editedTaskListId = null"
          @keydown.enter="editTaskList(task_list.id)"
        />
        <draggable
          v-model="task_list.tasks"
          v-bind="dragTaskOptions"
          class="flex flex-col space-y-4 h-full overflow-y-auto overflow-x-hidden"
          @change="updateTaskLists"
        >
          <li
            v-for="task in task_list.tasks"
            :key="task.id"
            class="cursor-pointer draggableItem h-24 bg-white flex justify-between items-center w-full rounded-lg py-3 px-4 border"
            @click="showTaskModal(task.id)"
          >
            <div class="flex flex-col w-full space-y-4">
              <div class="flex items-center justify-between">
                <div class="flex items-center w-52">
                  <span class="truncate">
                    #{{ task.id }}: {{ task.name }}
                  </span>
                </div>

                <div class="flex items-center space-x-2">
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 cursor-pointer"
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

                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 cursor-pointer text-red-600"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    @click.stop="removeTask(task.id)"
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
                  v-if="task.assignee"
                  :src="task.assignee.photo_url"
                  class="rounded-full w-7 h-7"
                />
                <div
                  v-if="task.due_date && selectedTaskId !== task.id"
                  class="font-light text-sm w-full flex justify-end"
                  :class="
                    task.due_date > currentDay
                      ? 'text-gray-400 hover:text-gray-700'
                      : 'text-red-500 hover:text-red-700'
                  "
                  @click.stop="displayChangeDateInput(task.id)"
                >
                  {{ task.due_date }}
                </div>
                <div v-else class="w-full flex justify-end">
                  <svg
                    v-if="selectedTaskId !== task.id"
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5 cursor-pointer text-gray-400 hover:text-gray-700"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    @click.stop="displayChangeDateInput(task.id)"
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
                      :ref="`taskDateInputs-${task.id}`"
                      v-model="task.due_date"
                      class="w-36"
                      input-class="px-2 py-1 bg-white rounded-lg border focus:outline-none w-full"
                      format="YYYY-MM-DD"
                      value-type="format"
                      @change="changeDate(task)"
                      @close="selectedTaskId = null"
                    />
                  </div>
                </div>
              </div>
            </div>
          </li>
          <div slot="header">
            <input
              v-if="headerSelectedTaskListId === task_list.id"
              :ref="`taskHeaderInputs-${task_list.id}`"
              v-model="taskModel.name"
              type="text"
              class="w-full focus:outline-none py-3 px-4 bg-white rounded-lg border focus:outline-none"
              placeholder="New Task"
              @focusout="headerSelectedTaskListId = null"
              @keydown.enter="addTask(task_list)"
              @blur="addTask(task_list)"
            />
          </div>
          <div slot="footer" class="flex flex-col w-full mt-4">
            <input
              v-show="selectedTaskListId === task_list.id"
              :ref="`taskFooterInputs-${task_list.id}`"
              v-model="taskModel.name"
              type="text"
              class="w-full mb-4 focus:outline-none py-3 px-4 bg-white rounded-lg border focus:outline-none"
              placeholder="New Task"
              @focusout="selectedTaskListId = null"
              @keydown.enter="addTask(task_list)"
              @blur="addTask(task_list)"
            />

            <button
              class="flex mb-4 text-gray-800 items-center justify-center cursor-pointer focus:outline-none bg-white w-full rounded-lg p-2 border"
              @click="displayFooterAddTaskInput(task_list.id)"
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
        </draggable>
      </card>
      <div
        slot="footer"
        class="flex flex-col p-10 w-96 hover:border hover:rounded-xl"
      >
        <h3 class="flex items-center w-96">
          <span
            v-if="isAddingTaskList === false"
            class="underline font-light"
            @click="displayAddTaskListInput"
          >
            + Add New Task List
          </span>
        </h3>
        <input
          v-if="isAddingTaskList === true"
          ref="addTaskList"
          v-model="taskList.name"
          type="text"
          class="mb-3 w-78 focus:outline-none py-3 px-4 bg-white rounded-lg border focus:outline-none"
          placeholder="New Task List"
          @focusout="addTaskList"
          @keydown.enter="addTaskList"
        />
      </div>
    </draggable>
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex';
import TaskModalContainer from '@/components/custom/TaskModalContainer.vue';
import { format } from 'date-fns';

export default {
  name: 'Show',
  middleware: 'auth',
  metaInfo() {
    return { title: this.$t('home') };
  },
  data() {
    return {
      isLoading: false,
      selectedTaskListId: null,
      headerSelectedTaskListId: null,
      selectedTaskId: null,
      editedTaskListId: null,
      taskList: {
        team_id: 0,
        name: ''
      },
      isAddingTaskList: false,
      taskModel: {
        task_list_id: 0,
        team_id: 0,
        name: '',
        tags: []
      }
    };
  },
  computed: {
    ...mapState('tasks', {
      taskItem: 'model'
    }),
    task_lists: {
      get() {
        return this.$store.getters['task_lists/dataArray'];
      },
      set(value) {
        this.$store.commit('task_lists/SET_DATA', value);
      }
    },
    currentDay() {
      return format(new Date(), 'yyyy-MM-dd');
    },
    dragTaskListOptions() {
      return {
        animation: 200,
        draggable: '.draggableItem',
        disabled: false,
        ghostClass: 'moving-card',
        itemKey: 'id',
        tag: 'ul',
        group: 'task_lists'
      };
    },
    dragTaskOptions() {
      return {
        animation: 200,
        draggable: '.draggableItem',
        disabled: false,
        ghostClass: 'moving-card',
        itemKey: 'id',
        tag: 'ul',
        group: 'tasks'
      };
    }
  },
  async mounted() {
    const loader = this.$loading.show({
      loader: 'dots',
      opacity: 0.9
    });

    await this.fetchTaskLists({ teamId: this.$route.params.id });
    await this.fetchTeam(this.$route.params.id);

    loader.hide();

    if (this.$route.params.taskId) {
      await this.showTaskModal(this.$route.params.taskId);
    }
  },
  methods: {
    ...mapActions('task_lists', {
      fetchTaskLists: 'index',
      createTaskList: 'store',
      updateTaskList: 'update',
      updateAllTaskLists: 'updateAll',
      deleteTaskList: 'destroy'
    }),
    ...mapActions('tasks', {
      createTask: 'store',
      updateTask: 'update',
      fetchTask: 'show',
      deleteTask: 'destroy'
    }),
    ...mapActions('teams', {
      fetchTeam: 'show'
    }),
    updateTaskLists: _.debounce(async function() {
      const newTaskLists = this.task_lists.map((taskList, index) => {
        return {
          ...taskList,
          order_column: index + 1,
          tasks: taskList.tasks.map((task, index) => {
            return { ...task, order_column: index + 1 };
          })
        };
      });

      await this.updateAllTaskLists({
        teamId: this.$route.params.id,
        taskLists: newTaskLists
      });
    }, 100),
    getTaskList(taskListId) {
      return this.task_lists.find(taskList => taskList.id === taskListId);
    },
    async showTaskModal(taskId) {
      const path = `/teams/${this.$route.params.id}`;

      if (!this.$route.params.taskId) {
        await this.$router.replace(`${path}/tasks/${taskId}`);
      }

      this.$sidebarModal.show(
        TaskModalContainer,
        {},
        {
          width: 800
        },
        {
          'before-close': () => {
            this.$router.replace(path);
          }
        }
      );
    },
    displayAddTaskListInput() {
      this.isAddingTaskList = true;

      this.$nextTick(() => {
        this.$refs.addTaskList.focus();
      });
    },
    displayEditTaskListInput(taskListId) {
      if (this.editedTaskListId === taskListId) {
        this.editedTaskListId = null;
        return;
      }

      this.editedTaskListId = taskListId;

      this.$nextTick(() => {
        this.$refs[`taskListInputs-${taskListId}`][0].focus();
      });
    },
    displayHeaderAddTaskInput(taskListId) {
      if (this.headerSelectedTaskListId === taskListId) {
        this.headerSelectedTaskListId = null;
        return;
      }
      this.headerSelectedTaskListId = taskListId;

      this.$nextTick(() => {
        this.$refs[`taskHeaderInputs-${taskListId}`][0].focus();
      });
    },
    displayFooterAddTaskInput(taskListId) {
      if (this.selectedTaskListId === taskListId) {
        this.selectedTaskListId = null;
        return;
      }
      this.selectedTaskListId = taskListId;

      this.$nextTick(() => {
        this.$refs[`taskFooterInputs-${taskListId}`][0].focus();
      });
    },
    removeTaskList(id) {
      this.$swal({
        title: 'Remove this task list?',
        text: "Are you sure? You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc0c51',
        confirmButtonText: 'Yes, Delete it!'
      }).then(async result => {
        if (result.value) {
          await this.deleteTaskList({ teamId: this.$route.params.id, id });

          await this.fetchTaskLists({ teamId: this.$route.params.id });
        }
      });
    },
    async editTaskList(id) {
      const taskList = this.task_lists.find(taskList => taskList.id === id);

      await this.updateTaskList({
        teamId: this.$route.params.id,
        form: taskList
      });

      this.editedTaskListId = null;

      await this.fetchTaskLists({ teamId: this.$route.params.id });
    },
    displayChangeDateInput(taskId) {
      if (this.selectedTaskId === taskId) {
        this.selectedTaskId = null;
        return;
      }

      this.selectedTaskId = taskId;
    },
    async changeDate(task) {
      await this.updateTask({
        form: task,
        teamId: this.$route.params.id
      });

      this.selectedTaskId = null;

      await this.fetchTaskLists({ teamId: this.$route.params.id });
    },
    async addTask(taskList) {
      if (this.taskModel.name === '') {
        return;
      }

      await this.createTask({
        form: {
          ...this.taskModel,
          team_id: this.$route.params.id,
          task_list_id: taskList.id
        },
        teamId: this.$route.params.id
      });

      if (this.headerSelectedTaskListId !== null) {
        this.getTaskList(this.headerSelectedTaskListId).tasks.unshift(
          this.taskItem
        );

        await this.updateTaskLists();
      } else {
        await this.fetchTaskLists({ teamId: this.$route.params.id });
      }

      this.taskModel = {
        task_list_id: taskList.id,
        team_id: this.$route.params.id,
        name: '',
        tags: []
      };
    },
    async changeTaskStatus(task) {
      await this.updateTask({
        form: { ...task, completed: !task.completed },
        teamId: this.$route.params.id
      });

      await this.fetchTaskLists({ teamId: this.$route.params.id });
    },
    async removeTask(id) {
      await this.deleteTask({ id, teamId: this.$route.params.id });

      await this.fetchTaskLists({ teamId: this.$route.params.id });
    },
    async addTaskList() {
      if (this.taskList.name === '') {
        this.isAddingTaskList = false;
        return;
      }

      await this.createTaskList({
        form: { ...this.taskList, team_id: this.$route.params.id },
        teamId: this.$route.params.id
      });

      await this.fetchTaskLists({ teamId: this.$route.params.id });

      this.taskList = {
        team_id: this.$route.params.id,
        name: ''
      };
    }
  }
};
</script>

<style scoped></style>
