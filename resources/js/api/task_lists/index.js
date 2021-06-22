import axios from 'axios';
import Vue from 'vue';

const module = teamId => `api/teams/${teamId}/task_lists`;

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

    Vue.$toast.success('TaskList created successfully');

    return res.data;
  } catch (err) {
    console.log(err);

    Vue.$toast.error('TaskList failed successfully');

    return err.response.data;
  }
}

async function updateAll(teamId, taskLists) {
  try {
    return await axios.put(`${module(teamId)}/update_all`, {
      task_lists: taskLists
    });
  } catch (err) {
    console.log(err);
    return err.response.data;
  }
}

async function update(teamId, form) {
  try {
    const res = await axios.put(`${module(teamId)}/${form.id}`, form);

    Vue.$toast.success('TaskList updated successfully');

    return res.data;
  } catch (err) {
    console.log(err);

    Vue.$toast.error('TaskList failed to update');

    return err.response.data;
  }
}

async function destroy(teamId, id) {
  try {
    const res = await axios.delete(`${module(teamId)}/${id}`);

    Vue.$toast.success('TaskList deleted successfully');

    return res.data;
  } catch (err) {
    console.log(err);

    Vue.$toast.error('TaskList failed to delete');

    return err.response.data;
  }
}

export { index, show, store, update, updateAll, destroy };
