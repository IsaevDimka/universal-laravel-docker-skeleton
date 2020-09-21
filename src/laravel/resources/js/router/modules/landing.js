const landingRoutes = {
    path: '/',
    name: 'landing',
    component: () => import('@/views/landing/index'),
    hidden: false,
    alwaysShow: false,
    redirect: '/about',
    meta: {title: 'Главная', icon: 'nested', noCache: true},
    children: [
        {
            path: '/about',
            component: () => import('@/views/landing/AboutPage'),
            name: 'about',
            meta: { title: 'About', noCache: true, breadcrumb: false},
        },
        {
            path: '/register',
            component: () => import('@/views/landing/Register'),
            name: 'register',
            meta: { title: 'Register', noCache: true},
        },
        {
            path: '/news',
            name: 'news',
            component: () => import('@/views/landing/news/index'),
            redirect: '/news/list',
            meta: { title: 'News', noCache: true},
            children: [
                {
                    path: 'list',
                    component: () => import('@/views/landing/news/list'),
                    name: 'news_list',
                    meta: { title: 'News', noCache: true},
                },
                {
                    path: ':id(\\d+)',
                    component: () => import('@/views/landing/news/view'),
                    name: 'news_view',
                    meta: { title: 'News view', noCache: true },
                    hidden: true,
                },
            ],
        },
        {
            path: '/feedback',
            component: () => import('@/views/landing/Feedback'),
            name: 'feedback',
            meta: { title: 'Написать нам', noCache: true},
        },
        {
            path: '/test',
            component: () => import('@/views/landing/test'),
            name: 'test',
            meta: { title: 'test', noCache: true},
            hidden: true,
        },
    ],
};

export default landingRoutes;
