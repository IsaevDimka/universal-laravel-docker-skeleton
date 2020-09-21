<template>
  <div class="container">

    <div v-if="checkAuth">
      <h3>Вы уже зарегистрированы!</h3>
      <el-button icon="el-icon-arrow-right" type="primary" plain>
        <router-link :to="{ path: '/redirect/login' }">
          В личный кабинет
        </router-link>
      </el-button>
    </div>

    <div v-else>
      <h1>Register</h1>
      <!--  DEBUG -->
      <debagger-pannel height="initial">{{ JSON.stringify(form_register, null, '\t') }}</debagger-pannel>
      <!-- END DEBUG -->
    </div>
  </div>

</template>

<script>
import request from "@/utils/request";
import Resource from '@/api/resource';

import {register} from '@/api/auth';
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';
export default {
  components: {vSelect},
  computed: {
    checkAuth() {
      return !!this.$store.getters.userId;
    },
  },
  data() {
    return {

      debug: [],
    }
  },
  created() {
    if (!this.checkAuth){
    }
  },
  mounted() {
  },
  watch: {

  },
  methods: {
  },
}
</script>

<style lang="scss" scoped>
</style>