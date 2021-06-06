import axios from 'axios';

const module = `api/teams`;

async function index() {
  try {
    return await axios.get(`${module}`);
  } catch (err) {
    console.log(err);
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

    return res.data;
  } catch (err) {
    console.log(err);
    return err.response.data;
  }
}

async function update(form) {
  try {
    const res = await axios.put(`${module}/${form.id}`, form);

    return res.data;
  } catch (err) {
    console.log(err);
    return err.response.data;
  }
}

async function destroy(id) {
  try {
    const res = await axios.delete(`${module}/${id}`);

    return res.data;
  } catch (err) {
    console.log(err);
    return err.response.data;
  }
}

export { index, show, store, update, destroy };
