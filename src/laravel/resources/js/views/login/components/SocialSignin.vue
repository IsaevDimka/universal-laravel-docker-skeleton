<template>
  <div class="social-signup-container" v-loading="loading">
    <div class="sign-btn" @click="handleAuth('google')">
      <span class="wx-svg-container"><i class="el-icon-connection"></i></span>
      Google
    </div>
    <div class="sign-btn" @click="handleAuth('github')">
      <span class="qq-svg-container"><i class="el-icon-connection"></i></span>
      Github
    </div>
    <div class="sign-btn" @click="handleAuth('facebook')">
      <span class="qq-svg-container"><i class="el-icon-connection"></i></span>
      Facebook
    </div>
  </div>
</template>
<script>
import openWindow from '@/utils/open-window'
import request from "@/utils/request";
export default {
  name: 'SocialSignin',
  data () {
    return {
      loading: false,
      callback_url: '',
    };
  },
  methods: {
    handleAuth(thirdpart) {
        this.loading = true;
        request({
          url: '/oauth/'+thirdpart,
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
    testHandleClick(thirdpart) {
      // alert('ok');
      // this.$store.commit('SET_AUTH_TYPE', thirdpart)
      // const client_id = 'xxxxx'
      // const redirect_uri = encodeURIComponent('xxx/redirect?redirect=' + window.location.origin + '/auth-redirect')
      // const url = 'https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id=' + client_id + '&redirect_uri=' + redirect_uri
      // openWindow(url, thirdpart, 540, 540)
    },
  }
}
</script>

<style lang="scss" scoped>
  .social-signup-container {
    margin: 20px 0;
    .sign-btn {
      display: inline-block;
      cursor: pointer;
    }
    .icon {
      color: #fff;
      font-size: 24px;
      margin-top: 8px;
    }
    .wx-svg-container,
    .qq-svg-container {
      display: inline-block;
      width: 40px;
      height: 40px;
      line-height: 40px;
      text-align: center;
      padding-top: 1px;
      border-radius: 4px;
      margin-bottom: 20px;
      margin-right: 5px;
    }
    .wx-svg-container {
      background-color: #24da70;
    }
    .qq-svg-container {
      background-color: #6BA2D6;
      margin-left: 50px;
    }
  }
</style>
