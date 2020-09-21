<template>
  <div class="container mt-5 mb-3">
    <el-alert
        title="Dev version"
        type="info"
        :closable="false"
        show-icon>
      <p>The site is under development and testing.</p>
      <p>Email: <el-link type="primary" :href="`mailto:${support_email}`">{{ support_email }}</el-link> </p>
    </el-alert>
  </div>
</template>

<script>
export default {
  data(){
    return {
      support_email: 'support@mail.com',
    };
  },
}
</script>