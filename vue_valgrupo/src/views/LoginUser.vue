<template>
    <div>
      <h2>Login</h2>
      <form @submit.prevent="loginUser">
        <label>
          Email:
          <input type="email" v-model="form.email" required />
        </label>
        <br />
  
        <label>
          Password:
          <input type="password" v-model="form.password" required />
        </label>
        <br />
  
        <button type="submit">Login</button>
        <button type="button" @click="goToRegister" style="margin-left: 10px;">Registar</button>
      </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import { useAuthStore } from '../stores/auth';
  
  export default {
    name: 'LoginUser',
    data() {
      return {
        form: {
          email: '',
          password: '',
        },
        authStore: useAuthStore(),
      };
    },
    methods: {
      loginUser() {
        axios
          .post('http://localhost:8000/api/login', this.form)
          .then(response => {
            const token = response.data.token;
  
            if (!token) {
              throw new Error('Token não recebido. Login falhou.');
            }
  
            this.authStore.setToken(token);
            alert('Login efetuado com sucesso!');
            this.$router.push('/encomendas');
          })
          .catch(error => {
            console.error('Erro no login:', error);
            alert('Email ou password inválidos.');
          });
      },
      goToRegister() {
        this.$router.push('/register');
      },
    },
  };
  </script>
  