<template>
  <div>
    <h1>Lista de Utilizadores</h1>

    <div style="margin-bottom: 20px;">
      <button @click="goToLogin">Login</button>
      <button @click="goToRegister">Registar</button>
      <button @click="goToEncomendas">Encomendas</button>
      <button @click="logout">Logout</button>
    </div>

    <ul>
      <li v-for="user in users" :key="user.id">
        {{ user.name }} - {{ user.email }}
        <button @click="editUser(user)">Editar</button>
        <button @click="deleteUser(user.id)">Apagar</button>
      </li>
    </ul>

    <div v-if="editingUser" style="margin-top: 20px;">
      <h2>Editar Utilizador</h2>
      <form @submit.prevent="updateUser">
        <label>
          Nome:
          <input v-model="editingUser.name" required />
        </label>
        <br />

        <label>
          Número Telemóvel:
          <input v-model="editingUser.numTelemovel" required />
        </label>
        <br />

        <label>
          Email:
          <input v-model="editingUser.email" type="email" required />
        </label>
        <br />

        <label>
          Morada:
          <input v-model="editingUser.morada" />
        </label>
        <br />

        <label>
          Código Postal:
          <input v-model="editingUser.codPostal" />
        </label>
        <br />

        <label>
          Localidade:
          <input v-model="editingUser.localidade" />
        </label>
        <br />

        <label>
          Observações:
          <textarea v-model="editingUser.observacoes"></textarea>
        </label>
        <br />

        <button type="submit">Guardar</button>
        <button type="button" @click="cancelEdit">Cancelar</button>
      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { useAuthStore } from '../stores/auth';

export default {
  name: 'UsersList',
  data() {
    return {
      users: [],
      editingUser: null,
      authStore: useAuthStore(),
    };
  },
  mounted() {
    this.fetchUsers();
  },
  methods: {
    getAuthHeaders() {
      return {
        Authorization: `Bearer ${this.authStore.token}`,
      };
    },
    fetchUsers() {
      axios
        .get('http://localhost:8000/api/users', {
          headers: this.getAuthHeaders(),
        })
        .then(res => {
          this.users = res.data.data || res.data;
        })
        .catch(err => {
          console.error('Erro ao carregar utilizadores:', err);
        });
    },
    editUser(user) {
      this.editingUser = { ...user }; // clona para edição
    },
    cancelEdit() {
      this.editingUser = null;
    },
    updateUser() {
      axios
        .put(
          `http://localhost:8000/api/users/${this.editingUser.id}`,
          this.editingUser,
          {
            headers: this.getAuthHeaders(),
          }
        )
        .then(() => {
          this.fetchUsers();
          this.editingUser = null;
          alert('Utilizador atualizado!');
        })
        .catch(err => {
          console.error('Erro ao atualizar utilizador:', err);
          alert('Erro ao atualizar utilizador!');
        });
    },
    deleteUser(userId) {
      if (!confirm('Tem certeza que deseja apagar este utilizador?')) return;

      console.log('Token para delete:', this.authStore.token); // Debug token

      axios
        .delete(`http://localhost:8000/api/users/${userId}`, {
          headers: this.getAuthHeaders(),
        })
        .then(() => {
          this.fetchUsers();
          alert('Utilizador apagado!');
        })
        .catch(err => {
          console.error('Erro ao apagar utilizador:', err);
          alert('Erro ao apagar utilizador!');
        });
    },
    goToLogin() {
      this.$router.push('/login');
    },
    goToRegister() {
      this.$router.push('/register');
    },
    goToEncomendas() {
      this.$router.push('/encomendas');
    },
    logout() {
      this.authStore.setToken(null);
      localStorage.removeItem('authToken');
      this.$router.push('/login');
    },
  },
};
</script>
