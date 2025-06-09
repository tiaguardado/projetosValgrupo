<template>
  <div>
    <h2>Registar</h2>
    <form @submit.prevent="registerUser">
      <label>
        Nome:
        <input v-model="form.name" required />
      </label>
      <br />

      <label>
        Número Telemóvel:
        <input v-model="form.numTelemovel" required />
      </label>
      <br />

      <label>
        Email:
        <input type="email" v-model="form.email" required />
      </label>
      <br />

      <label>
        Morada:
        <input v-model="form.morada" />
      </label>
      <br />

      <label>
        Código Postal:
        <input v-model="form.codPostal" />
      </label>
      <br />

      <label>
        Localidade:
        <input v-model="form.localidade" />
      </label>
      <br />

      <label>
        Observações:
        <textarea v-model="form.observacoes"></textarea>
      </label>
      <br />

      <label>
        Password:
        <input type="password" v-model="form.password" required />
      </label>
      <br />

      <label>
        Confirmar Password:
        <input type="password" v-model="form.password_confirmation" required />
      </label>
      <br />

      <button type="submit">Registar</button>
      <button type="button" @click="goToLogin" style="margin-left: 10px;">Voltar para Login</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'RegisterUser',
  data() {
    return {
      form: {
        name: '',
        numTelemovel: '',
        email: '',
        morada: '',
        codPostal: '',
        localidade: '',
        observacoes: '',
        password: '',
        password_confirmation: '',
      },
    };
  },
  methods: {
    registerUser() {
      console.log('Dados enviados:', this.form);

      axios.post('http://localhost:8000/api/register', this.form)
        .then(() => {
          alert('Registo efetuado com sucesso!');
          this.$router.push('/login');
        })
        .catch(error => {
          if (
            error.response &&
            error.response.data &&
            error.response.data.errors
          ) {
            console.error('Erros de validação:', error.response.data.errors);
            const errors = error.response.data.errors;
            let message = 'Erro ao registar:\n';
            for (const field in errors) {
              message += `${field}: ${errors[field].join(', ')}\n`;
            }
            alert(message);
          } else {
            console.error('Erro no registo:', error);
            alert('Erro ao registar. Verifique os dados.');
          }
        });
    },
    goToLogin() {
      this.$router.push('/login');
    },
  },
};
</script>
