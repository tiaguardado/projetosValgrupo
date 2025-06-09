<template>
    <div>
      <!-- Botões de navegação -->
      <div style="margin-bottom: 20px;">
        <button @click="goToUsers">Ir para Users</button>
        <button @click="logout">Logout</button>
      </div>
  
      <h1>Gestão de Encomendas</h1>
  
      <!-- Lista de encomendas -->
      <ul>
        <li v-for="encomenda in encomendas" :key="encomenda.id">
          {{ encomenda.tamanhoPizza }} - {{ encomenda.basePizza }} - {{ encomenda.estadoPedido }}
          <br />
          Ingredientes Extra: {{ encomenda.ingredientesExtra || 'Nenhum' }}
          <br />
          <button @click="editEncomenda(encomenda)">Editar Estado</button>
          <button @click="deleteEncomenda(encomenda.id)">Apagar</button>
        </li>
      </ul>
  
      <!-- Botão para mostrar/esconder o formulário de nova encomenda -->
      <button @click="showCreateForm = !showCreateForm" style="margin-top: 20px;">
        {{ showCreateForm ? 'Cancelar Nova Encomenda' : 'Adicionar Encomenda' }}
      </button>
  
      <!-- Formulário para criar nova encomenda -->
      <div v-if="showCreateForm" style="margin-top: 20px;">
        <h2>Nova Encomenda</h2>
        <form @submit.prevent="createEncomenda">
          <label>
            Tamanho da Pizza:
            <input v-model="newEncomenda.tamanhoPizza" required />
          </label>
          <br />
  
          <label>
            Base da Pizza:
            <input v-model="newEncomenda.basePizza" required />
          </label>
          <br />
  
          <label>
            Ingredientes Extra:
            <input v-model="newEncomenda.ingredientesExtra" />
          </label>
          <br />
  
          <button type="submit">Criar Encomenda</button>
        </form>
      </div>
  
      <!-- Formulário para editar SÓ o estado do pedido -->
      <div v-if="editingEncomenda" style="margin-top: 20px;">
        <h2>Editar Estado da Encomenda</h2>
        <form @submit.prevent="updateEstadoPedido">
          <label>
            Estado do Pedido:
            <select v-model="editingEncomenda.estadoPedido" required>
              <option>Pendente</option>
              <option>Em Preparação</option>
              <option>Pronto</option>
              <option>Entregue</option>
            </select>
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
    name: 'EncomendasUser',
    data() {
      return {
        encomendas: [],
        newEncomenda: {
          tamanhoPizza: '',
          basePizza: '',
          ingredientesExtra: '',
          estadoPedido: 'Pendente',
        },
        editingEncomenda: null,
        showCreateForm: false,
        authStore: useAuthStore(),
      };
    },
    mounted() {
      this.fetchEncomendas();
    },
    methods: {
      getAuthHeaders() {
        return {
          Authorization: `Bearer ${this.authStore.token}`,
        };
      },
      fetchEncomendas() {
        axios
          .get('http://localhost:8000/api/encomendas', {
            headers: this.getAuthHeaders(),
          })
          .then(res => {
            this.encomendas = res.data.data || res.data;
          })
          .catch(err => {
            console.error('Erro ao carregar encomendas:', err);
          });
      },
      createEncomenda() {
        axios
          .post('http://localhost:8000/api/encomendas', this.newEncomenda, {
            headers: this.getAuthHeaders(),
          })
          .then(() => {
            this.fetchEncomendas();
            this.newEncomenda = {
              tamanhoPizza: '',
              basePizza: '',
              ingredientesExtra: '',
              estadoPedido: 'Pendente',
            };
            this.showCreateForm = false;
            alert('Encomenda criada com sucesso!');
          })
          .catch(err => {
            console.error('Erro ao criar encomenda:', err);
            alert('Erro ao criar encomenda!');
          });
      },
      editEncomenda(encomenda) {
        this.editingEncomenda = {
          id: encomenda.id,
          estadoPedido: encomenda.estadoPedido,
        };
      },
      cancelEdit() {
        this.editingEncomenda = null;
      },
      updateEstadoPedido() {
        axios
          .put(
            `http://localhost:8000/api/encomendas/${this.editingEncomenda.id}`,
            { estadoPedido: this.editingEncomenda.estadoPedido },
            {
              headers: this.getAuthHeaders(),
            }
          )
          .then(() => {
            this.fetchEncomendas();
            this.editingEncomenda = null;
            alert('Estado da encomenda atualizado com sucesso!');
          })
          .catch(err => {
            console.error('Erro ao atualizar estado da encomenda:', err);
            alert('Erro ao atualizar estado da encomenda!');
          });
      },
      deleteEncomenda(id) {
        if (!confirm('Tem certeza que deseja apagar esta encomenda?')) return;
  
        axios
          .delete(`http://localhost:8000/api/encomendas/${id}`, {
            headers: this.getAuthHeaders(),
          })
          .then(() => {
            this.fetchEncomendas();
            alert('Encomenda apagada com sucesso!');
          })
          .catch(err => {
            console.error('Erro ao apagar encomenda:', err);
            alert('Erro ao apagar encomenda!');
          });
      },
      goToUsers() {
        this.$router.push('/users');
      },
      logout() {
        this.authStore.setToken(null);
        localStorage.removeItem('authToken');
        this.$router.push('/login');
      },
    },
  };
  </script>
  