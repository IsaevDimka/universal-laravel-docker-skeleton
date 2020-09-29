<template>
  <div>
    <button
        class="bg-blue-500 text-white active:bg-blue-600 font-bold uppercase text-xs px-4 py-2 rounded shadow hover:shadow-md outline-none focus:outline-none mr-1 mb-1"
        type="button" style="transition: all .15s ease">
      <i class="fas fa-heart"></i> Small
    </button>

    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-teal-600 bg-teal-200 uppercase last:mr-0 mr-1">
      teal
    </span>
    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-green-600 bg-green-200 uppercase last:mr-0 mr-1">
      green
    </span>

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb flex">
        <li class="breadcrumb-item text-gray-600"><a href="#" class="text-gray-600 hover:text-purple-700 mx-2">Home</a>
        </li>
        <li class="breadcrumb-item text-gray-600"><a href="#"
                                                     class="text-gray-600 hover:text-purple-700 mx-2">Library</a></li>
        <li class="breadcrumb-item active text-purple-700 hover:text-purple-700 mx-2" aria-current="page">Data</li>
      </ol>
    </nav>
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
@import "@/styles/tailwind";

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