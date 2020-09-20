<template>
  <div class="createPost-container">
    <el-form ref="postForm" :model="postForm" :rules="rules" class="form-container">
      <sticky :class-name="'sub-navbar '+postForm.status">
        <StatusDropdown v-model="postForm.is_active" />
<!--        <PlatformDropdown v-model="showPreview" />-->
<!--        <SourceUrlDropdown v-model="postForm.source_uri" />-->
        <el-tooltip class="item" effect="dark" content="Show preview" placement="bottom">
          <el-switch v-model="showPreview"></el-switch>
        </el-tooltip>
        <el-button
          v-loading="loading"
          style="margin-left: 10px;"
          type="success"
          @click="submitForm"
        >
          Submit
        </el-button>
        <el-button v-loading="loading" type="warning" @click="draftForm">
          Draft
        </el-button>
      </sticky>

      <div class="createPost-main-container">
        <el-row>
          <el-col :span="24">
            <el-form-item style="margin-bottom: 40px;" prop="title">
              <MDinput v-model="postForm.title" :maxlength="100" name="name" required>
                Title
              </MDinput>
            </el-form-item>

            <div class="postInfo-container">
              <el-row :gutter="20">
                <el-col :span="6">
                  <el-form-item
                      label-width="80px"
                      label="ID"
                      class="postInfo-container-item"
                  >
                    <el-input v-model="postForm.id" disabled></el-input>
                  </el-form-item>
                </el-col>
                <el-col :span="8">
                  <el-form-item label-width="80px" label="Author:" class="postInfo-container-item">
                    <el-select
                      v-model="postForm.author_id"
                      :remote-method="getRemoteUserList"
                      filterable
                      remote
                      placeholder="Search user"
                    >
                      <el-option
                        v-for="(user, index) in userListOptions"
                        :key="user.id"
                        :label="`#${user.id} ${user.username} `"
                        :value="user.id"
                      />
                    </el-select>
                  </el-form-item>
                </el-col>

                <el-col :span="10">
                  <el-form-item
                    label-width="120px"
                    label="Published date:"
                    class="postInfo-container-item"
                  >
                    <el-date-picker
                      v-model="postForm.created_at"
                      type="datetime"
                      format="yyyy-MM-dd HH:mm:ss"
                      placeholder="Select date and time"
                    />
                  </el-form-item>
                </el-col>
              </el-row>
            </div>
          </el-col>
        </el-row>

        <el-form-item style="margin-bottom: 40px;" label-width="80px" label="Summary:">
          <el-input
            v-model="postForm.content"
            :rows="1"
            type="textarea"
            class="news-textarea"
            autosize
            placeholder="Please enter the content"
          />
          <span v-show="contentShortLength" class="word-counter">{{ contentShortLength }} word</span>
        </el-form-item>

        <el-form-item prop="content" style="margin-bottom: 30px;">
          <Tinymce ref="editor" v-model="postForm.content" :height="400" />
        </el-form-item>

        <div class="editor-content" v-html="postForm.content" v-if="showPreview"/>
        <el-form-item prop="image_uri" style="margin-bottom: 30px;">
          <Upload />
        </el-form-item>
      </div>
    </el-form>
  </div>
</template>

<script>
import Tinymce from '@/components/Tinymce';
import Upload from '@/components/Upload/SingleImage';
import MDinput from '@/components/MDinput';
import Sticky from '@/components/Sticky'; // Sticky header
import { validURL } from '@/utils/validate';
import { fetchNews } from '@/api/news';
import { updateNews } from '@/api/news';
import { userSearch } from '@/api/search';
import {
  StatusDropdown,
  PlatformDropdown,
  SourceUrlDropdown,
} from './Dropdown';

const defaultForm = {
  id: undefined,
  title: '',
  slug: '',
  content: '',
  author_id: '',
  is_active: null,
};

export default {
  name: 'NewsDetail',
  components: {
    Tinymce,
    MDinput,
    Upload,
    Sticky,
    StatusDropdown,
    PlatformDropdown,
    SourceUrlDropdown,
  },
  props: {
    isEdit: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    const validateRequire = (rule, value, callback) => {
      if (value === '') {
        this.$message({
          message: rule.field + ' is required',
          type: 'error',
        });
        callback(new Error(rule.field + ' is required'));
      } else {
        callback();
      }
    };
    const validateSourceUri = (rule, value, callback) => {
      if (value) {
        if (validURL(value)) {
          callback();
        } else {
          this.$message({
            message: 'External URL is invalid.',
            type: 'error',
          });
          callback(new Error('External URL is invalid.'));
        }
      } else {
        callback();
      }
    };
    return {
      showPreview: false,
      postForm: Object.assign({}, defaultForm),
      loading: false,
      userListOptions: [],
      rules: {
        // image_uri: [{ validator: validateRequire }],
        title: [{ validator: validateRequire }],
        content: [{ validator: validateRequire }],
        // source_uri: [{ validator: validateSourceUri, trigger: 'blur' }],
      },
      tempRoute: {},
    };
  },
  computed: {
    contentShortLength() {
      return this.postForm.content.length;
    },
    lang() {
      return this.$store.getters.language;
    },
  },
  created() {
    if (this.isEdit) {
      const id = this.$route.params && this.$route.params.id;
      this.fetchData(id);
      this.getRemoteUserList();
    } else {
      this.postForm = Object.assign({}, defaultForm);
    }

    // Why need to make a copy of this.$route here?
    // Because if you enter this page and quickly switch tag, may be in the execution of the setTagsViewTitle function, this.$route is no longer pointing to the current page
    this.tempRoute = Object.assign({}, this.$route);
  },
  methods: {
    fetchData(id) {
      fetchNews(id)
        .then(response => {
          this.postForm = response.data.data;
          // Set tagsview title
          this.setTagsViewTitle();
        })
        .catch(err => {
          console.log(err);
        });
    },
    setTagsViewTitle() {
      const title =
        this.lang === 'zh'
          ? '编辑文章'
          : this.lang === 'vi'
            ? 'Chỉnh sửa'
            : 'Edit News'; // Should move to i18n
      const route = Object.assign({}, this.tempRoute, {
        title: `${title}-${this.postForm.id}`,
      });
      // this.$store.dispatch('updateVisitedView', route);
    },
    submitForm() {
      this.$refs.postForm.validate(valid => {
        if (valid) {
          this.loading = true;
          updateNews(this.postForm)
              .then(response => {
                this.setTagsViewTitle();
                this.$notify({
                  title: 'Success',
                  message: 'News has been updated successfully',
                  type: 'success',
                  duration: 2000,
                });
              })
              .catch(err => {
                console.log(err);
                this.$notify({
                  title: 'Error',
                  message: err.data.message,
                  type: 'error',
                  duration: 2000,
                });
              });

          this.loading = false;
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    draftForm() {
      if (
        this.postForm.content.length === 0 ||
        this.postForm.title.length === 0
      ) {
        this.$message({
          message: 'Please enter required title and content',
          type: 'warning',
        });
        return;
      }
      this.$message({
        message: 'Successfully saved',
        type: 'success',
        showClose: true,
        duration: 1000,
      });
      this.postForm.status = 'draft';
    },
    getRemoteUserList(query) {
      userSearch({ keyword: query }).then(response => {
        if (!response.data.data) {
          return;
        }
        this.userListOptions = response.data.data;
      });
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
@import "~@/styles/mixin.scss";
.createPost-container {
  position: relative;
  .createPost-main-container {
    padding: 0 45px 20px 50px;
    .postInfo-container {
      position: relative;
      @include clearfix;
      margin-bottom: 10px;
      .postInfo-container-item {
        float: left;
      }
    }
  }
  .word-counter {
    width: 40px;
    position: absolute;
    right: -10px;
    top: 0px;
  }
}
</style>
<style>
.createPost-container label.el-form-item__label {
  text-align: left;
}
.editor-content{
  margin-top: 20px;
}
</style>
