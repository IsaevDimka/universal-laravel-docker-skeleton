const getters = {
  devDrawer: state => state.dev.drawer,
  sidebar: state => state.app.sidebar,
  language: state => state.app.language,
  locales: state => state.app.locales,
  size: state => state.app.size,
  device: state => state.app.device,
  visitedViews: state => state.tagsView.visitedViews,
  cachedViews: state => state.tagsView.cachedViews,
  userId: state => state.user.data.id,
  token: state => state.user.token,
  avatar: state => state.user.data.avatar ?? 'https://wpimg.wallstcn.com/f778738c-e4f8-4870-b634-56703b4acafe.gif?imageView2/1/w/80/h/80',
  user: state => state.user.data,
  // introduction: state => state.user.introduction,
  roles: state => state.user.roles,
  permissions: state => state.user.permissions,
  permissionRoutes: state => state.permission.routes,
  addRoutes: state => state.permission.addRoutes,
  errorLogs: state => state.errorLog.logs,
  socketStatus: state => state.echo.socketStatus
};
export default getters;
