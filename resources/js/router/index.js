import AppLayout from '@/layout/AppLayout.vue';
import { createRouter, createWebHistory } from 'vue-router';

const router = createRouter({
    history: createWebHistory(),
    routes: [
         {
            path: '/',
            name: 'landing',
            component: () => import('@/views/pages/Landing.vue')
        },
        {
            path: '/admin',
            component: AppLayout,
            children: [
                {
                    path: '/admin/dashboard',
                    name: 'dashboard',
                    component: () => import('@/views/Dashboard.vue')
                },

                {
                    path: "/admin/questions",
                    name: "admin.questions",
                    meta: {
                        requiresAuth: true,
                    },
                    component: () =>
                        import("@/views/pages/admin/questions/IndexQuestions.vue"),
                },
                {
                    path: "/admin/questions/create",
                    name: "admin.questions.create",
                    meta: {
                        requiresAuth: true,
                    },
                    component: () =>
                        import("@/views/pages/admin/questions/CreateQuestions.vue"),
                },
                {
                    path: "/admin/questions/:id",
                    name: "admin.questions.show",
                    meta: {
                        requiresAuth: true,
                    },
                    component: () =>
                        import("@/views/pages/admin/questions/ShowQuestions.vue"),
                },
                {
                    path: "/admin/questions/:id/edit",
                    name: "admin.questions.edit",
                    meta: {
                        requiresAuth: true,
                    },
                    component: () =>
                        import("@/views/pages/admin/questions/EditQuestions.vue"),
                },


                
            ]
        },
       
        {
            path: '/pages/notfound',
            name: 'notfound',
            component: () => import('@/views/pages/NotFound.vue')
        },

        {
            path: '/auth/login',
            name: 'login',
            component: () => import('@/views/pages/auth/Login.vue')
        },
        {
            path: '/auth/access',
            name: 'accessDenied',
            component: () => import('@/views/pages/auth/Access.vue')
        },
        {
            path: '/auth/error',
            name: 'error',
            component: () => import('@/views/pages/auth/Error.vue')
        }
    ]
});

export default router;
