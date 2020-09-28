<template>
  <el-container>
    <el-header style="text-align: right; font-size: 12px">
      <el-menu :default-active="$route.path" class="el-menu-demo" mode="horizontal" :router="true">
        <el-menu-item index="/about">
          <router-link :to="{ name: 'about' }">
            About
          </router-link>
        </el-menu-item>
        <el-menu-item index="/register">
          <router-link :to="{ name: 'register' }">
            Register
          </router-link>
        </el-menu-item>
<!--        <el-menu-item>-->
<!--          <router-link :to="{ name: 'news_list' }">-->
<!--            Новости-->
<!--          </router-link>-->
<!--        </el-menu-item>-->
        <el-menu-item index="/test">
          <router-link :to="{ name: 'test' }">
            Test page
          </router-link>
        </el-menu-item>
<!--        <el-submenu index="2">-->
<!--          <template slot="title">Menu</template>-->
<!--          <el-menu-item index="2-1">item one</el-menu-item>-->
<!--          <el-menu-item index="2-2">item two</el-menu-item>-->
<!--          <el-menu-item index="2-3">item three</el-menu-item>-->
<!--        </el-submenu>-->
        <el-menu-item index="/login">
          <router-link :to="{ path: '/redirect/login' }">
            {{ checkAuth ? 'Backend' : 'Sign in' }}
          </router-link>
        </el-menu-item>
        <el-menu-item index="/feedback">
          <router-link :to="{ name: 'feedback' }">
            Feedback
          </router-link>
        </el-menu-item>
        <el-menu-item index="/privacy-policy">
          <router-link :to="{ name: 'privacy_policy' }">
            Privacy Policy
          </router-link>
        </el-menu-item>
        <el-menu-item index="/terms">
          <router-link :to="{ name: 'terms' }">
            Terms of service
          </router-link>
        </el-menu-item>
        <el-menu-item index="/changelog">
          <router-link :to="{ name: 'changelog' }">
            Changelog
          </router-link>
        </el-menu-item>
        <div class="el-menu-item">
          <lang-select class="right-menu-item hover-effect" />
        </div>
      </el-menu>
    </el-header>
    <el-main>
      <breadcrumb id="breadcrumb-container" class="breadcrumb-container" />
      <alert-test-version />
      <router-view />
    </el-main>
<!--    <Likely />-->
  </el-container>
</template>

<script>
import { mapState } from 'vuex';
import LangSelect from '@/components/LangSelect';
import { Footer } from '@/layout/components';
import Breadcrumb from './components/Breadcrumb';
import AlertTestVersion from "./AlertTestVersion";
export default {
  name: 'Landing',
  components: {
    Breadcrumb,
    LangSelect,
    Footer,
    AlertTestVersion,
  },
  computed: {
    ...mapState({
      device: state => state.app.device,
    }),
    checkAuth(){
      return !!this.$store.getters.userId;
    },
    user()
    {
      return this.$store.getters.user;
    }
  },
  created() {
  },
  data() {
    return {
      activeLink: null,
      isCollapse: true
    };
  },
  watch: {
    // $route (to, from) {
    //   this.activeLink = to.name;
    // }
  },
  methods: {
    open() {
      this.$notify.success({
        title: 'Success',
        message: 'This is a success message',
        offset: 100
      });
    },
  }
};
</script>
<style lang="scss" scoped>
main.el-main {
  margin-top: 1rem;
}
</style>