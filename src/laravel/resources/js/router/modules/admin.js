/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const adminRoutes = {
  path: '/backend/administrator',
  component: Layout,
  redirect: '/administrator/users',
  name: 'Administrator',
  alwaysShow: true,
  meta: {
    title: 'administrator',
    icon: 'admin',
    roles: ['admin', 'root'],
    // permissions: ['view menu administrator'],
  },
  children: [
    /** User managements */
    {
      path: 'users/edit/:id(\\d+)',
      component: () => import('@/views/users/UserProfile'),
      name: 'UserProfile',
      meta: { title: 'userProfile', noCache: true },
      hidden: true,
    },
    {
      path: 'users',
      component: () => import('@/views/users/List'),
      name: 'UserList',
      meta: { title: 'users', icon: 'user' },
    },
    /** Role and permission */
    {
      path: 'roles',
      component: () => import('@/views/role-permission/List'),
      name: 'RoleList',
      meta: { title: 'rolePermission', icon: 'role'},
    },
    {
      path: 'news/create',
      component: () => import('@/views/news/Create'),
      name: 'CreateNews',
      meta: { title: 'CreateNews', icon: 'edit' },
      hidden: true,
    },
    {
      path: 'news/edit/:id(\\d+)',
      component: () => import('@/views/news/Edit'),
      name: 'EditNews',
      meta: { title: 'EditNews', noCache: true },
      hidden: true,
    },
    {
      path: 'news',
      component: () => import('@/views/news/List'),
      name: 'NewsList',
      meta: { title: 'articleList', icon: 'list' },
    },
  ],
};

export default adminRoutes;
