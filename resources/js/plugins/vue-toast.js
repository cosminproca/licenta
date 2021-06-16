import VueToast from 'vue-toast-notification';
import Vue from 'vue';

Vue.use(VueToast, {
  position: 'bottom-right',
  queue: true,
  duration: 2000
});
