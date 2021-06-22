import axios from 'axios';
import Vue from 'vue';

const module = `api/teams`;

async function index() {
  try {
    return await axios.get(`${module}`);
  } catch (err) {
    console.log(err);
  }
}

async function sendInvite(id, form) {
  try {
    const res = await axios.post(`${module}/invite/${id}`, form);

    Vue.$toast.success('Team invite sent successfully');

    return res.data;
  } catch (err) {
    console.log(err);

    Vue.$toast.error('Failed to send invite');

    return err.response.data;
  }
}

async function show(id) {
  try {
    return await axios.get(`${module}/${id}`);
  } catch (err) {
    console.log(err);
  }
}

async function store(form) {
  try {
    const res = await axios.post(`${module}`, form);

    Vue.$toast.success('Team created successfully');

    return res.data;
  } catch (err) {
    console.log(err);

    Vue.$toast.error('Failed to create team');

    return err.response.data;
  }
}

async function update(form) {
  try {
    const res = await axios.put(`${module}/${form.id}`, form);

    Vue.$toast.success('Team updated successfully');

    return res.data;
  } catch (err) {
    console.log(err);

    Vue.$toast.error('Failed to update team');

    return err.response.data;
  }
}

async function destroy(id) {
  try {
    const res = await axios.delete(`${module}/${id}`);

    Vue.$toast.success('Team deleted successfully');

    return res.data;
  } catch (err) {
    console.log(err);

    Vue.$toast.error('Failed to delete team');

    return err.response.data;
  }
}

export { index, show, store, update, destroy, sendInvite };
