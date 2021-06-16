import axios from 'axios';
import Vue from 'vue';

const module = teamId => `api/teams/${teamId}/tasks`;

async function index(teamId) {
  try {
    return await axios.get(`${module(teamId)}`);
  } catch (err) {
    console.log(err);
  }
}

async function show(teamId, id) {
  try {
    return await axios.get(`${module(teamId)}/${id}`);
  } catch (err) {
    console.log(err);
  }
}

async function store(teamId, form) {
  try {
    const res = await axios.post(`${module(teamId)}`, form);

    Vue.$toast.success('Task created successfully');

    return res.data;
  } catch (err) {
    console.log(err);

    Vue.$toast.error('Task failed to create');

    return err.response.data;
  }
}

async function update(teamId, form) {
  try {
    const res = await axios.put(`${module(teamId)}/${form.id}`, form);

    Vue.$toast.success('Task updated successfully');

    return res.data;
  } catch (err) {
    console.log(err);

    Vue.$toast.error('Task failed to update');

    return err.response.data;
  }
}

async function destroy(teamId, id) {
  try {
    const res = await axios.delete(`${module(teamId)}/${id}`);

    Vue.$toast.success('Task deleted successfully');

    return res.data;
  } catch (err) {
    console.log(err);

    Vue.$toast.error('Task failed to delete');

    return err.response.data;
  }
}

export { index, show, store, update, destroy };
