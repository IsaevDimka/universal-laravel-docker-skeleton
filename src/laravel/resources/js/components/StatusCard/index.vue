<template>
  <el-card class="box-card" v-loading="loading">
    <div slot="header" class="clearfix">
      <span>{{ message }} | {{ config.version }} | environment: {{ node_env }}</span>
      <span style="float: right; padding: 3px 0" type="text" v-if="uptime">Uptime: <i>{{ uptime }}</i></span>
    </div>
    <div v-for="(error, index) in errors" :key="index" class="text item mt-1 mb-1">
      <el-alert :title="error"
                type="error"
                :closable="false"
                show-icon/>
    </div>
    <el-divider content-position="left">Services</el-divider>
    <div v-for="(status, service) in services" :key="index" class="mb-1">
      <el-alert :title="service"
                :description="status !== null ? status : '??'"
                :type="getAlertType(status)"
                :closable="false"
                show-icon/>
    </div>
    <el-divider></el-divider>
    <div class="mt-3">
      <div class="bottom clearfix" v-for="(key, index) in configs" :key="index">
        <span style="float: left">{{ key | uppercaseFirstCamelCase }}:</span>
        <span style="color: #8492a6; font-size: 0.8rem" class="ml-1">{{ config[key] }}</span>
      </div>
    </div>
  </el-card>
</template>

<script>
import request from "@/utils/request";

export default {
  name: 'StatusCard',
  data() {
    return {
      loading: false,
      errors: [],
      services: [],
      uptime: null,
      status: null,
      message: null,
      configs: [
        'environment',
        'timezone',
        'locale',
        'latest_release',
      ]
    };
  },
  computed: {
    config() {
      return window.config;
    },
    node_env(){
      return process.env.NODE_ENV;
    }
  },
  created() {
    this.getStatus();
  },
  methods: {
    getStatus() {
      this.loading = true;
      request({
        url: '/health',
        method: 'get',
      }).then(response => {
        const {status, message, errors, services, uptime} = response.data;
        this.status = status;
        this.message = message;
        this.errors = errors;
        this.services = services;
        this.uptime = uptime;
      }).catch(error => {
        this.$message({
          showClose: true,
          message: error.response.data.message,
          type: 'error',
          offset: 73,
          duration: 5000,
        });
      }).finally(() => (this.loading = false));
    },
    getAlertType(status = null){
      switch (status){
        case 'Operational': return 'success';
        case 'Down': return 'error';
        default: return 'warning';
      }
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss">
.bottom {
  margin-top: 13px;
  line-height: 12px;
}

.button {
  padding: 0;
  float: right;
}

.clearfix:before,
.clearfix:after {
  display: table;
  content: "";
}

.clearfix:after {
  clear: both
}
</style>
