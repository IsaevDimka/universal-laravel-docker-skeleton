<template>
  <div class="container">

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
  components: {
  },
  data() {
    return {
      loading: false,
    }
  },
  created() {
  },
  computed: {
  },
  watch: {
  },
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

<style>

</style>