import router from './router';
import store from './store';
import { Message } from 'element-ui';
import NProgress from 'nprogress'; // progress bar
import 'nprogress/nprogress.css'; // progress bar style
import { getToken } from '@/utils/auth';
import getPageTitle from '@/utils/get-page-title';

NProgress.configure({ showSpinner: false }); // NProgress Configuration


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
  'landing',
  'page_404',
  'page_401',
  'not_found',
];

router.beforeEach(async(to, from, next) => {
  // start progress bar
  NProgress.start();
  // set page title
  document.title = getPageTitle(to.meta.title);

// determine whether the user has logged in
  const hasToken = getToken()

  if (hasToken) {
    if (to.path === '/login') {
      // if is logged in, redirect to the home page
      next({ path: '/backend' });
      NProgress.done();
    } else {
      // determine whether the user has obtained his permission roles through getInfo
      const hasRoles = store.getters.roles && store.getters.roles.length > 0;
      if (hasRoles) {
        next();
      } else {
        try {
          // get user info
          // note: roles must be a object array! such as: ['admin'] or ,['manager','editor']
          const { data } = await store.dispatch('user/getInfo');
          const { roles, permissions } = data;

          // generate accessible routes map based on roles
          const accessRoutes = await store.dispatch('permission/generateRoutes', { roles, permissions });
          router.addRoutes(accessRoutes);
          next({ ...to, replace: true });
        } catch (error) {
          // remove token and go to login page to re-login
          await store.dispatch('user/resetToken');
          Message.error(error.message || 'Has Error');
          next(`/login?redirect=${to.path}`);
          NProgress.done();
        }
      }
    }
  } else {
    /* has no token*/

    if(!to.name)
    {
      // alert('go 404');
      next('/404');
      NProgress.done();
      return;
    }

    if (keyExists(to.name, whiteList)) {
      // alert(`go whitelist ${to.name} | ${keyExists(to.name, whiteList)}`);
      next();
      NProgress.done();
      return;
    }
      // other pages that do not have permission to access are redirected to the login page.
      // alert('go login');
      next(`/login?redirect=${to.path}`);
      NProgress.done();
    }
});

router.afterEach(() => {
  // finish progress bar
  NProgress.done();
});
