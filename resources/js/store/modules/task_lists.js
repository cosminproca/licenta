import {
  index,
  show,
  store,
  update,
  updateAll,
  destroy
} from '@/api/task_lists';
import { SET_MODEL, RESET_MODEL } from '@/utils/store';
import Form from 'vform';

const base = new Form({
  id: 0,
  team_id: 0,
  name: ''
});

export const state = {
  data: [],
  default: { ...base },
  model: { ...base }
};

export const getters = {
  dataArray: state => state.data
};

export const mutations = {
  SET_DATA: (state, payload) => {
    state.data = payload;
  },
  SET_MODEL,
  RESET_MODEL
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

  async updateAll({ commit }, { teamId, taskLists }) {
    const res = await updateAll(teamId, taskLists);
    console.log(res);
    commit('SET_DATA', res.data);

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
