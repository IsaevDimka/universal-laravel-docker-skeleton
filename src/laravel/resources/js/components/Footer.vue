<template>
    <div>
        <div class="container mt-4" v-permission="['debug']" v-if="config.debug">
            <card title="debug">
                <pre>
                    {{ config }}
                </pre>
            </card>
        </div>
        <footer class="footer mt-auto py-3">
            <div class="container">
                <span class="text-muted">{{ config.appName }} {{ config.version }} | {{ config.environment }}</span>
                <span v-role="['root', 'admin']"> |
                    <router-link :to="{ name: 'backend' }">Backend</router-link>
                </span>
            </div>
        </footer>
    </div>
</template>

<script>
    import VButton from "./Button";
    export default {
        components: {VButton},
        data: () => ({
            config: window.config,
        }),
    }
</script>

<style scoped>
    pre {
        font-family: Ubuntu,monospace;
        font-size: 12px;
        color: #212529;
        margin-top: 0;
        margin-bottom: 1rem;
        overflow: auto;
        display: block;
    }
</style>
