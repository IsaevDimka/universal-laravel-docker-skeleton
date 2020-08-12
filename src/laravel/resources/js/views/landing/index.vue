<template>
  <el-container>
    <el-header style="text-align: right; font-size: 12px">
      <el-menu default-active="1" class="el-menu-demo" mode="horizontal" @select="handleSelect">
        <el-menu-item index="1">Processing Center</el-menu-item>
        <el-submenu index="2">
          <template slot="title">Workspace</template>
          <el-menu-item index="2-1">item one</el-menu-item>
          <el-menu-item index="2-2">item two</el-menu-item>
          <el-menu-item index="2-3">item three</el-menu-item>
        </el-submenu>
        <el-menu-item index="3" disabled>Info</el-menu-item>
        <el-menu-item index="4">
          <router-link :to="{ path: '/redirect/login' }">
            {{ $t('login.logIn') }}
          </router-link>
        </el-menu-item>
      </el-menu>
    </el-header>

    <el-main>
      <el-tabs v-model="activeName" @tab-click="handleClick">
        <el-tab-pane label="User" name="first">User</el-tab-pane>
        <el-tab-pane label="Config" name="second">Config</el-tab-pane>
        <el-tab-pane label="Role" name="third">Role</el-tab-pane>
        <el-tab-pane label="Task" name="fourth">Task</el-tab-pane>
      </el-tabs>

      <el-button
          plain
          @click="open">
        Notification with offset
      </el-button>

      <el-steps :active="activeStep" finish-status="success">
        <el-step title="Step 1"></el-step>
        <el-step title="Step 2"></el-step>
        <el-step title="Step 3"></el-step>
      </el-steps>
      <el-button style="margin-top: 12px;" @click="nextStep">Next step</el-button>
    </el-main>
  </el-container>
</template>

<script>

import { mapState } from 'vuex';
export default {
  name: 'Landing',
  components: {
  },
  computed: {
    ...mapState({
    }),
  },
  data() {
    return {
      activeName: 'first',
      activeStep: 0,
    };
  },
  methods: {
    open() {
      this.$notify.success({
        title: 'Success',
        message: 'This is a success message',
        offset: 100
      });
    },
    nextStep() {
      if (this.activeStep++ > 2) this.activeStep = 0;
    },
    handleClick(tab, event) {
      console.log(tab, event);
    },
    handleSelect(key, keyPath) {
      console.log(key, keyPath);
    },
  }
};
</script>

<style>
</style>