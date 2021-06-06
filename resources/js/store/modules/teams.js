import { index, show, store, update, destroy } from '@/api/teams';
import { SET_DATA, REMOVE_DATA, SET_MODEL, RESET_MODEL } from '@/utils/store';
import Form from 'vform';

const base = new Form({
  owner_id: 0,
  name: ''
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
  async index({ commit }) {
    const res = await index();

    commit('SET_DATA', res.data);

    return res;
  },

  async show({ commit }, id) {
    const res = await show(id);

    commit('SET_MODEL', res.data);

    return res;
  },

  async store({ commit }, form) {
    const res = await store(form);

    commit('SET_MODEL', res.data);

    return res;
  },

  async update({ commit }, form) {
    const res = await update(form);

    commit('SET_MODEL', res.data);

    return res;
  },

  async destroy({ commit }, id) {
    return await destroy(id);
  }
};
