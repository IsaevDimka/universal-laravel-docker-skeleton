import axios from 'axios'
import { MessageBox, Message } from 'element-ui'
import store from '@/store'
import { getToken } from '@/utils/auth'

// create an axios instance
const service = axios.create({
    baseURL: process.env.MIX_BASE_API, // url = base url + request url
    // withCredentials: true, // send cookies when cross-domain requests
    timeout: 60 * 1 * 1000, // request timeout, 1 min
})

// request interceptor
service.interceptors.request.use(
    config => {

        // const { csrf_token } = window.config;
        // if (csrf_token) {
        //     config.headers['X-CSRF-TOKEN'] = csrf_token
        //     console.log('csrf_token', csrf_token)
        // } else {
        //     console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
        // }

        if (store.getters.token) {
            // let each request carry token
            // ['X-Token'] is a custom headers key
            // please modify it according to the actual situation
            const token = getToken()
            config.headers['Authorization'] = `Bearer ${token}`
        }
        return config
    },
    error => {
        // do something with request error
        console.log(error) // for debug
        return Promise.reject(error)
    }
)

// response interceptor
service.interceptors.response.use(
    /**
     * If you want to get http information such as headers or status
     * Please return  response => response
     */

    /**
     * Determine the request status by custom code
     * Here is just an example
     * You can also judge the status by HTTP Status Code
     */
    response => {
        const res = response;

        // if the custom code is not 200, it is judged as an error.
        if (res.data.status !== 200) {

            Message({
                message: res.data.message || 'Error',
                type: 'error',
                duration: 5 * 1000
            })

            if (res.data.status === 401) {
                // to re-login
                MessageBox.confirm('You have been logged out, you can cancel to stay on this page, or log in again', 'Confirm logout', {
                    confirmButtonText: 'Re-Login',
                    cancelButtonText: 'Cancel',
                    type: 'warning'
                }).then(() => {
                    store.dispatch('user/resetToken').then(() => {
                        location.reload()
                    })
                })
            }
            return Promise.reject(new Error(res.data.message || 'Error'))
        } else {
            if (store.getters.devDrawer) {
                const { debug } = res.data;
                Message({
                    showClose: true,
                    message: `ENV: ${process.env.NODE_ENV} | ${JSON.stringify(debug)}`,
                    offset: 73,
                    duration: 5 * 1000,
                });
            }
            return res
        }
    },
    error => {
        if (error.response && error.response.data) {

            // debug
            if (store.getters.devDrawer) {
                if (error.response.data.errors !== undefined)
                {
                    Object.keys(error.response.data.errors).map((key) => {
                        console.log(`${key} : ${error.response.data.errors[key]}`);
                    });
                }
            }

            Message({
                message: `${error.response.data.message ?? 'Error'} (${error.response.data.status ?? error.response.status})`,
                type: 'error',
                duration: 5 * 1000
            })
            return Promise.reject(error);
        }
        Message({
            message: error.message,
            type: 'error',
            duration: 5 * 1000
        })
        return Promise.reject(error)
    }
)

export default service