<template>
  <div>
    <h1 style="text-align: center;">Lista de Utilizadores</h1>

    <!-- Mostrar nome do utilizador se logado -->
    <div v-if="isLoggedIn" style="text-align: center; margin-bottom: 10px;">
      <strong>Bem-vindo, {{ userName }}!</strong>
    </div>

    <!-- Botões de navegação -->
    <div style="margin-bottom: 20px; text-align: center;">
      <button v-if="!isLoggedIn" @click="goToLogin">Login</button>
      <button v-if="!isLoggedIn" @click="goToRegister">Registar</button>
      <button v-if="isLoggedIn" @click="goToEncomendas">Encomendas</button>
      <button v-if="isLoggedIn" @click="logout">Logout</button>
    </div>

    <!-- Tabela de utilizadores -->
    <table
      border="1"
      cellpadding="8"
      cellspacing="0"
      style="
        margin: 0 auto 20px auto;
        border-collapse: collapse;
        text-align: center;
        min-width: 800px;
      "
    >
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome</th>
          <th>Telemóvel</th>
          <th>Email</th>
          <th>Morada</th>
          <th>Código Postal</th>
          <th>Localidade</th>
          <th>Observações</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="user in users" :key="user.id">
          <td>{{ user.id }}</td>
          <td>{{ user.name }}</td>
          <td>{{ user.numTelemovel }}</td>
          <td>{{ user.email }}</td>
          <td>{{ user.morada }}</td>
          <td>{{ user.codPostal }}</td>
          <td>{{ user.localidade }}</td>
          <td>{{ user.observacoes || 'Nenhuma' }}</td>
          <td>
            <button @click="editUser(user)">Editar</button>
            <button @click="deleteUser(user.id)">Apagar</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Formulário para editar utilizador -->
    <div v-if="editingUser" style="margin-top: 20px; text-align: center;">
      <h2>Editar Utilizador</h2>
      <form @submit.prevent="updateUser" style="display: inline-block; text-align: left;">
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
  computed: {
    isLoggedIn() {
      return !!this.authStore.token;
    },
    userName() {
      return this.authStore.user?.name || 'Utilizador';
    },
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
      // Garante que todos os campos existem no editingUser para evitar problemas no v-model
      this.editingUser = {
        id: user.id,
        name: user.name || '',
        numTelemovel: user.numTelemovel || '',
        email: user.email || '',
        morada: user.morada || '',
        codPostal: user.codPostal || '',
        localidade: user.localidade || '',
        observacoes: user.observacoes || '',
      };
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
