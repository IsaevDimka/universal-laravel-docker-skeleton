<template>
    <div class=" clearfix">
      <h1>{{ $t('under_construction') }}</h1>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
  name: 'DashboardDemo',
  data() {
    return {
    };
  },
  computed: {
    ...mapGetters([
      'user',
      'roles',
    ]),
  },
};
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
</style>
