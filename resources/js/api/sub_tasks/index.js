import axios from 'axios';

const module = teamId => `api/teams/${teamId}/sub_tasks`;

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

    return res.data;
  } catch (err) {
    console.log(err);
    return err.response.data;
  }
}

async function update(teamId, form) {
  try {
    const res = await axios.put(`${module(teamId)}/${form.id}`, form);

    return res.data;
  } catch (err) {
    console.log(err);
    return err.response.data;
  }
}

async function destroy(teamId, id) {
  try {
    const res = await axios.delete(`${module(teamId)}/${id}`);

    return res.data;
  } catch (err) {
    console.log(err);
    return err.response.data;
  }
}

export { index, show, store, update, destroy };
