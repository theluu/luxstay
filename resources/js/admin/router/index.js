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
import MessagesView     from '../views/Messages/MessagesView.vue'
import CommentsView     from '../views/Comments/CommentsView.vue'
import SettingsView     from '../views/Settings/SettingsView.vue'
import MenuView         from '../views/Menu/MenuView.vue'
import SubscribersView  from '../views/Subscribers/SubscribersView.vue'
import SlidersView      from '../views/Sliders/SlidersView.vue'
import SliderFormView   from '../views/Sliders/SliderFormView.vue'
import AboutPageView    from '../views/About/AboutPageView.vue'
import FooterView       from '../views/Footer/FooterView.vue'
import PaymentTransactionsView from '../views/Payments/PaymentTransactionsView.vue'
import TranslationsView from '../views/Translations/TranslationsView.vue'
import UsersView from '../views/Users/UsersView.vue'

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
    { path: '/admin/messages',               component: MessagesView     },
    { path: '/admin/comments',               component: CommentsView     },
    { path: '/admin/settings',               component: SettingsView     },
    { path: '/admin/menu',                   component: MenuView         },
    { path: '/admin/subscribers',            component: SubscribersView  },
    { path: '/admin/sliders',                component: SlidersView      },
    { path: '/admin/sliders/create',         component: SliderFormView   },
    { path: '/admin/sliders/:id/edit',       component: SliderFormView   },
    { path: '/admin/about-page',             component: AboutPageView    },
    { path: '/admin/footer',                 component: FooterView       },
    { path: '/admin/payment-transactions',    component: PaymentTransactionsView },
    { path: '/admin/translations', component: TranslationsView },
    { path: '/admin/users', component: UsersView },
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
