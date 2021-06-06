import { index, show, store, update, destroy } from '@/api/tasks';
import { SET_DATA, REMOVE_DATA, SET_MODEL, RESET_MODEL } from '@/utils/store';
import Form from 'vform';

const base = new Form({
  id: 0,
  task_list_id: 0,
  team_id: 0,
  name: '',
  description: '',
  priority: null,
  due_date: '',
  tags: []
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
  async index({ commit }, { teamId }) {
    const res = await index(teamId);

    commit('SET_DATA', res.data);

    return res;
  },

  async show({ commit }, { teamId, id }) {
    const res = await show(teamId, id);

    commit('SET_MODEL', res.data);

    return res;
  },

  async store({ commit }, { teamId, form }) {
    const res = await store(teamId, form);

    commit('SET_MODEL', res.data);

    return res;
  },

  async update({ commit }, { teamId, form }) {
    const res = await update(teamId, form);

    commit('SET_MODEL', res.data);

    return res;
  },

  async destroy({ commit }, { teamId, id }) {
    return await destroy(teamId, id);
  }
};
