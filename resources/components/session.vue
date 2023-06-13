<template>
  <div>
    <div
      class="modal fade"
      id="staticBackdrop"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      ref="alterPassModal"
    >
      <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
          <div class="modal-header border border-info border-opacity-25 py-1">
            <a class="navbar-brand d-flex align-items-center" href="#">
              <img
                src="/img/nav.png"
                alt="Logo"
                width="38"
                height="38"
                class="rounded me-2"
              >
              Xpert
            </a>
            <h1 class="modal-title fs-3" id="staticBackdropLabel">Alterar Senha</h1>
            <button
              class="btn-close btn-close-white m-0"
              data-bs-dismiss="modal"
            ></button>
          </div>
          <div class="modal-body bd-callout bd-callout-info">
            <div class="hstack gap-2">
              <input
                class="form-control"
                type="password"
                placeholder="senha atual"
                v-model="old_pass"
                @keyup.enter="alterPass"
              >
              <input
                class="form-control"
                type="password"
                placeholder="senha nova"
                v-model="new_pass"
                @keyup.enter="alterPass"
              >
            </div>
          </div>
          <div class="modal-footer border border-info border-opacity-25 py-1">
            <button
              class="btn btn-info"
              data-bs-dismiss="modal"
            >
              <i class="fa-solid fa-xmark me-1"></i>
              Cancelar
            </button>
            <button
              class="btn btn-primary btn-orange"
              :disabled="
                (old_pass == '' || new_pass == '') ||
                (old_pass == new_pass)
              "
              @click="alterPass"
            >
              <i class="fa-solid fa-check me-1"></i>
              Alterar
            </button>
          </div>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      id="staticBackdrop"
      data-bs-backdrop="static"
      data-bs-keyboard="false"
      tabindex="-1"
      ref="logoutModal"
    >
      <div class="modal-dialog">
        <div class="modal-content bg-dark text-light">
          <div class="modal-header border border-warning border-opacity-25 py-1">
            <a class="navbar-brand d-flex align-items-center" href="#">
              <img
                src="/img/nav.png"
                alt="Logo"
                width="38"
                height="38"
                class="rounded me-2"
              >
              Xpert
            </a>
            <h1 class="modal-title fs-3" id="staticBackdropLabel">Encerrar sessão</h1>
            <button
              class="btn-close btn-close-white m-0"
              data-bs-dismiss="modal"
            ></button>
          </div>
          <div class="modal-body bd-callout bd-callout-warning">
            Deseja mesmo sair do sistema?
          </div>
          <div class="modal-footer border border-warning border-opacity-25 py-1">
            <button
              class="btn btn-info"
              data-bs-dismiss="modal"
            >
              <i class="fa-solid fa-xmark me-1"></i>
              Cancelar
            </button>
            <button class="btn btn-primary btn-orange" @click="logout">
              <i class="fa-solid fa-check me-1"></i>
              Sair
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

    props: [ 'session_user', ],

    data(){
      return {
        old_pass: '',
        new_pass: '',
      }
    },

    computed: {},

    watch: {},

    created(){
      this.getUser()
      setInterval(this.getUser, 300000)
    },

    mounted(){},

    updated(){},

    activated(){},

    deactivated(){},

    methods: {
      getUser(){
        api.getSessionUser()
          .then((response)=>{
            response.data.session_started?
              this.$emit('setSessionUser', response.data.session_user):
              window.location.href = `${window.location.origin}/login`
          })
          .catch((error)=>{
            console.log(error)
            this.$emit('showAlert', 'error', `Ocorreu um erro no processo: ${error}`)
          })
          .finally(()=>{
          })
      },

      openLogoutModal(){
        bootstrap.Modal.getOrCreateInstance(this.$refs.logoutModal).show()
      },

      logout(){
        bootstrap.Modal.getOrCreateInstance(this.$refs.logoutModal).hide()
        this.$emit('isLoading', true)
        api.logout(this.session_user.id_user)
          .then((response)=>{
            if(response.data.session_ended){
              this.$emit('showAlert', 'success', 'Sessão encerrada com sucesso.')
              setTimeout(()=>{window.location.href = `${window.location.origin}/login`}, 1000)
            } else {
              this.$emit('showAlert', 'error', 'Sessão do usuário não encontrada.')
            }
          })
          .catch((error)=>{
            console.log(error)
            this.$emit('showAlert', 'error', `Ocorreu um erro no processo: ${error}`)
          })
          .finally(()=>{
            this.$emit('isLoading', false)
          })
      },

      openAlterPassModal(){
        bootstrap.Modal.getOrCreateInstance(this.$refs.alterPassModal).show()
      },

      alterPass(){
        if(this.old_pass == '' || this.new_pass == ''){
          return this.$emit('showAlert', 'warning', 'Senha atual e senha nova devem ser preenchidas.')
        }
        if(this.old_pass == this.new_pass){
          return this.$emit('showAlert', 'warning', 'Senha atual e senha nova não podem ser iguais.')
        }
        bootstrap.Modal.getOrCreateInstance(this.$refs.alterPassModal).hide()
        this.$emit('isLoading', true)
        api.alterPass(this.session_user.id_user, this.old_pass, this.new_pass)
          .then((response)=>{
            if(response.data.success){
              this.$emit('showAlert', 'success', 'Senha alterada com sucesso.')
            } else {
              switch(response.data.error){
                case 'old_pass':
                  this.$emit('showAlert', 'error', 'Senha atual inválida.')
                  break
                case 'new_pass':
                  this.$emit('showAlert', 'error', 'Senha nova é igual a senha atual.')
                  break
                case 'sql':
                  this.$emit('showAlert', 'error', 'SQL inválido, informe o suporte.')
                  break
              }
            }
          })
          .catch((error)=>{
            console.log(error)
            this.$emit('showAlert', 'error', `Ocorreu um erro no processo: ${error}`)
          })
          .finally(()=>{
            this.old_pass = ''
            this.new_pass = ''
            this.$emit('isLoading', false)
          })
      },
    },
  }
</script>
