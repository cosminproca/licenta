import Vue from 'vue';
import axios from 'axios';
import store from '@/store';
import router from '@/router';
// import i18n from '@/plugins/i18n';

axios.defaults.baseURL = process.env.MIX_APP_URL?.replace(/([^/])$/, '$1/');

// Request interceptor
axios.interceptors.request.use(request => {
  const token = store.getters['auth/token'];
  if (token) {
    request.headers.common.Authorization = `Bearer ${token}`;
  }

  const locale = store.getters['lang/locale'];
  if (locale) {
    request.headers.common['Accept-Language'] = locale;
  }

  // request.headers['X-Socket-Id'] = Echo.socketId()

  return request;
});

// Response interceptor
axios.interceptors.response.use(
  response => response,
  error => {
    const { status } = error.response;

    if (status >= 500) {
      // TODO: alert
      Vue.$toast.error('Operation failed due to server errors');
      console.log(status);
    }

    if (status === 404 && store.getters['auth/check']) {
      // TODO: alert
      Vue.$toast.error('Resource not found');
      console.log(status);
    }

    if (status === 403 && store.getters['auth/check']) {
      // TODO: alert
      Vue.$toast.error('Unauthorized');

      console.log(status);
      store.commit('auth/LOGOUT');

      router.push({ name: 'login' });
    }

    if (status === 401 && store.getters['auth/check']) {
      // TODO: alert
      Vue.$toast.error('Unauthenticated');

      console.log(status);
      store.commit('auth/LOGOUT');

      router.push({ name: 'login' });
    }

    return Promise.reject(error);
  }
);

Vue.prototype.$axios = axios;

export default axios;
