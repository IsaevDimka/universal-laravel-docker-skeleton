<template>
  <news-detail :is-edit="true" />
</template>

<script>
import NewsDetail from './components/NewsDetail';

export default {
  name: 'EditNews',
  components: { NewsDetail },
};
</script>

