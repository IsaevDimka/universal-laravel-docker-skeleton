<template>
  <header class="bg-white shadow">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold leading-tight text-gray-900">
        {{ this.$route.meta.title }}
      </h1>
    </div>
  </header>
</template>

<script>
export default {
name: "tailwind-header"
}
</script>

<style lang="scss" scoped>
@import "@/styles/tailwind";
</style>