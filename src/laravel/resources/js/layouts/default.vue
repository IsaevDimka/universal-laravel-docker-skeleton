<template>
  <div class="main-layout">
    <navbar />

    <div class="container mt-4">
      <child />
    </div>

    <Footer></Footer>

  </div>
</template>

<script>
import Navbar from '~/components/Navbar'
import Footer from "../components/Footer";

export default {
  name: 'MainLayout',

  data: () => ({
    config: window.config
  }),
  components: {
    Footer,
    Navbar,
  }
}
</script>
