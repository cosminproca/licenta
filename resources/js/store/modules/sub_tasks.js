import { index, show, store, update, destroy } from '@/api/sub_tasks';
import { SET_DATA, REMOVE_DATA, SET_MODEL, RESET_MODEL } from '@/utils/store';
import Form from 'vform';

const base = new Form({
  id: 0,
  task_id: 0,
  team_id: 0,
  completed: false,
  name: '',
  description: '',
  due_date: ''
});

export const state = {
  data: {},
  default: { ...base },
  model: { ...base }
};

export const getters = {
  dataArray: state => Object.values(state.data)
};

export const mutations = {
  SET_DATA,
  SET_MODEL,
  RESET_MODEL,
  REMOVE_DATA
};

export const actions = {
  async index({ commit }, { teamId, taskId }) {
    const res = await index(teamId, taskId);

    commit('SET_DATA', res.data);

    return res;
  },

  async show({ commit }, { teamId, taskId, id }) {
    const res = await show(teamId, taskId, id);

    commit('SET_MODEL', res.data);

    return res;
  },

  async store({ commit }, { teamId, taskId, form }) {
    const res = await store(teamId, taskId, form);

    commit('SET_MODEL', res.data);

    return res;
  },

  async update({ commit }, { teamId, taskId, form }) {
    const res = await update(teamId, taskId, form);

    commit('SET_MODEL', res.data);

    return res;
  },

  async destroy({ commit }, { teamId, taskId, id }) {
    return await destroy(teamId, taskId, id);
  }
};
