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
import {mapGetters} from 'vuex'
import request from "@/utils/request";

export default {
    data: () => ({
        loading: false,
        channels: [],
        messages: []
    }),
    computed: {
        ...mapGetters([
            'socketStatus',
            'token',
            'devDrawer',
        ])
    },
    watch: {
        socketStatus(to, from) {
            if (this.devDrawer) {
                this.$message({
                    showClose: true,
                    message: `socketStatus: ${from} | ${to}`,
                    type: 'success',
                    offset: 73,
                    duration: 5000,
                });
            }
        }
    },
    mounted() {
        this.connect();
    },
    beforeDestroy() {
        this.disconnect()
    },
    methods: {
        connect() {
            if (this.devDrawer) {
                this.$message({
                    showClose: true,
                    message: `connect socket.io`,
                    type: 'success',
                    offset: 73,
                    duration: 5000,
                });
            }
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
            if (this.devDrawer) {
                this.$message({
                    showClose: true,
                    message: `disconnect socket.io`,
                    type: 'success',
                    offset: 73,
                    duration: 5000,
                });
            }
            this.$echo.connector.socket.disconnect()
            this.channels = [];
        },
        init() {
            if (this.devDrawer) {
                this.$message({
                    showClose: true,
                    message: `init socket.io`,
                    type: 'success',
                    offset: 73,
                    duration: 5000,
                });
            }
            this.$echo.channel('system-events').listen('.App\\Events\\SystemMessage', e => {
                console.log('system-events', e);
                this.messages.push(e)
            })
            this.$echo.private('private-events').listen('.App\\Events\\PrivateMessage', e => {
                console.log('private-events', e);
                this.messages.push(e)
            })
            // this.$echo.channel('manual-create-order-events').listen('.App\\Events\\ManualCreateOrder', e => {
            //     console.log('manual-create-order-events', e);
            //     this.messages.push(e)
            // })
            this.channels = Object.keys(this.$echo.connector.channels)
        },
        sendMessage() {
            this.$message({
                showClose: true,
                message: `sendMessage socket.io`,
                type: 'success',
                offset: 73,
                duration: 5000,
            });
            request({
                url: '/webhook/test',
                method: 'get',
            })
                .then(response => {
                    console.log(response)
                })
                .catch(err => {
                    console.error(err)
                })

            // this.$store.dispatch('echo/sendMessage')
            // this.$echo.connector.socket.emit('Hello world')
            // this.$echo.connector.socket.emit('Hello world private-events', 'private-events')
        }
    }
}
</script>
