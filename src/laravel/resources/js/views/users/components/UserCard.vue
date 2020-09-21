<template>
  <el-card v-if="user">
    <div class="user-profile">
<!--      <div class="user-avatar box-center">-->
<!--        <pan-thumb :image="user.avatar" :height="'100px'" :width="'100px'" :hoverable="false" />-->
<!--      </div>-->
      <div class="box-center">
        <div class="user-name text-center">
          ID: {{ user.id }}
        </div>
<!--        <div class="user-item text-center text-muted">-->
<!--          Roles: {{ getRoles() }}-->
<!--        </div>-->
<!--        <div class="user-item text-center text-muted">-->
<!--          Permissions: {{ getPermissions() }}-->
<!--        </div>-->
        <div class="user-item text-center text-muted">
          {{ user.is_active ? 'Active' : 'Is not active' }}
        </div>
      </div>
      <div class="box-social">
        <el-table :data="tableData" :show-header="false" style="width: 100%" stripe>
          <el-table-column align="left" width="120">
            <template slot-scope="scope">
              {{ scope.row.label }}
            </template>
          </el-table-column>
          <el-table-column align="left" width="200">
            <template slot-scope="scope">
              <el-input v-model="user[scope.row.key]" :disabled="!isEdit"></el-input>
            </template>
          </el-table-column>
<!--          <el-table-column align="right" width="300">-->
<!--            <template slot-scope="scope">-->
<!--              <el-button type="default" @click="handleSaveRow">Save</el-button>-->
<!--              <el-button type="primary" @click="handleEditRow">Edit</el-button>-->
<!--            </template>-->
<!--          </el-table-column>-->
        </el-table>
      </div>
      <div class="user-follow">
        <el-button type="primary" style="width: 100%;">
          Action
        </el-button>
      </div>
    </div>
  </el-card>
</template>

<script>
import PanThumb from '@/components/PanThumb';

export default {
  components: { PanThumb },
  props: {
    user: {
      type: Object,
      default: () => {
        return {
          email: '',
          phone: '',
          avatar: '',
          roles: [],
          permissions: [],
        };
      },
    },
  },
  data() {
    return {
      tableData: [
        {
          label: 'Phone',
          key: 'phone',
        },
        {
          label: 'Email',
          key: 'email',
        },
        {
          label: 'Telegram',
          key: 'telegram_chat_id',
        }
      ],
      isEdit: false,
    };
  },
  methods: {
    getRoles() {
      return this.user.roles ? this.user.roles.map((value) => this.$options.filters.uppercaseFirst(value)).join(' | ') : '';
    },
    getPermissions() {
      return this.user.permissions ? this.user.permissions.join(' | ') : '';
    },
    handleEditRow() {
      this.isEdit = true
    },
    handleSaveRow() {
      this.isEdit = false
    },
  },
};
</script>

<style lang="scss" scoped>
.user-profile {
  .user-name {
    font-weight: bold;
  }
  .box-center {
    padding-top: 10px;
  }
  .user-item {
    padding-top: 10px;
    font-weight: 400;
    font-size: 14px;
  }
  .box-social {
    padding-top: 30px;
    .el-table {
      border-top: 1px solid #dfe6ec;
    }
  }
  .user-follow {
    padding-top: 20px;
  }
}
</style>
