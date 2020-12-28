<template>
  <div>
    <h1>Test page</h1>

  </div>
</template>

<script>
import request from "@/utils/request";
import clipboard from '@/directive/clipboard/index.js'; // use clipboard by v-directive
export default {
  name: "test",
  directives: {
    clipboard,
  },
  components: {},
  data() {
    return {
      loading: false,
    }
  },
  created() {
  },
  computed: {},
  watch: {},
  methods: {
    clipboardSuccess() {
      this.$message({
        message: 'Copy successfully',
        type: 'success',
        duration: 1500,
      });
    },
  },
}
</script>

<style lang="scss" scoped>

.breadcrumb .breadcrumb-item {
  position: relative;
}
.breadcrumb .breadcrumb-item:first-child {
  margin-left: 0 !important;
}
.breadcrumb .breadcrumb-item:not(:last-child):after {
  content: '/';
}
</style>