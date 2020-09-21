<template>
  <div class="container" v-loading="loading">
    <div class="mb-1">
      <el-button icon="el-icon-arrow-left" size="mini" plain @click="$router.go(-1)">Назад</el-button>
    </div>
    <div v-if="item" class="mt-1">
      <h1 class="text-md-center">
        {{ item.title }}
      </h1>
      <span class="created-at mt-3">
      <el-tooltip class="item" effect="dark" :content="item.created_at" placement="top">
        <time class="time">{{
            new Date(item.created_at).toLocaleDateString(language, {
              weekday: 'long',
              year: 'numeric',
              month: 'long',
              day: 'numeric'
            })
          }}</time>
      </el-tooltip>
    </span>
      <div class="item-content mt-3">
        {{ item.content }}
      </div>
    </div>
  </div>
</template>

<script>
import {fetchNews} from "@/api/news";

export default {
  computed:{
    language(){
      return this.$store.getters.language;
    }
  },
  data() {
    return {
      loading: false,
      news_id: null,
      item: null,
    }
  },
  created() {
    this.news_id = this.$route.params && this.$route.params.id;
    this.fetchData(this.news_id);
  },
  methods: {
    async fetchData(id) {
      this.loading = true;
      fetchNews(id).then(response => {
        this.item = response.data.data;
        if (!this.item.is_active) {
          this.$router.push({name: 'page_404'})
        }
      }).catch(error => {
        this.$router.push({name: 'page_404'})
      }).finally(() => (this.loading = false));
    },
  }
}
</script>

<style scoped>

</style>