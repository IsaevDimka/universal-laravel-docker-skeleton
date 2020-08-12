<template>
  <div
      v-if="devDrawer"
      class="dev-drawer"
      :style="{ height }"
  >
    <div class="app-container">
      <el-card class="box-card">
        <div slot="header" class="clearfix">
          <span>Debug</span>
          <el-button v-if="json" style="float: right; padding: 3px 0" type="text" @click="handleCopy(json, $event)">Copy</el-button>
        </div>
        <div class="text item">
          <pre>
            <slot/>
          </pre>
          <json-editor ref="jsonEditor" v-model="json" v-if="json" />
        </div>
      </el-card>

    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import clip from '@/utils/clipboard';
import JsonEditor from '@/components/JsonEditor';
export default {
  components: { JsonEditor },
  props: {
    json: '',
    height: {
      type: String,
      default: () => ('initial')
    }
  },
  computed: {
    ...mapGetters([
      'devDrawer'
    ])
  },
  methods: {
    handleCopy(text, event) {
      clip(text, event);
    },
  }
}
</script>
