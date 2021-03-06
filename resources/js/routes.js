import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from './components/dashboard/Home';
import Login from './components/dashboard/Auth/Login';
import Register from './components/dashboard/Auth/Register';
import AuthClient from './components/dashboard/Auth/Auth';

// profile
import Profile from './components/dashboard/Profile.vue';

// users views
import UsersCrud from './components/dashboard/users/Crud.vue';

// roles views
import RolesCrud from './components/dashboard/roles/Crud.vue';

// policies views
import PoliciesCrud from './components/dashboard/policies/Crud.vue';

// templates views
import TemplatesCrud from './components/dashboard/templates/Crud.vue';

// pages views
import PagesCrud from './components/dashboard/pages/Crud.vue';

// categories views
import CategoriesCrud from './components/dashboard/categories/Crud.vue';

// galleries views
import GalleriesCrud from './components/dashboard/galleries/Crud.vue';

// menus views
import MenusCrud from './components/dashboard/menus/Crud.vue';

// settings views
import CMSSettings from './components/dashboard/Settings';

// frontend page view
import Page from './components/Page.vue';
import Index from './components/Index.vue';

// 404 page view
import Page404 from './components/404.vue';

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    linkActiveClass: 'active',
    routes: [
        {
            path: '/dashboard',
            name: '',
            component: Home,
            meta: {
                breadcrumb: 'Home',
            },
        },
        {
            path: '/dashboard/login',
            name: 'Login',
            component: Login,
            meta: {
                forVisitors: true,
                breadcrumb: 'Login',
            },
        },
        {
            path: '/dashboard/register',
            name: 'Register',
            component: Register,
            meta: {
                forVisitors: true,
                breadcrumb: 'Create account',
            },
        },
        {
            path: '/dashboard/profile/tokens',
            name: 'Tokens',
            component: AuthClient,
            meta: {
                forAuth: true,
                breadcrumb: 'Tokens',
            },
        },
        {
            path: '/dashboard/profile',
            name: 'Profile',
            component: Profile,
            meta: {
                forAuth: true,
                breadcrumb: 'Profile',
            },
        },
        // menus section
        {
            path: '/dashboard/menus',
            component: MenusCrud,
            name: 'Menus',
            meta: {
                forAuth: true,
                breadcrumb: 'Menus',
            },
        },
        // users section
        {
            path: '/dashboard/people',
            component: UsersCrud,
            name: 'Users',
            meta: {
                forAuth: true,
                breadcrumb: 'Users',
            },
        },
        // roles section
        {
            path: '/dashboard/people/roles',
            component: RolesCrud,
            name: 'Roles',
            meta: {
                forAuth: true,
                breadcrumb: 'Roles',
            },
        },
        // policies section
        {
            path: '/dashboard/people/access-policies',
            component: PoliciesCrud,
            name: 'Policies',
            meta: {
                forAuth: true,
                breadcrumb: 'Policies',
            },
        },

        // pages section
        {
            path: '/dashboard/cms/pages',
            component: PagesCrud,
            name: 'Pages',
            meta: {
                forAuth: true,
                breadcrumb: 'Pages',
            },
        },

        // categories section
        {
            path: '/dashboard/cms/categories',
            component: CategoriesCrud,
            name: 'Categories',
            meta: {
                forAuth: true,
                breadcrumb: 'Categories',
            },
        },

        // galleries section
        {
            path: '/dashboard/cms/galleries',
            component: GalleriesCrud,
            name: 'Galleries',
            meta: {
                forAuth: true,
                breadcrumb: 'Galleries',
            },
        },

        // templates section
        {
            path: '/dashboard/cms/templates',
            component: TemplatesCrud,
            name: 'Templates',
            meta: {
                forAuth: true,
                breadcrumb: 'Templates',
            },
        },

        // settings section
        {
            path: '/dashboard/cms/settings',
            component: CMSSettings,
            name: 'Settings',
            meta: {
                forAuth: true,
                breadcrumb: 'Settings',
            },
        },

        // frontend section
        {
            path: '/',
            component: Page,
            meta: {
                forVisitors: true,
            },
        },
        {
            path: '/404',
            component: Page404,
            meta: {
                forVisitors: true,
            },
        },
        {
            path: '*',
            component: Index,
            meta: {
                forVisitors: true,
            },
        },
    ]
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.forVisitors)) {
        /*if (Vue.auth.isAuthenticated()) {
            next({
                name: 'Home'
            })
        } else*/ next()
    }
    else if (to.matched.some(record => record.meta.forAuth)) {
        if (!Vue.auth.isAuthenticated()) {
            next({
                path: '/dashboard/login',
                name: 'Login'
            })
        } else next()
    }
    else  next()
});

export default router;

/*

 Client ID: 1
 Client secret: 07mXrZ7SVnwS2xQuCmELq2vKVB3wrSM7tbk6uckX
 Password grant client created successfully.
 Client ID: 2
 Client secret: Q2NFyuyGBLefCr8s7Y0sdE4ssKHSQRFUBeeOI8rc

 */

/*
 What should we name the password grant client? [MandeCMS Password Grant Client]:
 > mandecpass

 Password grant client created successfully.
 Client ID: 3
 Client secret: NTXwfQrnMsxyy3d1jn3AJFkZPdQqIcn21UTJYbAP

 */

/*
personal access token

 eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjE4MGMwMzk4ZDQ5YzU0YzI0OGE0MDE1ZTczYWI2Y2Y1YzE5NmI5OGFjNTdkZWZmZGE4NzlhZGM0MzAzNDViNTViNmM0M2U1YzdkYzhmMjk2In0.eyJhdWQiOiIxIiwianRpIjoiMTgwYzAzOThkNDljNTRjMjQ4YTQwMTVlNzNhYjZjZjVjMTk2Yjk4YWM1N2RlZmZkYTg3OWFkYzQzMDM0NWI1NWI2YzQzZTVjN2RjOGYyOTYiLCJpYXQiOjE1NDk4MzIwNjIsIm5iZiI6MTU0OTgzMjA2MiwiZXhwIjoxNTgxMzY4MDYyLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.XraXRQZ0mlzOEQArC3oSoKN-4abrcSEhxzXkI1FTqM9ZWjWECPR5A8oX2EHbfYBg7CuVfoatq0jsHcYqWtIlGR9hq3A6tlhtWgiLY6HzWx5D2O3WXQJS79JZoSh43R9E9PEolYnPzRKnH25L0IB4Q15r4bqtVLz2IuV0IlbyBK288JTA1o1ak4iQ8tBGKwbFZQKAbIGLn60KfsuOKr_IfJmDigcf39zSvnxPUgzBzWvFOPu7pgsy2TsBThaIYcyqKeYmIWv0qjt45I5KMxgPeLoH6UpgrHUaqNUEp3GbqmVMkSK1HQJINlZbE6yn9zGwWSRZ80_hAPuO__WXCrxAxIrC5wkmK1BmOHbkGcaEnZomro53KCpgehxa8Stb9i4txEe6GWf8mHNXUxprphQoK73gpiq3YAs0nba1kiAtyUCoi9VXfyY1Bd-niphyv_PMj141SUlL3ykrszvZ-aDniOYvLUjsVsZw1gLD0rZsXfCtQLnLCW-jx5_XzeZFOXvfZFzeAUVAMjx_1RforNrLLCQ16TuvOiB4sGwGbo8iBu1IS4s2hBnqlfcFHeokV_MwyLst43VRxTDZJi8ydbquWd6-3wrfMJickFYCpiHaX48Xj-MbWkVHt8F5A58H3XjfNgKe_Ru28F_XnhCSRqjWZ7d5DOIdmRZl5xsNCPrwxDc
 */

/*
oauth:

name: mandec
url: http://mandecms.test/login
secret: xmoah81trlgdEoLLRvTx5mHy9lpmWZIbMlQNotPu
 */