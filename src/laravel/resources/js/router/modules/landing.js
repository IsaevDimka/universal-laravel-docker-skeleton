const landingRoutes = {
    path: '/',
    name: 'landing',
    component: () => import('@/views/landing/index'),
    hidden: false,
    alwaysShow: false,
    redirect: '/',
    meta: {title: 'Home', icon: 'nested', noCache: true},
    children: [
        {
            path: '/',
            component: () => import('@/views/landing/HomePage'),
            name: 'home',
            meta: { title: 'Home page', noCache: true, breadcrumb: false},
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
            meta: { title: 'Feedback', noCache: true},
        },
        {
            path: '/test',
            component: () => import('@/views/landing/test'),
            name: 'test',
            meta: { title: 'test', noCache: true},
            hidden: true,
        },
        {
            path: '/privacy-policy',
            component: () => import('@/views/landing/privacyPolicy'),
            name: 'privacy_policy',
            meta: { title: 'Privacy Policy', noCache: true},
            hidden: true,
        },
        {
            path: '/terms',
            component: () => import('@/views/landing/terms'),
            name: 'terms',
            meta: { title: 'Terms of service', noCache: true},
            hidden: true,
        },
        {
            path: '/changelog',
            component: () => import('@/views/landing/changelog'),
            name: 'changelog',
            meta: { title: 'Changelog', noCache: true},
            hidden: true,
        },
    ],
};

export default landingRoutes;
