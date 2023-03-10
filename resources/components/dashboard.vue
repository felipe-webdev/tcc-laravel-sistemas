<template>
  <div class="container-lg vstack flex-wrap text-light gap-4 py-4">
    <h3 class="text-center fs-3 m-0"> Cadastros </h3>


    <div class="card text-bg-dark border border-light border-opacity-25 shadow-plus">
      <div class="hstack flex-wrap flex-fill justify-content-center gap-0">
        <div 
          class="vstack flex-grow-0 justify-content-center align-items-center gap-4 p-4"
          style="width:200px"
        >
          <i class="fa-solid fa-user-tie fs-1"></i>
          <h2 class="fs-4">Funcionários</h2>
        </div>

        <div class="hstack flex-grow-1 flex-wrap justify-content-evenly align-items-stretch gap-4 p-4">
          <div class="flex-fill rounded bd-callout bd-callout-info" style="width:200px">
            <h5 class="card-title">Cadastrados</h5>
            <div v-if="counts.employee.loading" class="spinner-border" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="card-text fs-1">{{counts.employee.total}}</p>
            <p class="card-text">Total de Funcionários cadastrados no sistema.</p>
          </div>

          <div class="flex-fill rounded bd-callout bd-callout-success" style="width:200px">
            <h5 class="card-title">Ativos</h5>
            <div v-if="counts.employee.loading" class="spinner-border" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="card-text fs-1">{{counts.employee.active}}</p>
            <p class="card-text">Total de Funcionários com cadastro ativo.</p>
          </div>

          <div class="flex-fill rounded bd-callout bd-callout-warning" style="width:200px">
            <h5 class="card-title">Inativos</h5>
            <div v-if="counts.employee.loading" class="spinner-border" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="card-text fs-1">{{counts.employee.inactive}}</p>
            <p class="card-text">Total de Funcionários com cadastro inativo.</p>
          </div>

          <div class="flex-fill rounded bd-callout" style="width:200px">
            <h5 class="card-title">Cadastrar</h5>
            <p class="card-text">Cadastrar um novo Funcionário.</p>
            <button
              class="btn btn-info"
              @click="$emit('newRegistration', 'employee')"
            >
              <i class="fa-solid fa-user-plus me-1"></i>
              Funcionário
            </button>
          </div>
        </div>
      </div>
    </div>


    <div class="card text-bg-dark border border-light border-opacity-25 shadow-plus">
      <div class="hstack flex-wrap flex-fill justify-content-center gap-0">
        <div 
          class="vstack flex-grow-0 justify-content-center align-items-center gap-4 p-4"
          style="width:200px"
        >
          <i class="fa-solid fa-people-roof fs-1"></i>
          <h2 class="fs-4">Dependentes</h2>
        </div>

        <div class="hstack flex-grow-1 flex-wrap justify-content-evenly align-items-stretch gap-4 p-4">
          <div class="flex-fill rounded bd-callout bd-callout-info" style="width:200px">
            <h5 class="card-title">Cadastrados</h5>
            <div v-if="counts.family.loading" class="spinner-border" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="card-text fs-1">{{counts.family.total}}</p>
            <p class="card-text">Total de Dependentes cadastrados no sistema.</p>
          </div>

          <div class="flex-fill rounded bd-callout bd-callout-success" style="width:200px">
            <h5 class="card-title">Ativos</h5>
            <div v-if="counts.family.loading" class="spinner-border" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="card-text fs-1">{{counts.family.active}}</p>
            <p class="card-text">Total de Dependentes (de Funcionários com cadastro ativo).</p>
          </div>

          <div class="flex-fill rounded bd-callout bd-callout-warning" style="width:200px">
            <h5 class="card-title">Inativos</h5>
            <div v-if="counts.family.loading" class="spinner-border" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="card-text fs-1">{{counts.family.inactive}}</p>
            <p class="card-text">Total de Dependentes (de Funcionários com cadastro inativo).</p>
          </div>

          <div class="flex-fill rounded bd-callout" style="width:200px">
            <h5 class="card-title">Cadastrar</h5>
            <p class="card-text">Cadastrar um novo Dependente.</p>
            <button
              class="btn btn-info"
              @click="$emit('newRegistration', 'family')"
            >
              <i class="fa-solid fa-user-plus me-1"></i>
              Dependente
            </button>
          </div>
        </div>
      </div>
    </div>


    <div class="card text-bg-dark border border-light border-opacity-25 shadow-plus">
      <div class="hstack flex-wrap flex-fill justify-content-center gap-0">
        <div 
          class="vstack flex-grow-0 justify-content-center align-items-center gap-4 p-4"
          style="width:200px"
        >
          <i class="fa-solid fa-users fs-1"></i>
          <h2 class="fs-4">Usuários</h2>
        </div>

        <div class="hstack flex-grow-1 flex-wrap justify-content-evenly align-items-stretch gap-4 p-4">
          <div class="flex-fill rounded bd-callout bd-callout-info" style="width:200px">
            <h5 class="card-title">Cadastrados</h5>
            <div v-if="counts.user.loading" class="spinner-border" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="card-text fs-1">{{counts.user.total}}</p>
            <p class="card-text">Total de Usuários cadastrados no sistema.</p>
          </div>

          <div class="flex-fill rounded bd-callout bd-callout-success" style="width:200px">
            <h5 class="card-title">Ativos</h5>
            <div v-if="counts.user.loading" class="spinner-border" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="card-text fs-1">{{counts.user.active}}</p>
            <p class="card-text">Total de Usuários (de Funcionários com cadastro ativo).</p>
          </div>

          <div class="flex-fill rounded bd-callout bd-callout-warning" style="width:200px">
            <h5 class="card-title">Inativos</h5>
            <div v-if="counts.user.loading" class="spinner-border" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="card-text fs-1">{{counts.user.inactive}}</p>
            <p class="card-text">Total de Usuários (de Funcionários com cadastro inativo).</p>
          </div>

          <div class="flex-fill rounded bd-callout" style="width:200px">
            <h5 class="card-title">Cadastrar</h5>
            <p class="card-text">Cadastrar um novo Usuário.</p>
            <button
              class="btn btn-info"
              @click="$emit('newRegistration', 'user')"
            >
              <i class="fa-solid fa-user-plus me-1"></i>
              Usuário
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
  export default {
    components: {},

    directives: {},

    props: [
      'session_user',
      'system_types'
    ],

    data(){
      return {
        counts: {
          employee: { loading: true },
          family:   { loading: true },
          user:     { loading: true },
        },
      }
    },

    computed: {},

    watch: {},

    created(){
      this.$emit('isLoading', false);
      this.getCounts();
      setInterval(this.getCounts, 300000);
    },

    mounted(){},

    updated(){},

    activated(){},

    deactivated(){},

    methods: {
      getCounts(){
        this.counts.employee = {loading: true};
        this.counts.family   = {loading: true};
        this.counts.user     = {loading: true};
        api.countRecords()
          .then((response)=>{
            this.counts.employee = response.data.employee;
            this.counts.family   = response.data.family;
            this.counts.user     = response.data.user;
          })
          .catch((error)=>{
            console.log(error);
            this.$emit('showAlert', 'error', `Ocorreu um erro no processo: ${error}`);
          })
          .finally(()=>{
          });
      },
    },
  }
  </script>