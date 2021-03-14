<template>
  <el-tooltip placement="top" content="Click to copy" :disabled="isDisabled">
    <el-link
      v-clipboard:copy="copy"
      v-clipboard:success="toClipboard"
      icon="el-icon-copy-document"
      :underline="false"
      :disabled="isDisabled"
      :type="isDisabled ? 'info' : 'primary'"
    />
  </el-tooltip>
</template>

<script>
import clipboard from '@/directive/clipboard/index.js' // use clipboard by v-directive
export default {
  directives: { clipboard },
  props: {
    copy: {
      type: [String, Number],
      required: false,
      default: null,
    }
  },
  computed: {
    isDisabled() {
      return !this.copy
    }
  },
  methods: {
    toClipboard() {
      this.$message.success(`Buffer copy: ${this.copy}`)
    }
  }
}
</script>
