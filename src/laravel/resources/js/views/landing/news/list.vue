<template>
  <div class="container">
    <el-container v-loading="listLoading">
      <div class="row mt-3">
        <div class="col-lg-4 col-sm-12 mb-3" v-for="(item, index) in items" :key="index">
          <el-card :body-style="{ padding: '1.25rem', '-ms-flex': '1 1 auto', flex: '1 1 auto', 'min-height': '1px',  }" shadow="always">
            <div style="padding: 14px;">
              <span>{{ item.title | truncate(100, '...') }}</span>
              <div class="bottom clearfix">
                <el-tooltip class="item" effect="dark" :content="item.created_at" placement="right">
                  <time class="time">{{ new Date(item.created_at).toLocaleDateString(language, { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}</time>>
                </el-tooltip>
                <p class="content">{{ item.content | truncate(150, '...') }}</p>
                <el-button type="text" class="button" @click="goToNews(item.id)">Подробнее</el-button>
              </div>
            </div>
          </el-card>
        </div>
      </div>
    </el-container>
    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />
  </div>
</template>

<script>
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import Resource from '@/api/resource';
const newsResource = new Resource('news');
export default {
  // name: "news_list",
  components: { Pagination },
  data() {
    return {
      items: [],
      total: 0,
      listLoading: true,
      listQuery: {
        page: 1,
        limit: 9,
      },
    };
  },
  computed:{
    language(){
      return this.$store.getters.language;
    }
  },
  created() {
    this.getList();
  },
  methods: {
    async getList() {
      this.listLoading = true;
      const { data } = await newsResource.list(this.listQuery);
      this.items = data.data.items;
      this.total = data.data.total;
      this.listLoading = false;
    },
    goToNews(id) {
      this.$router.push({ name: 'news_view', params: { id }})
    },
  },
}
</script>

<style lang="scss" scoped>
.el-row {
  margin-bottom: 20px;
  &:last-child {
    margin-bottom: 0;
  }
}
.el-col {
    margin-top: 1rem;
}

.time {
  font-size: 13px;
  color: #999;
}

.bottom {
  margin-top: 13px;
  line-height: 1.2rem;
}

p.content{
  font-size: 0.9rem;
}

.button {
  padding: 0;
  float: right;
}

.image {
  width: 100%;
  display: block;
}

.clearfix:before,
.clearfix:after {
  display: table;
  content: "";
}

.clearfix:after {
  clear: both
}
.el-card{
  height: 100%;
}
</style>