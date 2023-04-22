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
            <div v-if="employee.loading" class="spinner-border" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="card-text fs-1">{{employee.total}}</p>
            <p class="card-text">Total de Funcionários cadastrados no sistema.</p>
          </div>

          <div class="flex-fill rounded bd-callout bd-callout-success" style="width:200px">
            <h5 class="card-title">Ativos</h5>
            <div v-if="employee.loading" class="spinner-border" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="card-text fs-1">{{employee.active}}</p>
            <p class="card-text">Total de Funcionários com cadastro ativo.</p>
          </div>

          <div class="flex-fill rounded bd-callout bd-callout-warning" style="width:200px">
            <h5 class="card-title">Inativos</h5>
            <div v-if="employee.loading" class="spinner-border" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="card-text fs-1">{{employee.inactive}}</p>
            <p class="card-text">Total de Funcionários com cadastro inativo.</p>
          </div>

          <div id="employee-chart" class="flex-fill rounded bd-callout hstack justify-content-center" style="width:200px">
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
            <div v-if="family.loading" class="spinner-border" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="card-text fs-1">{{family.total}}</p>
            <p class="card-text">Total de Dependentes cadastrados no sistema.</p>
          </div>

          <div class="flex-fill rounded bd-callout bd-callout-success" style="width:200px">
            <h5 class="card-title">Ativos</h5>
            <div v-if="family.loading" class="spinner-border" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="card-text fs-1">{{family.active}}</p>
            <p class="card-text">Total de Dependentes (de Funcionários com cadastro ativo).</p>
          </div>

          <div class="flex-fill rounded bd-callout bd-callout-warning" style="width:200px">
            <h5 class="card-title">Inativos</h5>
            <div v-if="family.loading" class="spinner-border" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="card-text fs-1">{{family.inactive}}</p>
            <p class="card-text">Total de Dependentes (de Funcionários com cadastro inativo).</p>
          </div>

          <div id="family-chart" class="flex-fill rounded bd-callout hstack justify-content-center" style="width:200px">
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
            <div v-if="user.loading" class="spinner-border" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="card-text fs-1">{{user.total}}</p>
            <p class="card-text">Total de Usuários cadastrados no sistema.</p>
          </div>

          <div class="flex-fill rounded bd-callout bd-callout-success" style="width:200px">
            <h5 class="card-title">Ativos</h5>
            <div v-if="user.loading" class="spinner-border" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="card-text fs-1">{{user.active}}</p>
            <p class="card-text">Total de Usuários (de Funcionários com cadastro ativo).</p>
          </div>

          <div class="flex-fill rounded bd-callout bd-callout-warning" style="width:200px">
            <h5 class="card-title">Inativos</h5>
            <div v-if="user.loading" class="spinner-border" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
            <p class="card-text fs-1">{{user.inactive}}</p>
            <p class="card-text">Total de Usuários (de Funcionários com cadastro inativo).</p>
          </div>

          <div id="user-chart" class="flex-fill rounded bd-callout hstack justify-content-center" style="width:200px">
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
        refresh: undefined,
        employee: {
          loading:  true,
          total:    null,
          active:   null,
          incative: null,
        },
        family: {
          loading:  true,
          total:    null,
          active:   null,
          incative: null,
        },
        user: {
          loading:  true,
          total:    null,
          active:   null,
          incative: null,
        },
      }
    },

    computed: {},

    watch: {
      'employee.total'(){
        Plotly.newPlot(
          'employee-chart',
          [
            {
              name: 'Funcionários',
              values: this.employee.loading? [0, 0]: [this.employee.active, this.employee.inactive],
              labels: ['Ativos', 'Inativos'],
              hoverinfo: 'label+value',
              textinfo: 'percent',
              textposition: 'auto',
              type: 'pie',
              hole: .4,
              showlegend: false,
              automargin: true,
              marker: {
                colors: ['rgb(29, 181, 116)', 'rgb(255, 193, 7)'],
              },
            },
          ],
          {
            title: '',
            paper_bgcolor: 'rgba(0, 0, 0, 0)',
            width:160,
            height:160,
            autosize: true,
            margin: {
              t:0, b:0, l:0, r:0,
              autoexpand: true,
            },
            showlegend: false,
          },
          {
            displayModeBar: false,
            responsive: true,
          }
        );
      },

      'family.total'(){
        Plotly.newPlot(
          'family-chart',
          [
            {
              name: 'Dependentes',
              values: this.family.loading? [0, 0]: [this.family.active, this.family.inactive],
              labels: ['Ativos', 'Inativos'],
              hoverinfo: 'label+value',
              textinfo: 'percent',
              textposition: 'auto',
              type: 'pie',
              hole: .4,
              showlegend: false,
              automargin: true,
              marker: {
                colors: ['rgb(29, 181, 116)', 'rgb(255, 193, 7)'],
              },
            },
          ],
          {
            title: '',
            paper_bgcolor: 'rgba(0, 0, 0, 0)',
            width:160,
            height:160,
            autosize: true,
            margin: {
              t:0, b:0, l:0, r:0,
              autoexpand: true,
            },
            showlegend: false,
          },
          {
            displayModeBar: false,
            responsive: true,
          }
        );
      },

      'user.total'(){
        Plotly.newPlot(
          'user-chart',
          [
            {
              name: 'Usuários',
              values: this.user.loading? [0, 0]: [this.user.active, this.user.inactive],
              labels: ['Ativos', 'Inativos'],
              hoverinfo: 'label+value',
              textinfo: 'percent',
              textposition: 'auto',
              type: 'pie',
              hole: .4,
              showlegend: false,
              automargin: true,
              marker: {
                colors: ['rgb(29, 181, 116)', 'rgb(255, 193, 7)'],
              },
            },
          ],
          {
            title: '',
            paper_bgcolor: 'rgba(0, 0, 0, 0)',
            width:160,
            height:160,
            autosize: true,
            margin: {
              t:0, b:0, l:0, r:0,
              autoexpand: true,
            },
            showlegend: false,
          },
          {
            displayModeBar: false,
            responsive: true,
          }
        );
      },
    },

    created(){
      this.$emit('isLoading', false);
      this.getCounts();
    },

    mounted(){},

    updated(){},

    activated(){ this.refresh = setInterval(this.getCounts, 10000 /* 300000 */); },

    deactivated(){ clearInterval(this.refresh); this.refresh = undefined; },

    methods: {
      getCounts(){
        this.employee.loading = true;
        this.family.loading   = true;
        this.user.loading     = true;
        api.countRecords()
          .then((response)=>{
            this.employee = response.data.employee;
            this.family   = response.data.family;
            this.user     = response.data.user;
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