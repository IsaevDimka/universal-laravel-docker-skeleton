import '@/bootstrap';
import { Message } from 'element-ui';
import { getToken } from '@/utils/auth';
import store from '@/store'

// Create axios instance
const service = window.axios.create({
  baseURL: process.env.MIX_BASE_API,
  timeout: 5000, // Request timeout
});

// Request intercepter
service.interceptors.request.use(
  config => {
      
    if (store.getters.token) {
      // let each request carry token
      // ['X-Token'] is a custom headers key
      // please modify it according to the actual situation
      const token = getToken()
      config.headers['Authorization'] = `Bearer ${token}`
    }

    // const locale = store.getters['lang/locale']
    // if (locale) {
    //   request.headers.common['Accept-Language'] = locale
    // }

    return config;
  },
  error => {
    // Do something with request error
    console.log(error); // for debug
    Promise.reject(error);
  }
);

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
      const { data: res, status } = response

      if (status === 200) {
        return res
      } else {
        Message({
          message: res.message || 'Error',
          type: 'error',
          duration: 5 * 1000
        })
        return Promise.reject(new Error(res.message || 'Error'))
      }
    },
    error => {
      const { data } = error.response
      const rejectData = errorFilter(data)
      return Promise.reject(rejectData)
    }
)

export default service
