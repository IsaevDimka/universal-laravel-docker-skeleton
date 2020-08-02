import store from '~/store'

export default {
  inserted(el, binding, vnode) {
    const { value } = binding
    const permissions = store.getters['auth/user'] && store.getters['auth/user'].permissions

    if (value && value instanceof Array && value.length > 0) {
      let permissionBinding = value;
      let hasPermission = false;

      if (permissions !== null){
        hasPermission = permissions.some(role => {
          let isTrue = false
          if (permissionBinding.includes(role)) {
            isTrue = true
          }
          return isTrue
        })
      }

      if (!hasPermission) {
        el.parentNode && el.parentNode.removeChild(el)
      }
    } else {
      throw new Error(`need permissions! Like v-permission="['api','beta','debug']"`)
    }
  }
}
