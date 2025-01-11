import { createRouter, createWebHistory } from "vue-router";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/login",
      name: "login",
      component: () => import("@/views/LoginView.vue"),
      meta: { requireGuest: true },
    },
    {
      path: "/register",
      name: "register",
      component: () => import("@/views/RegisterView.vue"),
      meta: { requireGuest: true },
    },
    {
      path: "/",
      name: "home",
      redirect: "/todo",
      meta: { requireAuth: true },
      children: [
        {
          path: "/todo",
          name: "todo",
          component: () => import("@/views/TodoView.vue"),
        },
      ],
    },
    {
      path: "/:catchAll(.*)*",
      component: () => import("@/views/NotFoundView.vue"),
    },
  ],
});

export default router;
