import Vue from 'vue';
import Router from 'vue-router';

/**
 * Layzloading will create many files and slow on compiling, so best not to use lazyloading on devlopment.
 * The syntax is lazyloading, but we convert to proper require() with babel-plugin-syntax-dynamic-import
 * @see https://doc.laravue.dev/guide/advanced/lazy-loading.html
 */

Vue.use(Router);

/* Layout */
import Layout from '@/layout';

/* Router for modules */
import landingRoutes from './modules/landing';
import elementUiRoutes from './modules/element-ui';
import componentRoutes from './modules/components';
import chartsRoutes from './modules/charts';
import tableRoutes from './modules/table';
import adminRoutes from './modules/admin';
import nestedRoutes from './modules/nested';
import errorRoutes from './modules/error';
import excelRoutes from './modules/excel';
import permissionRoutes from './modules/permission';

/**
 * Sub-menu only appear when children.length>=1
 * @see https://doc.laravue.dev/guide/essentials/router-and-nav.html
 **/

/**
* hidden: true                   if `hidden:true` will not show in the sidebar(default is false)
* alwaysShow: true               if set true, will always show the root menu, whatever its child routes length
*                                if not set alwaysShow, only more than one route under the children
*                                it will becomes nested mode, otherwise not show the root menu
* redirect: noredirect           if `redirect:noredirect` will no redirect in the breadcrumb
* name:'router-name'             the name is used by <keep-alive> (must set!!!)
* meta : {
    roles: ['admin', 'editor']   Visible for these roles only
    permissions: ['view menu zip', 'manage user'] Visible for these permissions only
    title: 'title'               the name show in sub-menu and breadcrumb (recommend set)
    icon: 'svg-name'             the icon show in the sidebar
    noCache: true                if true, the page will no be cached(default is false)
    breadcrumb: false            if false, the item will hidden in breadcrumb (default is true)
    affix: true                  if true, the tag will affix in the tags-view
  }
**/

export const constantRoutes = [
  {
    path: '/redirect',
    component: Layout,
    hidden: true,
    children: [
      {
        path: '/redirect/:path*',
        name: 'redirect',
        component: () => import('@/views/redirect/index'),
      },
    ],
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/login/index'),
    hidden: true,
  },
  {
    path: '/auth-redirect',
    name: 'auth_redirect',
    component: () => import('@/views/login/AuthRedirect'),
    hidden: true,
  },
  {
    path: '/404',
    name: 'page_404',
    component: () => import('@/views/error-page/404'),
    hidden: true,
  },
  {
    path: '/401',
    name: 'page_401',
    component: () => import('@/views/error-page/401'),
    hidden: true,
  },
  landingRoutes,
  {
    path: '/backend',
    component: Layout,
    redirect: '/backend/dashboard',
    children: [
      {
        path: 'dashboard',
        component: () => import('@/views/dashboard/index'),
        name: 'dashboard',
        meta: { title: 'dashboard', icon: 'dashboard', noCache: false },
      },
    ],
  },

  {
    path: '/backend/profile',
    component: Layout,
    redirect: '/profile/edit',
    children: [
      {
        path: 'edit',
        component: () => import('@/views/users/SelfProfile'),
        name: 'SelfProfile',
        meta: { title: 'userProfile', icon: 'user', noCache: true },
      },
    ],
  },
];

export const asyncRoutes = [
  permissionRoutes,
  componentRoutes,
  chartsRoutes,
  elementUiRoutes,
  nestedRoutes,
  tableRoutes,
  adminRoutes,
  {
    path: '/backend/documentation',
    component: Layout,
    redirect: '/documentation/index',
    meta: {
      roles: ['root'],
    },
    children: [
      {
        path: 'index',
        component: () => import('@/views/documentation/index'),
        name: 'Documentation',
        meta: { title: 'documentation', icon: 'documentation', noCache: true, roles: ['root'], },
      },
    ],
  },
  {
    path: '/backend/guide',
    component: Layout,
    redirect: '/guide/index',
    meta: {
      roles: ['root'],
    },
    children: [
      {
        path: 'index',
        component: () => import('@/views/guide/index'),
        name: 'Guide',
        meta: { title: 'guide', icon: 'guide', noCache: true,},
      },
    ],
  },
  {
    path: '/backend/theme',
    component: Layout,
    redirect: 'noredirect',
    meta: { roles: ['root'] },
    children: [
      {
        path: 'index',
        component: () => import('@/views/theme/index'),
        name: 'Theme',
        meta: { title: 'theme', icon: 'theme' },
      },
    ],
  },
  {
    path: '/backend/clipboard',
    component: Layout,
    redirect: 'noredirect',
    meta: { roles: ['root'] },
    children: [
      {
        path: 'index',
        component: () => import('@/views/clipboard/index'),
        name: 'ClipboardDemo',
        meta: { title: 'clipboardDemo', icon: 'clipboard', roles: ['root'] },
      },
    ],
  },
  errorRoutes,
  excelRoutes,
  {
    path: '/backend/zip',
    component: Layout,
    redirect: '/zip/download',
    alwaysShow: true,
    meta: { title: 'zip', icon: 'zip', roles: ['root'] },
    children: [
      {
        path: 'download',
        component: () => import('@/views/zip'),
        name: 'ExportZip',
        meta: { title: 'exportZip' },
      },
    ],
  },
  {
    path: '/backend/pdf',
    component: Layout,
    redirect: '/pdf/index',
    meta: { title: 'pdf', icon: 'pdf', permissions: ['view menu pdf'], roles: ['root'] },
    children: [
      {
        path: 'index',
        component: () => import('@/views/pdf'),
        name: 'Pdf',
        meta: { title: 'pdf' },
      },
    ],
  },
  {
    path: '/backend/pdf/download',
    component: () => import('@/views/pdf/Download'),
    hidden: true,
  },
  {
    path: '/backend/i18n',
    component: Layout,
    // meta: { permissions: ['view menu i18n'] },
    meta:{
      roles: ['root'],
    },
    children: [
      {
        path: 'index',
        component: () => import('@/views/i18n'),
        name: 'I18n',
        meta: { title: 'i18n', icon: 'international' },
      },
    ],
  },
  // {
  //   path: '/backend/external-link',
  //   component: Layout,
  //   children: [
  //     {
  //       path: 'http://github.com/IsaevDimka/universal-laravel-docker-skeleton',
  //       meta: { title: 'externalLink', icon: 'link' },
  //     },
  //   ],
  // },
  {
    path: '/backend/error-log',
    component: Layout,
    meta: {
      roles: ['root'],
    },
    children: [
      {
        path: 'log',
        component: () => import('@/views/error-log/index'),
        name: 'error_log',
        meta: { title: 'Error Log', icon: 'bug' }
      }
    ]
  },
  { path: '*', name: 'not_found', redirect: '/404', hidden: true },
];

const createRouter = () => new Router({
  mode: 'hash', // require service support | history, hash
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRoutes,
});

const router = createRouter();

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter();
  router.matcher = newRouter.matcher; // reset router
}

export default router;
