<template>
  <div class="container">
    <h1>Status page</h1>
    <status-card/>
  </div>
</template>

<script>
import StatusCard from "@/components/StatusCard"
export default {
  name: 'StatusPage',
  components: {StatusCard},
};
</script>
