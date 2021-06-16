import axios from 'axios';
import Vue from 'vue';

const module = (teamId, taskId) =>
  `api/teams/${teamId}/tasks/${taskId}/comments`;

async function index(teamId, taskId) {
  try {
    return await axios.get(`${module(teamId, taskId)}`);
  } catch (err) {
    console.log(err);
  }
}

async function show(teamId, taskId, id) {
  try {
    return await axios.get(`${module(teamId)}/${id}`);
  } catch (err) {
    console.log(err);
  }
}

async function store(teamId, taskId, form) {
  try {
    const res = await axios.post(`${module(teamId, taskId)}`, form);

    Vue.$toast.success('Comment added successfully');

    return res.data;
  } catch (err) {
    console.log(err);

    Vue.$toast.error('Comment failed to add successfully');

    return err.response.data;
  }
}

async function update(teamId, taskId, form) {
  try {
    const res = await axios.put(`${module(teamId, taskId)}/${form.id}`, form);

    Vue.$toast.success('Comment updated successfully');

    return res.data;
  } catch (err) {
    console.log(err);

    Vue.$toast.error('Comment failed to update');

    return err.response.data;
  }
}

async function updateAll(teamId, taskId, taskLists) {
  try {
    return await axios.put(`${module(teamId, taskId)}/update_all`, {
      task_lists: taskLists
    });
  } catch (err) {
    console.log(err);
    return err.response.data;
  }
}

async function destroy(teamId, taskId, id) {
  try {
    const res = await axios.delete(`${module(teamId, taskId)}/${id}`);

    Vue.$toast.success('Comment deleted successfully');

    return res.data;
  } catch (err) {
    console.log(err);

    Vue.$toast.error('Comment failed to delete');

    return err.response.data;
  }
}

export { index, show, store, update, updateAll, destroy };
