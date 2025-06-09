<template>
    <div>
      <!-- Botões de navegação -->
      <div style="margin-bottom: 20px; text-align: center;">
        <button @click="goToUsers">Ir para Users</button>
        <button @click="logout">Logout</button>
      </div>
  
      <h1 style="text-align: center;">Gestão de Encomendas</h1>
  
      <!-- Barra de pesquisa por ID -->
      <div style="text-align: center; margin-bottom: 20px;">
        <input
          v-model="searchId"
          placeholder="Pesquisar por ID"
          style="padding: 5px; width: 200px;"
          type="number"
        />
        <button @click="searchEncomendaById">Pesquisar</button>
        <button @click="clearSearch">Limpar</button>
      </div>
  
      <!-- Lista de encomendas em tabela -->
      <table
        border="1"
        cellpadding="8"
        cellspacing="0"
        style="
          margin: 0 auto 20px auto;
          border-collapse: collapse;
          text-align: center;
          min-width: 600px;
        "
      >
        <thead>
          <tr>
            <th>ID</th>
            <th>Tamanho</th>
            <th>Base</th>
            <th>Estado</th>
            <th>Ingredientes Extra</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="encomenda in filteredEncomendas" :key="encomenda.id">
            <td>{{ encomenda.id }}</td>
            <td>{{ encomenda.tamanhoPizza }}</td>
            <td>{{ encomenda.basePizza }}</td>
            <td>{{ encomenda.estadoPedido }}</td>
            <td>{{ encomenda.ingredientesExtra || 'Nenhum' }}</td>
            <td>
              <button @click="editEncomenda(encomenda)">Editar Estado</button>
              <button @click="deleteEncomenda(encomenda.id)">Apagar</button>
            </td>
          </tr>
        </tbody>
      </table>
  
      <!-- Botão para mostrar/esconder o formulário de nova encomenda -->
      <div style="text-align: center;">
        <button @click="showCreateForm = !showCreateForm" style="margin-top: 20px;">
          {{ showCreateForm ? 'Cancelar Nova Encomenda' : 'Adicionar Encomenda' }}
        </button>
      </div>
  
      <!-- Formulário para criar nova encomenda -->
      <div v-if="showCreateForm" style="margin-top: 20px; text-align: center;">
        <h2>Nova Encomenda</h2>
        <form @submit.prevent="createEncomenda">
          <label>
            Tamanho da Pizza:
            <select v-model="newEncomenda.tamanhoPizza" required>
              <option disabled value="">Selecione o tamanho</option>
              <option>Individual</option>
              <option>Média</option>
              <option>Grande</option>
              <option>Familiar</option>
            </select>
          </label>
          <br />
  
          <label>
            Base da Pizza:
            <select v-model="newEncomenda.basePizza" required>
              <option disabled value="">Selecione a base</option>
              <option>Pizza Havaiana</option>
              <option>Pizza de Fiambre</option>
              <option>Pizza Chourição</option>
            </select>
          </label>
          <br />
  
          <label>
            Ingredientes Extra:
            <div style="display: inline-block; text-align: left; margin: 10px 0;">
              <label>
                <input type="checkbox" value="Fiambre" v-model="ingredientesSelecionados" />
                Fiambre
              </label>
              <br />
              <label>
                <input type="checkbox" value="Chourição" v-model="ingredientesSelecionados" />
                Chourição
              </label>
              <br />
              <label>
                <input type="checkbox" value="queijo" v-model="ingredientesSelecionados" />
                queijo
              </label>
              <br />
              <label>
                <input type="checkbox" value="cogumelos" v-model="ingredientesSelecionados" />
                cogumelos
              </label>
              <br />
              <label>
                <input type="checkbox" value="picante" v-model="ingredientesSelecionados" />
                picante
              </label>
            </div>
          </label>
          <br />
  
          <label>
            Estado do Pedido:
            <select v-model="newEncomenda.estadoPedido" required>
              <option>Pendente</option>
              <option>Concluido</option>
              <option>Cancelado</option>
            </select>
          </label>
          <br />
  
          <button type="submit">Criar Encomenda</button>
        </form>
      </div>
  
      <!-- Formulário para editar SÓ o estado do pedido -->
      <div v-if="editingEncomenda" style="margin-top: 20px; text-align: center;">
        <h2>Editar Estado da Encomenda</h2>
        <form @submit.prevent="updateEstadoPedido">
          <label>
            Estado do Pedido:
            <select v-model="editingEncomenda.estadoPedido" required>
              <option>Pendente</option>
              <option>Concluido</option>
              <option>Cancelado</option>
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
        ingredientesSelecionados: [], // array dos checkboxes
        editingEncomenda: null,
        showCreateForm: false,
        authStore: useAuthStore(),
        searchId: '', // campo para pesquisa por id
      };
    },
    computed: {
      filteredEncomendas() {
        if (!this.searchId) {
          return this.encomendas;
        }
        return this.encomendas.filter(
          encomenda => encomenda.id === parseInt(this.searchId)
        );
      },
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
        // Atualizar o campo ingredientesExtra com os selecionados, separados por vírgula
        this.newEncomenda.ingredientesExtra = this.ingredientesSelecionados.join(', ');
  
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
            this.ingredientesSelecionados = [];
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
      searchEncomendaById() {
        // Já é reativo por causa da computed filteredEncomendas
      },
      clearSearch() {
        this.searchId = '';
      },
    },
  };
  </script>
  