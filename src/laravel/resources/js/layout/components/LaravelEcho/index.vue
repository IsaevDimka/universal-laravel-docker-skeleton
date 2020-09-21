<template>
  <debagger-pannel class="pt-2 pb-2">
    <el-row>
      <el-col>
        <el-button
          :loading="loading"
          :type="socketStatus ? 'danger' : 'success'"
          size="mini"
          @click="connect"
        >
          {{ socketStatus ? 'Disconnect' : 'Connect' }} socket
        </el-button>
        <el-button
          v-if="socketStatus"
          type="primary"
          size="mini"
          @click="sendMessage"
        >
          Send message
        </el-button>
      </el-col>
      <el-col :lg="12">
        <h3>channels:</h3>
        <pre>{{ channels }}</pre>
      </el-col>
      <el-col :lg="12">
        <h3>messages ({{ messages.length }}):</h3>
        <pre>{{ messages }}</pre>
      </el-col>
    </el-row>
  </debagger-pannel>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  data: () => ({
    loading: false,
    channels: [],
    messages: []
  }),
  computed: {
    ...mapGetters([
      'socketStatus',
      'token'
    ])
  },
  watch: {
    socketStatus(to, from) {
      console.log(to, from)
    }
  },
  mounted() {
    this.connect()
  },
  beforeDestroy() {
    this.disconnect()
  },
  methods: {
    connect() {
      if (!this.socketStatus) {
        this.loading = true
        this.$store.dispatch('echo/connect')
          .then(() => this.init())
          .catch(e => console.log(e))
          .finally(() => {
            this.loading = false
          })
      } else {
        this.disconnect()
      }
    },
    disconnect() {
      this.$echo.connector.socket.disconnect()
      this.channels = []
    },
    init() {
      this.$echo.channel('system-name').listen('SystemMessage', e => {
        console.log(e);
      })

      this.$echo.private('events').listen('PrivateMessage', e => {
        this.messages.push(e)
      })
      this.channels = Object.keys(this.$echo.connector.channels)
    },
    sendMessage() {
      fetch('http://localhost/api/v1/webhook/test', {
        method: 'GET',
        headers: {
          authorization: `Bearer ${this.token}`
        }
      })
        .then(response => {
          console.log(response)
        })
        .catch(err => {
          console.error(err)
        })

      this.$store.dispatch('echo/sendMessage')
      // this.$echo.connector.socket.emit('Hello world')
      this.$echo.connector.socket.emit('Hello world private-events', 'private-events')
    }
  }
}
</script>
