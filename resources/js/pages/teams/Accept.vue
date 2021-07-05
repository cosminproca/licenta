<template>
  <div class="row">
    <div class="col-lg-8 m-auto">
      <card :title="'Accept Team Invitation'">
        <template v-if="success">
          <div class="alert alert-success" role="alert">
            {{ success }}
          </div>

          <router-link :to="{ name: 'login' }" class="btn btn-primary">
            {{ $t('login') }}
          </router-link>
        </template>
        <template v-else>
          <div class="alert alert-danger" role="alert">
            {{ error || 'Failed to accept team invitation' }}
          </div>
        </template>
      </card>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

const qs = params =>
  Object.keys(params)
    .map(key => `${key}=${params[key]}`)
    .join('&');

export default {
  async beforeRouteEnter(to, from, next) {
    try {
      const { data } = await axios.get(
        `/api/teams/accept/${to.params.id}?${qs(to.query)}`
      );

      next(vm => {
        vm.success = data.message;
      });
    } catch (e) {
      next(vm => {
        vm.error = e.response.data.errors;
      });
    }
  },

  middleware: 'guest',

  metaInfo() {
    return { title: 'Accept Team Invitation' };
  },

  data() {
    return {
      error: '',
      success: ''
    };
  }
};
</script>
