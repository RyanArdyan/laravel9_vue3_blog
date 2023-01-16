import { createRouter, createWebHistory } from "vue-router";

// Import component--component
import About from '../pages/About.vue'
import Home from "../pages/Home.vue";
import Blog from "../pages/Blog.vue";
import Contact from "../pages/Contact.vue";
// ini detail blog
import SingleBlog from "../pages/SingleBlog.vue";
import Login from '../pages/Login.vue';
import Register from '../pages/Register.vue';
import Dashboard from '../pages/Dashboard.vue';
import CreateCategories from '../pages/categories/CreateCategories.vue';

const routes = [
    {
        path: "/",
        name: "Home",
        component: Home,
    },
    {
        path: "/about",
        name: "About",
        component: About,
    },
    {
        path: "/blog",
        name: "Blog",
        component: Blog,
    },
    {
        path: "/contact",
        name: "Contact",
        component: Contact,
    },
    {
        path: "/blog/:slug",
        name: "SingleBlog",
        component: SingleBlog,
        props: true,
    },
    {
        path: "/login",
        name: "Login",
        component: Login,
        meta: {requiresGuest: true}
    },
    {
        path: '/register',
        name: 'Register',
        component: Register,
        meta: {requiresGuest: true}
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: Dashboard,
        meta: {requiresAuth: true}
    }, 
    {
        path: '/categories/create',
        name: 'CreateCategories',
        component: CreateCategories,
        meta: {requiresAuth: true}
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from) => {
    // jika user berhasil login atau register maka di application > localStorage akan membuat key authenticated, lalu kita mengambilnya
    const authenticated = localStorage.getItem("authenticated");

    // jika user yang sudah login mencoba mengakses login atau register maka arahkan ke dashboard
    if (to.meta.requiresGuest && authenticated) {
        return {
            name: 'Dashboard'
        };
    // jika key authenticated tidak ada di localStorage maka
    // user belum login dan mencoba mengakses dashboard maka arahkan ke halaman dashboard
    } else if (to.meta.requiresAuth && !authenticated) {
        return {
            name: 'Login'
        };
    }
});

export default router;
