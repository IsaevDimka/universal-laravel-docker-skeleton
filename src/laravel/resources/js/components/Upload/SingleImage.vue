<template>
  <div class="upload-container">
    <el-upload
      :data="additionalData"
      :multiple="false"
      :show-file-list="false"
      class="image-uploader"
      drag
      action="/api/v1/storages/store"
      :on-preview="handleUploadPreview"
      :on-remove="handleUploadRemove"
      :on-success="handleUploadSuccess"
      :before-upload="beforeUpload"
    >
      <i class="el-icon-upload" />
      <div class="el-upload__text">Drop file here or <em>click to upload</em></div>
      <div class="el-upload__tip" slot="tip">jpg/png files with a size less than 2mb</div>
    </el-upload>
    <div class="image-preview image-app-preview">
      <div v-show="imageUrl.length>1" class="image-preview-wrapper">
        <img :src="imageUrl">
        <div class="image-preview-action">
          <i class="el-icon-delete" @click="rmImage" />
        </div>
      </div>
    </div>
    <div class="image-preview">
      <div v-show="imageUrl.length>1" class="image-preview-wrapper">
        <img :src="imageUrl">
        <div class="image-preview-action">
          <i class="el-icon-delete" @click="rmImage" />
        </div>
      </div>
    </div>
    <div v-if="imageUrl">
      <el-input v-model="imageUrl" style="width:400px;max-width:100%;margin-top: 1rem;" disabled/>
      <el-button v-clipboard:copy="imageUrl" v-clipboard:success="clipboardSuccess" type="primary" icon="document">
        copy
      </el-button>
    </div>
  </div>
</template>

<script>
import clipboard from '@/directive/clipboard/index.js'; // use clipboard by v-directive
export default {
  directives: {
    clipboard,
  },
  name: 'SingleImageUpload3',
  props: {
    value: {
      type: String,
      default: '',
    },
  },
  data() {
    return {
      tempUrl: '',
      additionalData: {},
    };
  },
  computed: {
    imageUrl() {
      return this.value;
    },
  },
  methods: {
    rmImage() {
      this.emitInput('');
    },
    emitInput(val) {
      this.$emit('input', val);
    },
    // for upload
    handleUploadRemove(file, fileList) {
      console.log('handleUploadRemove', file, fileList);
    },
    handleUploadPreview(file) {
      console.log('handleUploadPreview', file);
    },
    handleUploadSuccess(res, file) {
      file.name = res.data.filename;
      file.url = res.data.url;
      this.value = res.data.url;
      console.log('handleUploadSuccess', file);
      this.emitInput(file);
    },
    beforeUpload(file) {
      console.log('beforeUpload', file);
      const isJPG = file.type === 'image/jpeg';
      const isLt2M = file.size / 1024 / 1024 < 2;

      if (!isJPG) {
        this.$message.error('Avatar picture must be JPG format!');
      }
      if (!isLt2M) {
        this.$message.error('Avatar picture size can not exceed 2MB!');
      }
      return isJPG && isLt2M;
    },
    clipboardSuccess() {
      this.$message({
        message: 'Copy successfully',
        type: 'success',
        duration: 1500,
      });
    },
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
@import "~@/styles/mixin.scss";
.upload-container {
  width: 100%;
  position: relative;
  @include clearfix;
  .image-uploader {
    width: 35%;
    float: left;
  }
  .image-preview {
    width: 200px;
    height: 200px;
    position: relative;
    border: 1px dashed #d9d9d9;
    float: left;
    margin-left: 50px;
    .image-preview-wrapper {
      position: relative;
      width: 100%;
      height: 100%;
      img {
        width: 100%;
        height: 100%;
      }
    }
    .image-preview-action {
      position: absolute;
      width: 100%;
      height: 100%;
      left: 0;
      top: 0;
      text-align: center;
      color: #fff;
      opacity: 0;
      font-size: 20px;
      background-color: rgba(0, 0, 0, .5);
      transition: opacity .3s;
      cursor: pointer;
      line-height: 200px;
      .el-icon-delete {
        font-size: 36px;
      }
    }
    &:hover {
      .image-preview-action {
        opacity: 1;
      }
    }
  }
  .image-app-preview {
    width: 320px;
    height: 180px;
    position: relative;
    border: 1px dashed #d9d9d9;
    float: left;
    margin-left: 50px;
    .app-fake-conver {
      height: 44px;
      position: absolute;
      width: 100%; // background: rgba(0, 0, 0, .1);
      text-align: center;
      line-height: 64px;
      color: #fff;
    }
  }
}
</style>
