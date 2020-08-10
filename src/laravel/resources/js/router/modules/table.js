import Layout from '@/layout';

const tableRoutes = {
  path: '/table',
  component: Layout,
  redirect: '/table/complex-table',
  name: 'Complex Table',
  meta: {
    title: 'table',
    icon: 'table',
    permissions: ['view menu table'],
  },
  children: [
    {
      path: 'drag-table',
      component: () => import('@/views/table/DragTable'),
      name: 'DragTable',
      meta: { title: 'dragTable' },
    },
    {
      path: 'inline-edit-table',
      component: () => import('@/views/table/InlineEditTable'),
      name: 'InlineEditTable',
      meta: { title: 'inlineEditTable' },
    },
    {
      path: 'complex-table',
      component: () => import('@/views/table/ComplexTable'),
      name: 'ComplexTable',
      meta: { title: 'complexTable' },
    },
  ],
};
export default tableRoutes;
