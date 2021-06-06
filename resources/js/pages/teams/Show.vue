<template>
  <div class="flex space-x-16 p-10 overflow-x-auto">
    <draggable
      v-model="task_lists"
      v-bind="dragSectionListOptions"
      class="flex space-x-16"
      @update="updateTaskLists"
    >
      <card
        v-for="task_list in task_lists"
        :key="task_list.id"
        class="w-96 hover:border hover:rounded-xl"
      >
        <div class="flex justify-between items-center mb-10">
          <span class="text-xl font-semibold">{{ task_list.name }}</span>
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 cursor-pointer"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            @click="removeTaskList(task_list.id)"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
            />
          </svg>
        </div>
        <draggable
          v-model="task_list.tasks"
          v-bind="dragTaskListOptions"
          class="flex flex-col space-y-4 overflow-y-auto"
        >
          <li
            v-for="task in task_list.tasks"
            :key="task.id"
            class="cursor-pointer bg-white w-full rounded-lg py-3 px-4 border"
            @click="showTaskModal(task)"
          >
            {{ task.name }}
          </li>
          <div slot="footer" class="flex flex-col mb-4 w-full">
            <input
              v-show="selectedTaskListId === task_list.id"
              :ref="`taskInputs-${task_list.id}`"
              v-model="taskModel.name"
              type="text"
              class="mb-4 w-full focus:outline-none py-3 px-4 bg-white rounded-lg border focus:outline-none"
              placeholder="New Task"
              @focusout="selectedTaskListId = null"
              @keydown.enter="addTask(task_list)"
            />

            <button
              class="flex text-gray-800 items-center justify-center cursor-pointer focus:outline-none bg-white w-full rounded-lg p-2 mb-3 border"
              @click="displayAddTaskInput(task_list.id)"
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
        class="flex flex-col space-y-3 p-10 w-96 hover:border hover:rounded-xl"
      >
        <h3 class="flex items-center">
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
          class="mb-3 w-72 focus:outline-none py-3 px-4 bg-white rounded-lg border focus:outline-none"
          placeholder="New Task List"
          @focusout="addTaskList"
          @keydown.enter="addTaskList"
        />
      </div>
    </draggable>
  </div>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex';
import draggable from 'vuedraggable';
import TaskModalContainer from '@/components/custom/TaskModalContainer.vue';

export default {
  name: 'Show',
  components: {
    draggable
  },
  middleware: 'auth',
  metaInfo() {
    return { title: this.$t('home') };
  },
  data() {
    return {
      selectedTaskListId: null,
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
      },
      isAddingTask: false
    };
  },
  computed: {
    ...mapGetters('task_lists', {
      task_lists: 'dataArray'
    }),
    task_lists: {
      get() {
        return this.$store.getters['task_lists/dataArray'];
      },
      set(value) {
        this.$store.commit('task_lists/SET_DATA', value);
      }
    },
    dragSectionListOptions() {
      return {
        animation: 200,
        disabled: false,
        ghostClass: 'moving-card',
        itemKey: 'id',
        tag: 'ul',
        group: 'task_lists'
      };
    },
    dragTaskListOptions() {
      return {
        animation: 200,
        disabled: false,
        ghostClass: 'moving-card',
        itemKey: 'id',
        tag: 'ul',
        group: 'tasks'
      };
    }
  },
  async mounted() {
    await this.fetchTaskLists({ teamId: this.$route.params.id });
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
      createTask: 'store'
    }),
    ...mapMutations('tasks', {
      setTask: 'SET_MODEL'
    }),
    async updateTaskLists() {
      const newTaskLists = this.task_lists.map((taskList, index) => {
        return { ...taskList, order_column: index + 1 };
      });

      console.log(newTaskLists);
      console.log(this.task_lists);

      /*      await this.updateAllTaskLists({
        teamId: this.$route.params.id,
        taskLists: newTaskLists
      }); */
    },
    showTaskModal(task) {
      this.setTask(task);
      this.$sidebarModal.show(TaskModalContainer);
    },
    displayAddTaskListInput() {
      this.isAddingTaskList = true;

      this.$nextTick(() => {
        this.$refs.addTaskList.focus();
      });
    },
    displayAddTaskInput(taskListId) {
      if (this.selectedTaskListId === taskListId) {
        this.selectedTaskListId = null;
        return;
      }
      this.selectedTaskListId = taskListId;

      this.$nextTick(() => {
        this.$refs[`taskInputs-${taskListId}`][0].focus();
      });
    },
    async removeTaskList(id) {
      await this.deleteTaskList({ teamId: this.$route.params.id, id });
      await this.fetchTaskLists({ teamId: this.$route.params.id });
    },
    async editTaskList(id) {
      const taskList = this.task_lists.find(taskList => taskList.id === id);
      await this.updateTaskList({
        teamId: this.$route.params.id,
        form: taskList
      });
      await this.fetchTaskLists({ teamId: this.$route.params.id });
    },
    async addTask(taskList) {
      if (this.taskModel.name === '') {
        this.isAddingTask = false;
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
      await this.fetchTaskLists({ teamId: this.$route.params.id });

      this.taskModel = {
        task_list_id: taskList.id,
        team_id: this.$route.params.id,
        name: '',
        tags: []
      };
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
