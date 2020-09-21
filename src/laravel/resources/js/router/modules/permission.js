import Layout from '@/layout';

const permissionRoutes = {
  path: '/backend/permission',
  component: Layout,
  redirect: '/permission/index',
  alwaysShow: true, // will always show the root menu
  meta: {
    title: 'permission',
    icon: 'lock',
    roles: ['root'],
  },
  children: [
    {
      path: 'page',
      component: () => import('@/views/permission/Page'),
      name: 'PagePermission',
      meta: {
        title: 'pagePermission',
        // permissions: ['manage permission'],
        test: true,
      },
    },
    {
      path: 'directive',
      component: () => import('@/views/permission/Directive'),
      name: 'directivePermission',
      meta: {
        title: 'directivePermission',
        // if do not set roles neither permissions, means: this page does not require permission
      },
    },
  ],
};

export default permissionRoutes;
