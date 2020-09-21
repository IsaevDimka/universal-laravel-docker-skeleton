<template>
  <news-detail :is-edit="false" />
</template>

<script>
import NewsDetail from './components/NewsDetail';

export default {
  name: 'CreateNews',
  components: { NewsDetail },
};
</script>

