<template>
  <div class="container-lg vstack flex-wrap gap-4 py-4">
    <h1 class="text-center text-light fs-3 m-0"> Listagem </h1>
    <div class="vstack gap-3">
      <div class="input-group shadow-plus">
        <div 
          class="form-floating flex-fill"
          style="width: 220px; min-width: 0;"
        >
          <input 
            v-model="search.name"
            type="text"
            placeholder="Nome"
            class="form-control bg-light-smooth"
            @keyup.enter="getList('enter')"
          >
          <label>Nome</label>
        </div>

        <div 
          class="form-floating flex-fill"
          style="width: 190px; min-width: 0;"
        >
          <select 
            v-model="search.job_type"
            class="form-select bg-light-smooth"
          >
            <option :value="null">Qualquer cargo</option>
            <option 
              v-for="option in system_types.job"
              :key="option.value"
              :value="option.value"
            >
              {{option.label}}
            </option>
          </select>
          <label>
            Cargo
          </label>
        </div>

        <div 
          class="form-floating flex-fill"
          style="width: 130px; min-width: 0;"
        >
          <select 
            v-model="search.active"
            class="form-select bg-light-smooth"
          >
            <option :value="null">Qualquer situação</option>
            <option :value="0">Inativo</option>
            <option :value="1">Ativo</option>
          </select>
          <label>
            Situação cadastral
          </label>
        </div>

        <div 
          class="form-floating flex-fill"
          style="width: 130px; min-width: 0;"
        >
          <select 
            v-model="pagination.page_limit"
            class="form-select bg-light-smooth"
          >
            <option :value="2">20</option>
            <option :value="4">40</option>
            <option :value="6">60</option>
          </select>
          <label>
            Resultados por página
          </label>
        </div>

        <button 
          class="btn btn-info"
          @click="getList('search')"
        >
          <i class="fa-solid fa-magnifying-glass me-2"></i>
          Pesquisar
        </button>
      </div>

      <div class="border border-light border-opacity-25 rounded shadow-plus">
        <table class="table table-dark table-hover m-0">
          <thead>
            <tr>
              <td>Funcionário</td>
              <td>Cargo</td>
              <td>Situação</td>
              <td>Possui usuário</td>
              <td>Dependentes</td>
              <td>Perfil</td>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="(employee) in search.result"
              :key="employee.id"
              :class="{
                'table-active': (search.selected.id == employee.id),
              }"
              @click="selectRow(employee)"
            >
              <td>{{employee.name}}</td>
              <td>{{employee.job}}</td>
              <td>{{employee.active == 1? 'Ativo': 'Inativo'}}</td>
              <td>
                <button 
                  class="btn btn-outline-light me-2"
                  @click="$emit('profileAction', 'profile', {id_employee:employee.id, tab:'user'})"
                >
                  <i class="fa-solid fa-up-right-from-square"></i>
                </button>
                {{employee.is_user === 'true'? 'Sim': 'Não'}}
              </td>
              <td>
                <button 
                  class="btn btn-outline-light me-2"
                  @click="$emit('profileAction', 'profile', {id_employee:employee.id, tab:'family'})"
                >
                  <i class="fa-solid fa-up-right-from-square"></i>
                </button>
                {{employee.family}}
              </td>
              <td>
                <button 
                  class="btn btn-outline-light"
                  @click="$emit('profileAction', 'profile', {id_employee:employee.id, tab:'info'})"
                >
                  <i class="fa-solid fa-up-right-from-square"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="hstack justify-content-between">
        <div
          v-if="pagination.records"
        >
          <span class=" text-bg-dark border border-light border-opacity-25 rounded p-2 shadow-plus">
            Total {{!pagination.records? 0: pagination.records}}
          </span>
        </div>

        <div 
          v-if="pagination.pages > 1"
          class="input-group shadow-plus"
          style="width: fit-content;"
        >
          <button 
            class="btn btn-info"
            :disabled="!pagination.page"
            @click="getPage('prev')"
          >
            <i class="fa-solid fa-chevron-left"></i>
          </button>
          <span class="input-group-text bg-light-smooth">
            {{`Pág ${pagination.page+1} de ${pagination.pages}`}}
          </span>
          <button 
            class="btn btn-info"
            :disabled="pagination.page == pagination.pages-1"
            @click="getPage('next')"
          >
            <i class="fa-solid fa-chevron-right"></i>
          </button>
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
        pagination: {
          page:        null,
          pages:       null,
          page_limit:  2,
          page_offset: null,
          records:     null
        },
        search: {
          name:     null,
          job_type: null,
          active:   null,
          selected: { id: null },
          result:   []
        },
      }
    },

    computed: {
      job_type(){   return this.search.job_type;       },
      active(){     return this.search.active;         },
      page_limit(){ return this.pagination.page_limit; },
    },

    watch: {
      job_type(){   if(this.pagination.records){ this.getList('param'); } },
      active(){     if(this.pagination.records){ this.getList('param'); } },
      page_limit(){ if(this.pagination.records){ this.getList('param'); } },
    },

    created(){
      this.$emit('isLoading', false);
    },

    mounted(){},

    updated(){},

    activated(){},

    deactivated(){},

    methods: {
      getList(from){
        this.$emit('isLoading', true);
        if(this.pagination.records && (from == 'search' || from == 'param' || from == 'enter')){
          this.pagination.page        = null;
          this.pagination.pages       = null;
          this.pagination.page_offset = null;
          this.pagination.records     = null;
          this.search.result          = [];
          this.search.selected        = { id: null };
        }
        api.listEmployees(
          this.search.name,
          this.search.job_type,
          this.search.active,
          this.pagination.page,
          this.pagination.page_limit
        )
          .then((response)=>{
            if(response.data.success){
              this.search.result          = response.data.result;
              this.pagination.page        = response.data.page;
              this.pagination.pages       = response.data.pages;
              this.pagination.page_limit  = response.data.page_limit;
              this.pagination.page_offset = response.data.page_offset;
              this.pagination.records     = response.data.records;
            } else {
              switch(response.data.error){
                case 'sql':
                  this.$emit('showAlert', 'error', 'SQL inválido, informe o suporte.');
                  break;
                case 'empty':
                  this.$emit('showAlert', 'warning', 'Nenhum resultado encontrado.');
                  break;
              }
            }
          })
          .catch((error)=>{
            console.log(error);
            this.$emit('showAlert', 'error', `Ocorreu um erro no processo: ${error}`);
          })
          .finally(()=>{
            this.$emit('isLoading', false);
          });
      },

      selectRow(employee){
        this.search.selected = employee;
      },

      getPage(direction){
        if(direction == 'next'){
          this.pagination.page++;
        }
        if(direction == 'prev'){
          this.pagination.page--;
        }
        this.getList('pagination');
      },
    },
  }
</script>