const landingRoutes = {
    path: '',
    name: 'landing',
    component: () => import('@/views/landing/index'),
    hidden: false,
    meta: {title: 'landing', icon: 'link', noCache: true},
};

export default landingRoutes;
