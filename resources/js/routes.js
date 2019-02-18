import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from './components/Home';
import Login from './components/Auth/Login';
import Register from './components/Auth/Register';
import AuthClient from './components/Auth/Auth';

// users views
import UserList from './components/users/Index.vue';
import UserForm from './components/users/Form.vue';
import UserView from './components/users/View.vue';
import UsersCrud from './components/users/Crud.vue';

// roles views
import RoleList from './components/users/roles/Index.vue';

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    linkActiveClass: 'active',
    routes: [
        {
            path: '/',
            name: '',
            component: Home
        },
        {
            path: '/home',
            name: 'Home',
            component: Home
        },
        {
            path: '/login',
            name: 'Login',
            component: Login,
            meta: {
                forVisitors: true,
                breadcrumb: 'Login',
            },
        },
        {
            path: '/register',
            name: 'Register',
            component: Register,
            meta: {
                forVisitors: true,
                breadcrumb: 'Create account',
            },
        },
        {
            path: '/auth',
            name: 'Auth',
            component: AuthClient,
            meta: {
                forAuth: true,
                breadcrumb: 'Auth',
            },
        },
        // users section
        {
            path: '/people',
            component: UserList,
            name: 'Users',
            meta: {
                forAuth: true,
                breadcrumb: 'Users',
            },
        },
        {
            path: '/users/form',
            component: UserForm,
            name: 'Add/Edit user',
            meta: {
                forAuth: true,
                breadcrumb: 'Add/Edit user',
            },
        },
        {
            path: '/users/view',
            component: UserView,
            name: 'View user',
            meta: {
                forAuth: true,
                breadcrumb: 'User',
            },
        },
        {
            path: '/people/crud',
            component: UsersCrud,
            name: 'UsersCrud',
            meta: {
                forAuth: true,
                breadcrumb: 'Users',
            },
        },
        // roles section
        {
            path: '/roles',
            component: RoleList,
            name: 'Roles',
            meta: {
                forAuth: true,
                breadcrumb: 'Roles',
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