<template>
  <div class="app-container">
    <el-table v-loading="listLoading" :data="items" border fit highlight-current-row style="width: 100%">
      <el-table-column align="center" label="ID" width="80">
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>

      <el-table-column min-width="300px" label="Title">
        <template slot-scope="{row}">
          <router-link :to="'/backend/administrator/news/edit/'+row.id" class="link-type">
            <span>{{ row.title }}</span>
          </router-link>
        </template>
      </el-table-column>


      <el-table-column width="120px" align="center" label="Author">
        <template slot-scope="scope">
          <span>#{{ scope.row.author.id }} {{ scope.row.author.username }}</span>
        </template>
      </el-table-column>

      <el-table-column class-name="status-col" label="Status" width="110">
        <template slot-scope="{row}">
          <el-switch
              v-model="row.is_active"
              active-color="#13ce66"
              inactive-color="#ff4949">
          </el-switch>
<!--          <el-tag :type="row.is_active | statusFilter">-->
<!--            {{ row.is_active }}-->
<!--          </el-tag>-->
        </template>
      </el-table-column>

      <el-table-column width="180px" align="center" label="Created at">
        <template slot-scope="scope">
          <span>{{ scope.row.created_at }}</span>
        </template>
      </el-table-column>

      <el-table-column width="180px" align="center" label="Updated at">
        <template slot-scope="scope">
          <span>{{ scope.row.updated_at }}</span>
        </template>
      </el-table-column>

      <el-table-column width="180px" align="center" label="Deleted at">
        <template slot-scope="scope">
          <span>{{ scope.row.deleted_at }}</span>
        </template>
      </el-table-column>

      <el-table-column align="center" label="Actions" width="120">
        <template slot-scope="scope">
          <router-link :to="'/backend/administrator/news/edit/'+scope.row.id">
            <el-button type="primary" size="small" icon="el-icon-edit">
              Edit
            </el-button>
          </router-link>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit" @pagination="getList" />
  </div>
</template>

<script>
import Pagination from '@/components/Pagination'; // Secondary package based on el-pagination
import Resource from '@/api/resource';
const newsResource = new Resource('news');

export default {
  name: 'NewsList',
  components: { Pagination },
  filters: {
    statusFilter(status) {
      const statusMap = {
        published: 'success',
        draft: 'info',
        deleted: 'danger',
      };
      return statusMap[status];
    },
  },
  data() {
    return {
      items: null,
      total: 0,
      listLoading: true,
      listQuery: {
        page: 1,
        limit: 20,
        withTrashed: true,
      },
    };
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
  },
};
</script>

<style scoped>
.edit-input {
  padding-right: 100px;
}
.cancel-btn {
  position: absolute;
  right: 15px;
  top: 10px;
}
</style>
