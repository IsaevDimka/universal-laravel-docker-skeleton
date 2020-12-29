import router from './router';
import store from './store';
import {Message} from 'element-ui';
import NProgress from 'nprogress'; // progress bar
import 'nprogress/nprogress.css'; // progress bar style
import {getToken} from '@/utils/auth';
import getPageTitle from '@/utils/get-page-title';
import defaultSettings from "@/settings";

NProgress.configure({showSpinner: false}); // NProgress Configuration

// NProgress.set(0.3); // установка конкретного процента
NProgress.inc(); // увеличение прогресса на случайный процент
NProgress.configure({ease: 'ease', speed: 500}); // конфигурация скорости загрузки и CSS easing
NProgress.configure({trickleRate: 0.02, trickleSpeed: 800});

function keyExists(key, search) {
    if (!search || (search.constructor !== Array && search.constructor !== Object)) {
        return false;
    }
    for (var i = 0; i < search.length; i++) {
        if (search[i] === key) {
            return true;
        }
    }
    return key in search;
}

// no redirect whitelist route name
const whiteList = [
    'login',
    'auth_redirect',
    'redirect',
    'page_404',
    'page_401',
    'not_found',
    'status',
    'oauth_callback',

    'landing',
    'home',
    'register',
    'feedback',
    'news',
    'news_list',
    'news_view',
    'test',
    'privacy_policy',
    'terms',
    'changelog'
];

router.beforeEach(async (to, from, next) => {

    // start progress bar
    NProgress.start();
    // set page title
    document.title = getPageTitle(to.meta.title);

    console.log('Current vue route | to: ', to);

    // determine whether the user has logged in
    const hasToken = getToken()
    if (hasToken) {
        if (to.name === 'login') {
            // if is logged in, redirect to the home page
            next({path: '/backend'});
        } else {
            // determine whether the user has obtained his permission roles through getInfo
            const hasRoles = store.getters.roles && store.getters.roles.length > 0;
            if (hasRoles) {
                next();
            } else {
                try {
                    // get user info
                    // note: roles must be a object array! such as: ['admin'] or ,['manager','editor']
                    const {data} = await store.dispatch('user/getInfo');
                    const {roles, permissions} = data;

                    // generate accessible routes map based on roles
                    const accessRoutes = await store.dispatch('permission/generateRoutes', {roles, permissions});
                    router.addRoutes(accessRoutes);
                    next({...to, replace: true});
                } catch (error) {
                    // remove token and go to login page to re-login
                    await store.dispatch('user/resetToken');
                    Message.error(error.message || 'Error');
                    next(`/login?redirect=${to.path}`);
                }
            }
        }
    } else {
        /* has no token*/

        if (!to.name) {
            next('/404');
            return;
        }

        if (keyExists(to.name, whiteList)) {
            //whiteList.indexOf(to.path) !== -1
            next();
            return;
        }
        // other pages that do not have permission to access are redirected to the login page.
        next(`/login?redirect=${to.path}`);
    }
});

router.afterEach((to, from) => {
    // set title
    const {title} = defaultSettings;
    document.title = to.meta.title ? `${to.meta.title} | ${title}` : title;
    // finish progress bar
    NProgress.done();
});
