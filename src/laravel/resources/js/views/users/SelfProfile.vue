<template>
  <div class="app-container">
    <el-row :gutter="20">
      <el-col :span="18">
        <user-card :user="user" v-loading="loading"/>
<!--          <user-bio />-->
      </el-col>
      <el-col :span="12">
<!--          <user-activity :user="user" />-->
      </el-col>
    </el-row>
    <debagger-pannel height="initial">{{ JSON.stringify(user, null, '\t') }}</debagger-pannel>
  </div>
</template>

<script>
import UserBio from './components/UserBio';
import UserCard from './components/UserCard';
import UserActivity from './components/UserActivity';

export default {
  name: 'SelfProfile',
  components: { UserBio, UserCard, UserActivity },
  data() {
    return {
      user: {},
      loading: false,
    };
  },
  watch: {
    '$route': 'getUser',
  },
  created() {
    this.getUser();
  },
  methods: {
    async getUser() {
      this.loading = true;
      const data = await this.$store.dispatch('user/getInfo');
      this.user = data.data;
      this.loading = false;
    },
  },
};
</script>
