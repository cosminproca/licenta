import axios from 'axios';
import Vue from 'vue';

const module = (teamId, taskId) =>
  `api/teams/${teamId}/tasks/${taskId}/sub_tasks`;

async function index(teamId, taskId) {
  try {
    return await axios.get(`${module(teamId, taskId)}`);
  } catch (err) {
    console.log(err);
  }
}

async function show(teamId, taskId, id) {
  try {
    return await axios.get(`${module(teamId, taskId)}/${id}`);
  } catch (err) {
    console.log(err);
  }
}

async function store(teamId, taskId, form) {
  try {
    const res = await axios.post(`${module(teamId, taskId)}`, form);

    Vue.$toast.success('SubTask created successfully');

    return res.data;
  } catch (err) {
    console.log(err);

    Vue.$toast.error('SubTask failed to create');

    return err.response.data;
  }
}

async function update(teamId, taskId, form) {
  try {
    const res = await axios.put(`${module(teamId, taskId)}/${form.id}`, form);

    Vue.$toast.success('SubTask updated successfully');

    return res.data;
  } catch (err) {
    console.log(err);

    Vue.$toast.error('SubTask failed to update');

    return err.response.data;
  }
}

async function destroy(teamId, taskId, id) {
  try {
    const res = await axios.delete(`${module(teamId, taskId)}/${id}`);

    Vue.$toast.success('SubTask deleted successfully');

    return res.data;
  } catch (err) {
    console.log(err);

    Vue.$toast.error('SubTask failed to delete');

    return err.response.data;
  }
}

export { index, show, store, update, destroy };
