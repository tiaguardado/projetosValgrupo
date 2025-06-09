import { defineStore } from 'pinia';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('authToken') || '',
  }),
  actions: {
    setToken(token) {
      this.token = token;
      localStorage.setItem('authToken', token);
    },
    clearToken() {
      this.token = '';
      localStorage.removeItem('authToken');
    },
  },
});
