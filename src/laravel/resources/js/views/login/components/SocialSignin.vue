<template>
  <div class="social-signup-container" v-loading="loading">
    <div v-for="(driver, index) in drivers" :key="driver"
        class="sign-btn" @click="handleAuth(driver)">
      <span class="wx-svg-container"><i class="el-icon-connection"></i></span>
      {{ driver | uppercaseFirst }}
    </div>

    <!-- Redirect mode -->
    <vue-telegram-login
        mode="redirect"
        telegram-login="cpatrackerSpaceBot"
        redirect-url="https://cpatracker.space/oauth/telegram/callback" />

  </div>
</template>
<script>
import openWindow from '@/utils/open-window'
import {vueTelegramLogin} from 'vue-telegram-login'

import request from "@/utils/request";
export default {
  name: 'SocialSignin',
  components:{
    vueTelegramLogin
  },
  data () {
    return {
      loading: false,
      callback_url: '',
      drivers: [
          'google',
          'github',
          'facebook',
          'vkontakte',
          // 'twitter',
          'gitlab',
          'zalo',
      ]
    };
  },
  methods: {
    yourCallbackFunction (user) {
      // gets user as an input
      // id, first_name, last_name, username,
      // photo_url, auth_date and hash
      console.log(user)
    },
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
