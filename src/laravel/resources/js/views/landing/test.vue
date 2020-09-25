<template>
  <div class="container">
    <el-button @click="oAuthGoogle" :loading="loading">oauth Google</el-button>
    <div class="mt-3">
      <el-input v-model="callback_url" placeholder="Please input" style="width:400px;max-width:100%;" />
      <el-button v-clipboard:copy="callback_url" v-clipboard:success="clipboardSuccess" type="primary" icon="document">
        copy
      </el-button>
    </div>
  </div>
</template>

<script>
import request from "@/utils/request";
import clipboard from '@/directive/clipboard/index.js'; // use clipboard by v-directive
export default {
  name: "test",
  directives: {
    clipboard,
  },
  components: {
  },
  data() {
    return {
      loading: false,
      callback_url: '',
    }
  },
  created() {
  },
  computed: {
  },
  watch: {
  },
  methods: {
    oAuthGoogle() {
      this.$message({
        showClose: true,
        message: `oAuthGoogle`,
        type: 'success',
        offset: 73,
        duration: 5000,
      });
      this.loading = true;
      request({
        url: '/oauth/google',
        method: 'post',
      }).then(response => {

        const { url } = response.data.data;
        this.callback_url = url;
        window.location = url;

        this.$message({
          showClose: true,
          message: response.data.message,
          type: 'success',
          offset: 73,
          duration: 5000,
        });
      }).catch(error => {
        this.$message({
          showClose: true,
          message: error.response.message,
          type: 'error',
          offset: 73,
          duration: 5000,
        });
      }).finally(() => (this.loading = false));
    },
    clipboardSuccess() {
      this.$message({
        message: 'Copy successfully',
        type: 'success',
        duration: 1500,
      });
    },
  },
}
</script>

<style>
.image {
  float: left;
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center center;
  border: 1px solid #ebebeb;
  margin: 5px 5px 48px 5px;
}
</style>