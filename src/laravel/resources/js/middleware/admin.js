import store from '~/store'

export default (to, from, next) => {

  const roles = store.getters && store.getters['auth/user'].roles

  const hasAdmin = roles.some(role => {
    return role.includes('root')
  })

  if (! hasAdmin) {
    next({ name: 'home' })
  } else {
    next()
  }

}
