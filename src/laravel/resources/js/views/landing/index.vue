<template>
  <!--  <el-container>-->
  <!--    <el-header style="text-align: right; font-size: 12px">-->
  <!--      <el-menu :default-active="$route.path" class="el-menu-demo" mode="horizontal" :router="true">-->
  <!--        <el-menu-item index="/about">-->
  <!--          <router-link :to="{ name: 'about' }">-->
  <!--            About-->
  <!--          </router-link>-->
  <!--        </el-menu-item>-->
  <!--        <el-menu-item index="/register">-->
  <!--          <router-link :to="{ name: 'register' }">-->
  <!--            Register-->
  <!--          </router-link>-->
  <!--        </el-menu-item>-->
  <!--&lt;!&ndash;        <el-menu-item>&ndash;&gt;-->
  <!--&lt;!&ndash;          <router-link :to="{ name: 'news_list' }">&ndash;&gt;-->
  <!--&lt;!&ndash;            Новости&ndash;&gt;-->
  <!--&lt;!&ndash;          </router-link>&ndash;&gt;-->
  <!--&lt;!&ndash;        </el-menu-item>&ndash;&gt;-->
  <!--        <el-menu-item index="/test">-->
<!--            <router-link :to="{ name: 'test' }">-->
<!--              Test page-->
<!--            </router-link>-->
  <!--        </el-menu-item>-->
  <!--&lt;!&ndash;        <el-submenu index="2">&ndash;&gt;-->
  <!--&lt;!&ndash;          <template slot="title">Menu</template>&ndash;&gt;-->
  <!--&lt;!&ndash;          <el-menu-item index="2-1">item one</el-menu-item>&ndash;&gt;-->
  <!--&lt;!&ndash;          <el-menu-item index="2-2">item two</el-menu-item>&ndash;&gt;-->
  <!--&lt;!&ndash;          <el-menu-item index="2-3">item three</el-menu-item>&ndash;&gt;-->
  <!--&lt;!&ndash;        </el-submenu>&ndash;&gt;-->
  <!--        <el-menu-item index="/login">-->
  <!--          <router-link :to="{ path: '/redirect/login' }">-->
  <!--            {{ checkAuth ? 'Backend' : 'Sign in' }}-->
  <!--          </router-link>-->
  <!--        </el-menu-item>-->
  <!--        <el-menu-item index="/feedback">-->
  <!--          <router-link :to="{ name: 'feedback' }">-->
  <!--            Feedback-->
  <!--          </router-link>-->
  <!--        </el-menu-item>-->
  <!--        <el-menu-item index="/privacy-policy">-->
  <!--          <router-link :to="{ name: 'privacy_policy' }">-->
  <!--            Privacy Policy-->
  <!--          </router-link>-->
  <!--        </el-menu-item>-->
  <!--        <el-menu-item index="/terms">-->
  <!--          <router-link :to="{ name: 'terms' }">-->
  <!--            Terms of service-->
  <!--          </router-link>-->
  <!--        </el-menu-item>-->
  <!--        <el-menu-item index="/changelog">-->
  <!--          <router-link :to="{ name: 'changelog' }">-->
  <!--            Changelog-->
  <!--          </router-link>-->
  <!--        </el-menu-item>-->
  <!--        <div class="el-menu-item">-->
  <!--          <lang-select class="right-menu-item hover-effect" />-->
  <!--        </div>-->
  <!--      </el-menu>-->
  <!--    </el-header>-->
  <!--    <el-main>-->
  <!--      <breadcrumb id="breadcrumb-container" class="breadcrumb-container" />-->
  <!--      <alert-test-version />-->
  <!--      <router-view />-->
  <!--    </el-main>-->
  <!--&lt;!&ndash;    <Likely />&ndash;&gt;-->
  <!--  </el-container>-->

  <div>
    <tailwind-menu/>
    <tailwind-header/>

    <main>
      <transition name="fade-transform" mode="out-in">
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
          <breadcrumb id="breadcrumb-container" class="breadcrumb-container"/>
          <router-view/>
        </div>
      </transition>
    </main>

    <tailwind-cta/>

  </div>
</template>

<script>
import {Footer} from '@/layout/components';
import Breadcrumb from './components/Breadcrumb';
import TailwindHeader from "@/views/landing/components/tailwind-header";
import TailwindCta from "@/views/landing/components/tailwind-cta";
import TailwindMenu from "@/views/landing/components/tailwind-menu";

export default {
  name: 'Landing',
  components: {
    TailwindMenu,
    TailwindCta,
    TailwindHeader,
    Breadcrumb,
    Footer,
  },
  created() {
  },
  data() {
    return {
    };
  },
  methods: {
  }
};
</script>
<style lang="scss" scoped>
@import "@/styles/tailwind";
</style>