<template>
  <div class="login-container">
    <div class="login-form">
      <h3 class="title">
        Auth with {{ driver }}
      </h3>
      <debagger-pannel class="pt-2 pb-2" v-loading="loading">
        <pre>{{ otherQuery }}</pre>
      </debagger-pannel>
    </div>
  </div>
</template>

<script>
export default {
  name: "oauthCallback",
  data() {
    return {
      token: '',
      driver: '',
      redirect: '',
      otherQuery: {},
    }
  },
  created() {
    this.driver = this.$route.params && this.$route.params.driver;
    const query = this.$route.query;
    this.otherQuery = this.getOtherQuery(query);
  },
  mounted() {
    this.handleoAuth();
  },
  methods: {
    getOtherQuery(query) {
      return Object.keys(query).reduce((acc, cur) => {
        if (cur !== 'redirect') {
          acc[cur] = query[cur];
        }
        return acc;
      }, {});
    },
    handleoAuth() {
      const loading = this.$loading({
        lock: true,
        text: 'Loading',
        spinner: 'el-icon-loading',
        background: 'rgba(0, 0, 0, 0.7)'
      });

      this.$store.dispatch('user/oauthCallback', {driver: this.driver, query: this.otherQuery})
          .then(() => {
            this.$router.push({path: '/redirect/backend'})
          })
          .catch((error) => {
            this.$message({
              showClose: true,
              message: error.response.message,
              type: 'error',
              offset: 73,
              duration: 5000,
            });
          }).finally(() => (loading.close()));
    },
  },
}
</script>

<style rel="stylesheet/scss" lang="scss">
$bg: #2d3a4b;
$light_gray: #eee;

/* reset element-ui css */
.login-container {
  .el-input {
    display: inline-block;
    height: 47px;
    width: 85%;

    input {
      background: transparent;
      border: 0px;
      -webkit-appearance: none;
      border-radius: 0px;
      padding: 12px 5px 12px 15px;
      color: $light_gray;
      height: 47px;

      &:-webkit-autofill {
        -webkit-box-shadow: 0 0 0px 1000px $bg inset !important;
        -webkit-text-fill-color: #fff !important;
      }
    }
  }

  .el-form-item {
    border: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    color: #454545;
  }
}

</style>

<style rel="stylesheet/scss" lang="scss" scoped>
$bg: #2d3a4b;
$dark_gray: #889aa4;
$light_gray: #eee;
.login-container {
  position: fixed;
  height: 100%;
  width: 100%;
  background-color: $bg;

  .login-form {
    position: absolute;
    left: 0;
    right: 0;
    width: 520px;
    max-width: 100%;
    padding: 35px 35px 15px 35px;
    margin: 120px auto;
  }

  .svg-container {
    padding: 6px 5px 6px 15px;
    color: $dark_gray;
    vertical-align: middle;
    width: 30px;
    display: inline-block;
  }

  .title {
    font-size: 26px;
    color: $light_gray;
    margin: 0px auto 40px auto;
    text-align: center;
    font-weight: bold;
  }

}

@media screen and (orientation: landscape) and (max-width: 1024px) {
  .login-container {
    position: relative;
    overflow-y: auto;

    .login-form {
      transform: translate(-50%, -50%);
      left: 50%;
      top: 50%;
      margin: auto;
    }
  }
}
</style>
