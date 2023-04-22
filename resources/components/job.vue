<template>
  <div class="container-lg vstack flex-wrap text-light gap-4 py-4">
    <h3 class="text-center fs-3 m-0"> Trabalho </h3>

    <div class="nav nav-tabs justify-content-center border-light border-opacity-25">
      <button
        ref="departTab"
        class="nav-link active"
        data-bs-toggle="tab"
        data-bs-target="#depart-tab"
        type="button"
      >
        Departamentos
      </button>

      <button
        ref="jobTab"
        class="nav-link"
        data-bs-toggle="tab"
        data-bs-target="#job-tab"
        type="button"
      >
        Cargos
      </button>

      <button
        ref="ManageTab"
        class="nav-link"
        data-bs-toggle="tab"
        data-bs-target="#manage-tab"
        type="button"
      >
        Gerenciar
      </button>
    </div>


    <div class="tab-content">
      <div class="tab-pane fade show active" id="depart-tab">
        <div class="container-lg vstack flex-wrap gap-4 py-4">
          <div class="input-group text-dark shadow-plus">
            <div
              class="form-floating flex-fill"
              style="width: 220px; min-width: 0;"
            >
              <input
                v-model="departs.search.name"
                type="search"
                placeholder="Departamento"
                class="form-control bg-light-smooth"
                @keyup.enter="getDepartsList('enter')"
              >
              <label>Departamento</label>
            </div>

            <div
              class="form-floating flex-fill"
              style="width: 130px; min-width: 0;"
            >
              <select
                v-model="departs.pagination.page_limit"
                class="form-select bg-light-smooth"
              >
                <option :value="10">10</option>
                <option :value="20">20</option>
                <option :value="30">30</option>
              </select>
              <label>
                Resultados por página
              </label>
            </div>

            <button
              class="btn btn-info"
              @click="getDepartsList('search')"
            >
              <i class="fa-solid fa-magnifying-glass me-2"></i>
              Pesquisar
            </button>
          </div>

          <div class="border border-light border-opacity-25 shadow-plus">
            <table class="table table-dark table-hover m-0">
              <thead>
                <tr class="fw-bold">
                  <td>Departamento</td>
                  <td>Descrição</td>
                  <td>Ações</td>
                </tr>
              </thead>

              <tbody>
                <tr
                  v-for="(depart, index) in departs.search.result"
                  :key="depart.id_job_depart"
                  :class="{
                    'table-active': (departs.search.selected == depart.id_job_depart),
                  }"
                  @click="departs.search.selected = depart.id_job_depart"
                >
                  <td>
                    <div v-if="!(editing.departs.editing && editing.departs.index == index)">
                      {{depart.job_depart}}
                    </div>
                    <input
                      v-if="(editing.departs.editing && editing.departs.index == index)"
                      v-model="depart.job_depart"
                      type="text"
                      placeholder="Nome"
                      class="form-control bg-light-smooth"
                    >
                  </td>
                  <td>
                    <div v-if="!(editing.departs.editing && editing.departs.index == index)">
                      {{depart.obs}}
                    </div>
                    <input
                      v-if="(editing.departs.editing && editing.departs.index == index)"
                      v-model="depart.obs"
                      type="text"
                      placeholder="Observação"
                      class="form-control bg-light-smooth"
                    >
                  </td>
                  <td>
                    <div class="hstack justify-content-end gap-1">
                      <button
                        :class="{'d-none': !(editing.departs.editing && editing.departs.index == index)}"
                        class="btn btn-info btn-sm btn-32"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        data-bs-title="Cancelar"
                        @click="hideTooltips(); restoreBackup('departs');"
                      >
                        <i class="fa-solid fa-xmark"></i>
                      </button>
                      <button
                        :class="{'d-none': !(editing.departs.editing && editing.departs.index == index)}"
                        class="btn btn-orange btn-sm btn-32"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        data-bs-title="Salvar"
                        :disabled="!is_depart_ready"
                        @click="hideTooltips(); updateDepart(index)"
                      >
                        <i class="fa-solid fa-check"></i>
                      </button>
                      <button
                        class="btn btn-warning btn-sm btn-32"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        data-bs-title="Editar"
                        :disabled="!(!editing.departs.editing && editing.departs.index == null)"
                        @click="
                          hideTooltips();
                          editing.departs.editing = true;
                          editing.departs.index = index;
                        "
                      >
                        <i class="fa-solid fa-pen"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="hstack justify-content-between">
            <div
              v-if="departs.pagination.records"
            >
              <span class=" text-bg-dark border border-light border-opacity-25 rounded p-2 shadow-plus">
                Total {{!departs.pagination.records? 0: departs.pagination.records}}
              </span>
            </div>

            <div
              v-if="departs.pagination.pages > 1"
              class="input-group shadow-plus"
              style="width: fit-content;"
            >
              <button
                class="btn btn-info"
                :disabled="!departs.pagination.page"
                @click="getPage('prev', 'departs')"
              >
                <i class="fa-solid fa-chevron-left"></i>
              </button>
              <span class="input-group-text bg-light-smooth">
                {{`Pág ${departs.pagination.page+1} de ${departs.pagination.pages}`}}
              </span>
              <button
                class="btn btn-info"
                :disabled="departs.pagination.page == departs.pagination.pages-1"
                @click="getPage('next', 'departs')"
              >
                <i class="fa-solid fa-chevron-right"></i>
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="tab-pane fade" id="job-tab">
        <div class="container-lg vstack flex-wrap gap-4 py-4">
          <div class="input-group text-dark shadow-plus">
            <div
              class="form-floating flex-fill"
              style="width: 220px; min-width: 0;"
            >
              <input
                v-model="jobs.search.name"
                type="search"
                placeholder="Cargo"
                class="form-control bg-light-smooth"
                @keyup.enter="getJobsList('enter')"
              >
              <label>Cargo</label>
            </div>

            <div
              class="form-floating flex-fill"
              style="width: 190px; min-width: 0;"
            >
              <select
                v-model="jobs.search.id_job_depart"
                class="form-select bg-light-smooth"
              >
                <option :value="null">Qualquer departamento</option>
                <option
                  v-for="option in system_types.job_depart"
                  :key="option.value"
                  :value="option.value"
                >
                  {{option.label}}
                </option>
              </select>
              <label>
                Departamento
              </label>
            </div>

            <div
              class="form-floating flex-fill"
              style="width: 130px; min-width: 0;"
            >
              <select
                v-model="jobs.pagination.page_limit"
                class="form-select bg-light-smooth"
              >
                <option :value="10">10</option>
                <option :value="20">20</option>
                <option :value="30">30</option>
              </select>
              <label>
                Resultados por página
              </label>
            </div>

            <button
              class="btn btn-info"
              @click="getJobsList('search')"
            >
              <i class="fa-solid fa-magnifying-glass me-2"></i>
              Pesquisar
            </button>
          </div>

          <div class="border border-light border-opacity-25 shadow-plus">
            <table class="table table-dark table-hover m-0">
              <thead>
                <tr class="fw-bold">
                  <td>Cargo</td>
                  <td>Departamento</td>
                  <td>Salário</td>
                  <td>Ações</td>
                </tr>
              </thead>

              <tbody>
                <tr
                  v-for="(job, index) in jobs.search.result"
                  :key="job.id_job_type"
                  :class="{
                    'table-active': (jobs.search.selected == job.id_job_type),
                  }"
                  @click="jobs.search.selected = job.id_job_type"
                >
                  <td>
                    <div v-if="!(editing.jobs.editing && editing.jobs.index == index)">
                      {{job.job_type}}
                    </div>
                    <input
                      v-if="(editing.jobs.editing && editing.jobs.index == index)"
                      v-model="job.job_type"
                      type="text"
                      placeholder="Nome"
                      class="form-control bg-light-smooth"
                    >
                  </td>
                  <td>
                    <div v-if="!(editing.jobs.editing && editing.jobs.index == index)">
                      {{find_type(system_types.job_depart, job.id_job_depart)}}
                    </div>
                    <select
                      v-if="(editing.jobs.editing && editing.jobs.index == index)"
                      v-model="job.id_job_depart"
                      type="number"
                      class="form-select bg-light-smooth"
                    >
                      <option
                        v-for="option in system_types.job_depart"
                        :key="option.value"
                        :value="option.value"
                      >{{option.label}}</option>
                    </select>
                  </td>
                  <td>
                    <div v-if="!(editing.jobs.editing && editing.jobs.index == index)">
                      {{job.salary | filter_money}}
                    </div>
                    <money
                      v-if="(editing.jobs.editing && editing.jobs.index == index)"
                      v-model="job.salary"
                      placeholder="Salário"
                      class="form-control bg-light-smooth"
                    />
                  </td>
                  <td>
                    <div class="hstack justify-content-end gap-1">
                      <button
                        :class="{'d-none': !(editing.jobs.editing && editing.jobs.index == index)}"
                        class="btn btn-info btn-sm btn-32"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        data-bs-title="Cancelar"
                        @click="hideTooltips(); restoreBackup('jobs');"
                      >
                        <i class="fa-solid fa-xmark"></i>
                      </button>
                      <button
                        :class="{'d-none': !(editing.jobs.editing && editing.jobs.index == index)}"
                        class="btn btn-orange btn-sm btn-32"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        data-bs-title="Salvar"
                        :disabled="!is_job_ready"
                        @click="hideTooltips(); updateJob(index)"
                      >
                        <i class="fa-solid fa-check"></i>
                      </button>
                      <button
                        class="btn btn-warning btn-sm btn-32"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        data-bs-title="Editar"
                        :disabled="!(!editing.jobs.editing && editing.jobs.index == null)"
                        @click="
                          hideTooltips();
                          editing.jobs.editing = true;
                          editing.jobs.index = index;
                        "
                      >
                        <i class="fa-solid fa-pen"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="hstack justify-content-between">
            <div
              v-if="jobs.pagination.records"
            >
              <span class=" text-bg-dark border border-light border-opacity-25 rounded p-2 shadow-plus">
                Total {{!jobs.pagination.records? 0: jobs.pagination.records}}
              </span>
            </div>

            <div
              v-if="jobs.pagination.pages > 1"
              class="input-group shadow-plus"
              style="width: fit-content;"
            >
              <button
                class="btn btn-info"
                :disabled="!jobs.pagination.page"
                @click="getPage('prev', 'jobs')"
              >
                <i class="fa-solid fa-chevron-left"></i>
              </button>
              <span class="input-group-text bg-light-smooth">
                {{`Pág ${jobs.pagination.page+1} de ${jobs.pagination.pages}`}}
              </span>
              <button
                class="btn btn-info"
                :disabled="jobs.pagination.page == jobs.pagination.pages-1"
                @click="getPage('next', 'jobs')"
              >
                <i class="fa-solid fa-chevron-right"></i>
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="tab-pane fade" id="manage-tab">
        <div class="container-lg vstack flex-wrap gap-4 py-4">
          <div
            v-if="!job.new & !depart.new"
            class="bd-callout vstack gap-3 rounded"
          >
            <h3 class="hstack text-warning fs-6 m-0">
              <i class="fa-solid fa-circle-info me-1"></i>
              Cadastre um novo Departamento ou Cargo
              <div class="ms-auto">
                <button
                  class="btn btn-warning btn-sm"
                  @click="depart.new = true"
                >
                  <i class="fa-solid fa-plus me-1"></i>
                  Novo Departamento
                </button>
                <button
                  class="btn btn-warning btn-sm"
                  @click="job.new = true"
                >
                  <i class="fa-solid fa-plus me-1"></i>
                  Novo Cargo
                </button>
              </div>
            </h3>
          </div>

          <div
            v-if="depart.new"
            class="vstack gap-3"
          >
            <div
              class="bd-callout rounded vstack flex-fill gap-3"
            >
              <h3 class="hstack justify-content-between text-light fs-6">
                Novo Departamento
                <div class="ms-auto">
                  <button
                    class="btn btn-info btn-sm"
                    @click="resetDepart"
                  >
                    <i class="fa-solid fa-xmark me-1"></i>
                    Cancelar
                  </button>
                  <button
                    class="btn btn-orange btn-sm"
                    :disabled="!is_newDepart_ready"
                    @click="insertDepart"
                  >
                    <i class="fa-solid fa-check me-1"></i>
                    Salvar
                  </button>
                </div>
              </h3>

              <div class="text-dark hstack flex-wrap gap-3">
                <div
                  class="form-floating"
                  style="width: 400px; min-width: 0;"
                >
                  <input
                    v-model="depart.job_depart"
                    type="text"
                    placeholder="Nome"
                    class="form-control bg-light-smooth"
                  >
                  <label>Nome</label>
                </div>
                <div
                  class="form-floating flex-fill"
                  style="width: 400px; min-width: 0;"
                >
                  <input
                    v-model="depart.obs"
                    type="text"
                    placeholder="Observação"
                    class="form-control bg-light-smooth"
                  >
                  <label>Observação</label>
                </div>
              </div>
            </div>
          </div>

          <div
            v-if="job.new"
            class="vstack gap-3"
          >
            <div
              class="bd-callout rounded vstack flex-fill gap-3"
            >
              <h3 class="hstack text-light fs-6">
                Novo Cargo
                <div class="ms-auto">
                  <button
                    class="btn btn-info btn-sm"
                    @click="resetJob"
                  >
                    <i class="fa-solid fa-xmark me-1"></i>
                    Cancelar
                  </button>
                  <button
                    class="btn btn-orange btn-sm"
                    :disabled="!is_newJob_ready"
                    @click="insertJob"
                  >
                    <i class="fa-solid fa-check me-1"></i>
                    Salvar
                  </button>
                </div>
              </h3>

              <div class="hstack flex-fill gap-3 text-dark">
                <div
                  class="form-floating flex-fill"
                  style="width: 300px; min-width: 0;"
                >
                  <input
                    v-model="job.job_type"
                    type="text"
                    placeholder="Nome"
                    class="form-control bg-light-smooth"
                  >
                  <label>Nome</label>
                </div>

                <div
                  class="form-floating flex-fill"
                  style="width: 300px; min-width: 0;"
                >
                  <select
                    v-model="job.id_job_depart"
                    type="number"
                    class="form-select bg-light-smooth"
                  >
                    <option
                      v-for="option in system_types.job_depart"
                      :key="option.value"
                      :value="option.value"
                    >{{option.label}}</option>
                  </select>
                  <label>Departamento</label>
                </div>
                <div
                  class="form-floating flex-fill"
                  style="width: 200px; min-width: 0;"
                >
                  <money
                    v-model="job.salary"
                    placeholder="Salário"
                    class="form-control bg-light-smooth"
                  />
                  <label>Salário</label>
                </div>
              </div>
            </div>
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
      'system_types',
      'find_type',
    ],

    data(){
      return {
        editing: {
          jobs: {
            editing: false,
            index:   null,
          },
          departs: {
            editing: false,
            index:   null,
          },
        },
        job: {
          new:           false,
          id_job_depart: null,
          job_type:      null,
          salary:        0,
        },
        jobs:    {
          backup: [],
          pagination: {
            page:        null,
            pages:       null,
            page_limit:  10,
            page_offset: null,
            records:     null,
          },
          search: {
            name:          null,
            id_job_depart: null,
            result: [],
            selected: null,
          },
        },
        depart: {
          new:        false,
          job_depart: null,
          obs:        null,
        },
        departs: {
          backup: [],
          pagination: {
            page:        null,
            pages:       null,
            page_limit:  10,
            page_offset: null,
            records:     null,
          },
          search: {
            name:   null,
            result: [],
            selected: null,
          },
        },
        tooltips: [],
      }
    },

    computed: {
      is_job_ready(){
        if(!this.editing.jobs.editing){ return undefined; }
        if(
          this.editing.jobs.editing && (
            (
              this.jobs.search.result[this.editing.jobs.index].job_type !=
                this.jobs.backup[this.editing.jobs.index].job_type &&
                  this.jobs.search.result[this.editing.jobs.index].job_type
            ) ||
            (
              this.jobs.search.result[this.editing.jobs.index].id_job_depart !=
                this.jobs.backup[this.editing.jobs.index].id_job_depart &&
                  this.jobs.search.result[this.editing.jobs.index].id_job_depart
            ) ||
            (
              this.jobs.search.result[this.editing.jobs.index].salary !=
                this.jobs.backup[this.editing.jobs.index].salary &&
                  this.jobs.search.result[this.editing.jobs.index].salary
            )
          )
        ){ return true; } else { return false; }
      },

      is_depart_ready(){
        if(!this.editing.departs.editing){ return undefined; }
        if(
          this.editing.departs.editing && (
            (
              this.departs.search.result[this.editing.departs.index].job_depart !=
                this.departs.backup[this.editing.departs.index].job_depart &&
                  this.departs.search.result[this.editing.departs.index].job_depart
            ) ||
            (
              this.departs.search.result[this.editing.departs.index].obs !=
                this.departs.backup[this.editing.departs.index].obs &&
                  this.departs.search.result[this.editing.departs.index].obs
            )
          )
        ){ return true; } else { return false; }
      },

      is_newJob_ready(){
        if(!this.job.new){ return undefined; }
        if(
          this.job.new && (
            this.job.job_type && this.job.id_job_depart && this.job.salary
          )
        ){ return true; } else { return false; }
      },

      is_newDepart_ready(){
        if(!this.depart.new){ return undefined; }
        if(
          this.depart.new && (
            this.depart.job_depart && this.depart.obs
          )
        ){ return true; } else { return false; }
      },

      search_job_depart_param(){  return this.jobs.search.id_job_depart;  },

      search_job_page_param(){    return this.jobs.pagination.page_limit; },

      search_depart_page_param(){ return this.departs.pagination.page_limit; },
    },

    watch: {
      search_job_depart_param(){  if(this.jobs.pagination.records){ this.getJobsList('param'); } },

      search_job_page_param(){    if(this.jobs.pagination.records){ this.getJobsList('param'); } },

      search_depart_page_param(){ if(this.departs.pagination.records){ this.getDepartsList('param'); } },
    },

    created(){
      this.$emit('isLoading', false);
    },

    mounted(){
      this.getDepartsList();
      this.getJobsList();
      this.tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
      [...this.tooltips].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    },

    updated(){
      this.tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
      [...this.tooltips].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    },

    activated(){},

    deactivated(){},

    methods: {
      async getJobsList(from = undefined){
        this.$emit('isLoading', true);
        if(this.jobs.pagination.records && (from == 'search' || from == 'param' || from == 'enter')){
          this.jobs.pagination.page        = null;
          this.jobs.pagination.pages       = null;
          this.jobs.pagination.page_offset = null;
          this.jobs.pagination.records     = null;
          this.jobs.search.result          = [];
        }
        api.listJobs(
          this.jobs.search.name,
          this.jobs.search.id_job_depart,
          this.jobs.pagination.page,
          this.jobs.pagination.page_limit
        )
          .then((response)=>{
            if(response.data.success){
              this.jobs.backup                 = JSON.parse(JSON.stringify(response.data.result));
              this.jobs.search.result          = response.data.result;
              this.jobs.pagination.page        = response.data.page;
              this.jobs.pagination.pages       = response.data.pages;
              this.jobs.pagination.page_limit  = response.data.page_limit;
              this.jobs.pagination.page_offset = response.data.page_offset;
              this.jobs.pagination.records     = response.data.records;
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

      async getDepartsList(from = undefined){
        this.$emit('isLoading', true);
        if(this.departs.pagination.records && (from == 'search' || from == 'param' || from == 'enter')){
          this.departs.pagination.page        = null;
          this.departs.pagination.pages       = null;
          this.departs.pagination.page_offset = null;
          this.departs.pagination.records     = null;
          this.departs.search.result          = [];
        }
        api.listDeparts(
          this.departs.search.name,
          this.departs.pagination.page,
          this.departs.pagination.page_limit
        )
          .then((response)=>{
            if(response.data.success){
              this.departs.backup                 = JSON.parse(JSON.stringify(response.data.result));
              this.departs.search.result          = response.data.result;
              this.departs.pagination.page        = response.data.page;
              this.departs.pagination.pages       = response.data.pages;
              this.departs.pagination.page_limit  = response.data.page_limit;
              this.departs.pagination.page_offset = response.data.page_offset;
              this.departs.pagination.records     = response.data.records;
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

      insertJob(){
        this.$emit('isLoading', true);
        api.insertJob(
          this.job
        )
          .then((response)=>{
            if(response.data.success){
              this.resetJob();
              this.$emit('refreshSystemTypes');
              this.$emit('showAlert', 'success', 'Cargo cadastrado com sucesso.');
            }
          })
          .catch((error)=>{
            console.log(error);
          })
          .finally(()=>{
            this.$emit('isLoading', false);
          });
      },

      insertDepart(){
        this.$emit('isLoading', true);
        api.insertDepart(
          this.depart
        )
          .then((response)=>{
            if(response.data.success){
              this.resetDepart();
              this.$emit('refreshSystemTypes');
              this.$emit('showAlert', 'success', 'Departamento cadastrado com sucesso.');
            }
          })
          .catch((error)=>{
            console.log(error);
          })
          .finally(()=>{
            this.$emit('isLoading', false);
          });
      },

      updateJob(index){
        this.$emit('isLoading', true);
        api.updateJob(this.jobs.search.result[index])
          .then((response)=>{
            if(response.data.success){
              this.editing.jobs = {
                editing: false,
                index:   null,
              };
              this.getJobsList('update')
                .then(()=>{
                  this.$emit('showAlert', 'success', 'Cargo atualizado com sucesso.');
                });
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

      updateDepart(index){
        this.$emit('isLoading', true);
        api.updateDepart(this.departs.search.result[index])
          .then((response)=>{
            if(response.data.success){
              this.editing.departs = {
                editing: false,
                index:   null,
              };
              this.getDepartsList('update')
                .then(()=>{
                  this.$emit('showAlert', 'success', 'Departamento atualizado com sucesso.');
                });
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

      resetJob(){
        this.job = {
          new:           false,
          id_job_depart: null,
          job_type:      null,
          salary:        0,
        };
      },

      resetDepart(){
        this.depart = {
          new:        false,
          job_depart: null,
          obs:        null,
        };
      },

      restoreBackup(type){
        switch(type){
          case 'jobs':
            this.editing.jobs = {
              editing: false,
              index:   null,
            };
            this.jobs.search.result = JSON.parse(JSON.stringify(this.jobs.backup));
            break;
          case 'departs':
            this.editing.departs = {
              editing: false,
              index:   null,
            };
            this.departs.search.result = JSON.parse(JSON.stringify(this.departs.backup));
            break;
        }
      },

      getPage(direction, type){
        switch(type){
          case 'jobs':
            if(direction == 'next'){
              this.jobs.pagination.page++;
            }
            if(direction == 'prev'){
              this.jobs.pagination.page--;
            }
            this.getJobsList('pagination');
          case 'departs':
            if(direction == 'next'){
              this.departs.pagination.page++;
            }
            if(direction == 'prev'){
              this.departs.pagination.page--;
            }
            this.getDepartsList('pagination');
        }
      },

      hideTooltips(){
        this.tooltips.forEach(el => bootstrap.Tooltip.getOrCreateInstance(el).hide());
      },
    },
  }
  </script>