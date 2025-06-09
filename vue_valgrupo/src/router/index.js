import { createRouter, createWebHistory } from 'vue-router';
import UsersList from '../views/UsersList.vue';
import RegisterUser from '../views/RegisterUser.vue';
import LoginUser from '../views/LoginUser.vue';
import EncomendasUser from '../views/EncomendasUser.vue';

const routes = [

  {
    path: '/',
    redirect: '/login', // Redireciona para login como default
  },
  
  {
    path: '/users',
    name: 'UsersList',
    component: UsersList,
  },

  {
    path: '/register',
    name: 'RegisterUser',
    component: RegisterUser,
  },

  {
    path: '/login',
    name: 'LoginUser',
    component: LoginUser,
  },

  {
    path: '/encomendas',
    name: 'EncomendasUser',
    component: EncomendasUser,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
