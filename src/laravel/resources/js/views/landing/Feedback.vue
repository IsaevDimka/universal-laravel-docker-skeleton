<template>
  <div class="container">
    <el-form ref="formFeedback"
             :model="formFeedback"
             :rules="formRules"
             label-position="top">
      <el-form-item label="Message" prop="message">
        <el-input
            type="textarea"
            :autosize="{ minRows: 5, maxRows: 7}"
            placeholder="Message"
            v-model="formFeedback.message">
        </el-input>
      </el-form-item>
      <el-form-item prop="recaptcha">
        <vue-recaptcha
            ref="recaptcha"
            @verify="onRecaptchaVerify"
            @expired="onRecaptchaExpired"
            :sitekey="recaptcha_site_key"></vue-recaptcha>
      </el-form-item>

      <el-form-item style="margin-top: 1rem!important;">
        <el-button type="primary" @click="onSubmitFormFeedback" :loading="loading">
          Send
        </el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import VueRecaptcha from 'vue-recaptcha';
import request from "@/utils/request";
import {validEmail} from "@/utils/validate";

export default {
name: "Feedback",
  components: {
    VueRecaptcha,
  },
  data(){
    return {
      recaptcha_site_key: process.env.MIX_RECAPTCHA_SITE_KEY,

      loading: false,
      formFeedback: {
        email: null,
        message: null,
        recaptcha: null,
      },
      formRules: {
        message: [{ required: true, trigger: 'blur', min:10, message: 'Enter your message! Minimum length 10 characters.' }],
        recaptcha: [{required: true, trigger: 'blur', message: 'Enter captcha!'}],
      },
    };
  },
  methods: {
    onRecaptchaVerify(response) {
      this.formFeedback.recaptcha = response;
    },
    onRecaptchaExpired() {
      this.formFeedback.recaptcha = null;
    },
    onSubmitFormFeedback() {
      this.$refs.formFeedback.validate(valid => {
        if (valid) {
          this.loading = true;
          request({
            url: '/feedback',
            method: 'post',
            data: this.formFeedback
          }).then(response => {
            const {data, message} = response.data;
            this.$message({
              showClose: true,
              message: message,
              type: 'success',
              offset: 73,
              duration: 5000,
            });
            this.resetData();
          }).catch(error => {
            this.$message({
              showClose: true,
              message: error.response.data.message,
              type: 'error',
              offset: 73,
              duration: 5000,
            });
          }).finally(() => (this.loading = false));
        } else {
          this.$message({
            showClose: true,
            message: 'Ошибка',
            type: 'error',
            offset: 73,
            duration: 5000,
          });
          return false
        }
      })
    },
    resetData(){
      Object.assign(this.formFeedback, {
        email: null,
        message: null,
        recaptcha: null,
      })
      this.$refs.recaptcha.reset();
    },
  }
}
</script>

<style scoped>

</style>