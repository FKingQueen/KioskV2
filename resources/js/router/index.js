import { createRouter, createWebHistory } from 'vue-router'

// Authentication
import Login from '../components/auth/login.vue'
import Register from '../components/auth/register.vue'

// Main
import Main from '../components/main.vue'

// User
import Dashboard from '../components/user/dashboard.vue'

// Consumer
import PurchaseHistory from '../components/user/consumer/purchaseHistory.vue'
import ProductInfo from '../components/user/consumer/productInfo.vue'
import CheckOut from '../components/user/consumer/checkOut.vue'
import PreOrder from '../components/user/consumer/preOrder.vue'
import Cart from '../components/user/consumer/cart.vue'

// Cashier
import CashierPreOrder from '../components/user/cashier/cashierPreOrder.vue'


const routes = [
    {
        name: 'login',
        path: '/login',
        component: Login,
    },
    {
        name: 'register',
        path: '/register',
        component: Register,
    },
    {
        path: '/',
        name: 'main',
        component: Main,
        children: [
            {
                name: 'dashboard',
                path: '/',  // Relative path
                component: Dashboard,
            },
            {
                name: 'productInfo',
                path: '/productInfo/:id',  // Relative path
                component: ProductInfo,
                props:true
            },
            // {
            //     name: 'moreArticle',
            //     path: '/:article',
            //     component: moreArticle ,
            //     props:true 
            // },
        ]
    },
    {
        path: '/user',
        name: 'user',
        component: Main,
        meta: {
            requiresAuth: true,
        },
        children: [
            {
                name: 'userDashboard',
                path: 'dashboard',  // Relative path
                component: Dashboard,
            },
            {
                name: 'purchaseHistory',
                path: 'purchaseHistory',  // Relative path
                component: PurchaseHistory,
                meta: { role: '3' }
            },
            {
                name: 'checkOut',
                path: 'checkOut/:id',  // Relative path
                component: CheckOut,
                props:true,
                meta: { role: '3' }
            },
            {
                name: 'preOrder',
                path: 'preOrder',  // Relative path
                component: PreOrder,
                meta: { role: '3' }
            },
            {
                name: 'cart',
                path: 'cart',  // Relative path
                component: Cart,
                meta: { role: '3' }
            },
            // Cashier
            {
                name: 'cashierPreOrder',
                path: 'cashierPreOrder',  // Relative path
                component: CashierPreOrder,
                meta: { role: '2' }
            },
        ]
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
    scrollBehavior(to, from, savedPosition) {
        return { top: 0, behavior: 'smooth' }
    }
});

router.beforeEach((to, from, next) => {
    const isAuthenticated = !!localStorage.getItem('authToken'); // Replace this with your actual authentication check
    const userRole = localStorage.getItem('userRole'); // Retrieve the user role from local storage or Vuex store

    if (to.path === '/login' && isAuthenticated) {
        next(from); // Redirect back to the previous route
    } else if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!isAuthenticated) {
            next({
                path: '/login',
                query: { redirect: to.fullPath },
            });
        } else {
            if (to.matched.some(record => record.meta.role)) {
                const requiredRole = to.matched.find(record => record.meta.role).meta.role;
                if (userRole === requiredRole) {
                    next();
                } else {
                    next({ path: '/' }); // Redirect to home or an unauthorized page
                }
            } else {
                next();
            }
        }
    } else {
        next();
    }
});

export default router;
