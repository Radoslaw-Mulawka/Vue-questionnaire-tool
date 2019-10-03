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
// import elementUiRoutes from './modules/element-ui';
// import componentRoutes from './modules/components';
// import chartsRoutes from './modules/charts';
// import tableRoutes from './modules/table';
import adminRoutes from './modules/admin';
// import nestedRoutes from './modules/nested';
import errorRoutes from './modules/error';
// import excelRoutes from './modules/excel';
// import permissionRoutes from './modules/permission';

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
    roles: ['admin', 'editor']   will control the page roles (you can set multiple roles)
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
        component: () => import('@/views/redirect/index'),
      },
    ],
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/login/index'),
    hidden: false,
  },
  {
    path: '/registration',
    component: () => import('@/views/login/index'),
    hidden: true,
    meta: { title: 'registration' },
  },
  {
    path: '/sending-response',
    component: () => import('@/views/login/index'),
    hidden: true,
    name: 'sending-response',
    meta: { title: 'sending-response' },
  },
  {
    path: '/verify/:hash',
    component: () => import('@/views/login/index'),
    hidden: true,
    name: 'verify',
    meta: { title: 'verify' },
  },
  {
    path: '/password-reset-step-1',
    component: () => import('@/views/login/index'),
    hidden: true,
    name: 'password-reset-step-1',
    meta: { title: 'password-reset-step-1' },
  },
  {
    path: '/password-reset-step-2',
    component: () => import('@/views/login/index'),
    hidden: true,
    name: 'password-reset-step-2',
    meta: { title: 'password-reset-step-2' },
  },
  {
    path: '/password/reset/:hash',
    component: () => import('@/views/login/index'),
    hidden: true,
    name: 'password/reset',
    meta: { title: 'password/reset' },
  },
  {
    path: '/sendagain',
    component: () => import('@/views/login/index'),
    hidden: true,
    name: 'sendagain',
    meta: { title: 'sendagain' },
  },
  {
    path: '/auth-redirect',
    component: () => import('@/views/login/AuthRedirect'),
    hidden: true,
  },
  {
    path: '/404',
    redirect: { name: 'Page404' },
    component: () => import('@/views/error-page/404'),
    hidden: true,
  },
  {
    path: '/401',
    component: () => import('@/views/error-page/401'),
    hidden: true,
  },
  {
    path: '',
    component: Layout,
    redirect: 'dashboard',
    hidden: false,
    children: [
      {
        path: 'dashboard',
        component: () => import('@/views/dashboard/index'),
        name: 'Dashboard',
        meta: { title: 'dashboard', icon: 'dashboard', noCache: false },
      },
    ],
  },
  {
    path: '/campaigns',
    component: Layout,
    hidden: false,
    children: [
      {
        path: '',
        component: () => import('@/views/campaigns/index'),
        name: 'campaign',
        meta: { title: 'Kampanie', icon: 'calendar', noCache: false },
      },
    ],
  },
  {
    path: '/campaigns/:id',
    component: Layout,
    hidden: true,
    // meta: { title: 'Edycja Kampanii', icon: 'plus-circle', noCache: false },
    children: [
      {
        path: '',
        component: () => import('@/views/campaign_creation/index'),
        name: 'campaign-edit',
        meta: { title: 'Edycja Kampanii', icon: 'plus-circle', noCache: false },
      },
    ],
  },
  {
    path: '/results/:id',
    component: Layout,
    hidden: true,
    // meta: { title: 'Edycja Kampanii', icon: 'plus-circle', noCache: false },
    children: [
      {
        path: '',
        component: () => import('@/views/campaign_results/index'),
        name: 'campaign-result',
        meta: { title: 'Wyniki Kampanii', icon: 'plus-circle', noCache: false },
      },
    ],
  },
  {
    path: '/new-campaign',
    component: Layout,
    hidden: false,
    children: [
      {
        path: '', // /new-campaign
        component: () => import('@/views/campaign_creation/index'),
        name: 'new-campaign',
        meta: { title: 'Dodaj kampanię', icon: 'plus-circle', noCache: false },
      },
    ],
  },
  {
    path: '/profile',
    component: Layout,
    hidden: true,
    children: [
      {
        path: '',
        component: () => import('@/views/user_profile/index'),
        name: 'profile',
        meta: { title: 'Profil użytkownika', icon: 'plus-circle', noCache: false },
      },
    ],
  },
];

export const asyncRoutes = [
  adminRoutes,
  errorRoutes,
  {
    path: '*', redirect: '/404', hidden: true,
  },
];

const createRouter = () => new Router({
  mode: 'history', // require service support
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
