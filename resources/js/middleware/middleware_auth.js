export default function middleware_auth(Vue) {
    console.log(Vue.router.app);
    if (!Vue.router.app.$auth.isAuthed()) {
        console.log('no user data in local storate');
        return router.push({ name: `Login` });
    }

    console.log('user data in local storage exists');
    return next();
}