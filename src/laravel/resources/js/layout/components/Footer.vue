<template>
  <el-footer>
    <el-card class="box-card debug-card" v-if="config.debug">
      <div slot="header" class="clearfix">
        <span>Debug</span>
      </div>
      <pre>
        {{ config }}
    </pre>
    </el-card>
    <span class="text-muted">{{ config.appName }} {{ config.version }} | {{ config.environment }}</span>
  </el-footer>
</template>

<script>
export default {
  name: 'Footer',
  data: () => ({
    config: window.config,
  }),
  created() {
  }
}
</script>

<style scoped>
.debug-card {
  margin-bottom: 2rem!important;
}

pre {
  font-family: Ubuntu,monospace;
  font-size: 12px;
  color: #212529;
  margin-top: 0;
  margin-bottom: 1rem;
  overflow: auto;
  display: block;
}
</style>
