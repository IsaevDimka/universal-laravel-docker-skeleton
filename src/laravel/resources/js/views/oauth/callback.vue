<template>
<div>
  <h3>driver: {{ driver }}</h3>
  <debagger-pannel class="pt-2 pb-2" v-loading="loading">
    <pre>
      {{ otherQuery }}
    </pre>
  </debagger-pannel>
</div>
</template>

<script>
export default {
name: "oauthCallback",
  data(){
    return{
      loading: false,
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
          this.loading = true
          this.$store.dispatch('user/oauthCallback', {driver: this.driver, query: this.otherQuery})
            .then(() => {
              this.$router.push({ path: '/redirect/backend' })
              this.loading = false
            })
            .catch(() => {
              this.loading = false
            });
    },
  },
}
</script>

<style scoped>

</style>