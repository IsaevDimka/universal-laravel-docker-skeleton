import Basic from '@/layout/basic';

const landingRoutes = {
    path: '',
    component: Basic,
    redirect: 'landing',
    children: [
        {
            path: 'landing',
            component: () => import('@/views/landing/index'),
            name: 'Landing',
            meta: {title: 'landing', icon: 'link', noCache: true},
        },
    ],
};

export default landingRoutes;
