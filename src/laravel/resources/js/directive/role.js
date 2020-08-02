import store from '~/store'

export default {
  inserted(el, binding, vnode) {
    const { value } = binding
    const roles = store.getters['auth/user'] && store.getters['auth/user'].roles

    if (value && value instanceof Array && value.length > 0) {
      let rolesBinding = value
      let hasRole = false;

      if (roles !== null){
        hasRole = roles.some(role => {
          let isTrue = false
          if (rolesBinding.includes(role)) {
            isTrue = true
          }
          return isTrue
        })
      }

      if (!hasRole) {
        el.parentNode && el.parentNode.removeChild(el)
      }
    } else {
      throw new Error(`need roles! Like v-role="['admin','root']"`)
    }
  }
}
