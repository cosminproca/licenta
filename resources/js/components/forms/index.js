import Vue from 'vue';

import HasError from './validation/HasError.vue';
import AlertError from './validation/AlertError';
import AlertSuccess from './validation/AlertSuccess';
import Checkbox from './Checkbox';
import TextInput from './TextInput';

// Components that are registered globaly.
[HasError, AlertError, AlertSuccess, Checkbox, TextInput].forEach(Component => {
  Vue.component(Component.name, Component);
});
