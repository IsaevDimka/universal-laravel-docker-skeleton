<template>
  <el-switch
    v-model="devMode"
    inactive-text="dev"
    @change="handleDevModeSwitch"
  />
</template>

<script>
import Cookies from 'js-cookie'
export default {
  data: () => ({
    devMode: false
  }),
  mounted() {
    this.devMode = Cookies.get('devmode') === 'true'
  },
  methods: {
    handleDevModeSwitch() {
      this.$store.dispatch('dev/devModeSwitch', this.devMode)
    }
  }
}
</script>
