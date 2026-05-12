import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

import LoginView      from '../views/LoginView.vue'
import DashboardView  from '../views/DashboardView.vue'
import RoomsView      from '../views/Rooms/RoomsView.vue'
import RoomFormView   from '../views/Rooms/RoomFormView.vue'
import BookingsView   from '../views/Bookings/BookingsView.vue'
import PostsView      from '../views/Posts/PostsView.vue'
import PostFormView   from '../views/Posts/PostFormView.vue'
import ProductsView   from '../views/Products/ProductsView.vue'
import ProductFormView from '../views/Products/ProductFormView.vue'
import OrdersView     from '../views/Orders/OrdersView.vue'
import ActivitiesView from '../views/Activities/ActivitiesView.vue'
import ActivityFormView from '../views/Activities/ActivityFormView.vue'

const routes = [
    { path: '/admin/login',                  component: LoginView,       meta: { public: true } },
    { path: '/admin',                        component: DashboardView   },
    { path: '/admin/rooms',                  component: RoomsView       },
    { path: '/admin/rooms/create',           component: RoomFormView    },
    { path: '/admin/rooms/:id/edit',         component: RoomFormView    },
    { path: '/admin/bookings',               component: BookingsView    },
    { path: '/admin/posts',                  component: PostsView       },
    { path: '/admin/posts/create',           component: PostFormView    },
    { path: '/admin/posts/:id/edit',         component: PostFormView    },
    { path: '/admin/products',               component: ProductsView    },
    { path: '/admin/products/create',        component: ProductFormView },
    { path: '/admin/products/:id/edit',      component: ProductFormView },
    { path: '/admin/orders',                 component: OrdersView      },
    { path: '/admin/activities',             component: ActivitiesView  },
    { path: '/admin/activities/:id/edit',    component: ActivityFormView },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

router.beforeEach((to) => {
    const auth = useAuthStore()
    if (!to.meta.public && !auth.isAuthenticated) {
        return { path: '/admin/login' }
    }
})

export default router
