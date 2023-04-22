<template>
  <div
    ref="login"
    class="
      vh-100
      d-flex
      flex-row
      justify-content-center
      align-content-center
      align-items-center
    "
  >
    <loading
      ref="loading"
      :isLoading="isLoading"
    />
    <alert
      ref="alert"
    />
    <div class="blurred"></div>
    <div
      class="
        border
        rounded
        shadow
        text-bg-dark
        login-container
      "
    >
      <div class="mb-3">
        <div
          class="
            d-flex
            flex-row
            align-content-center
            align-items-center
          "
        >
          <div class="login-logo">
            <img
              alt="logotipo do sistema"
              src="/img/logo.png"
              class="
                rounded
                w-100
                h-100
              "
            >
          </div>
          <div
            class="
              m-auto
              login-titulo
            "
          >
            Xpert
          </div>
        </div>
      </div>

      <div class="px-3">
        <input
          class="
            form-control
            mb-3
          "
          type="text"
          placeholder="usuario@empresa"
          :disabled="disabled"
          @keyup.enter="login"
          v-model="user"
        />
        <input
          class="
            form-control
            mb-3
          "
          type="password"
          placeholder="senha"
          :disabled="disabled"
          @keyup.enter="login"
          v-model="pass"
        />
      </div>

      <div
        class="
          px-3
          d-flex
          justify-content-center
        "
      >
        <button
          type="button"
          class="
            btn
            btn-primary
          "
          :disabled="(user == '' || pass == '') || disabled"
          @click="login"
        >
          <span
            v-if="loadingLogin"
            class="
              spinner-border
              spinner-border-sm
            "
          ></span>
          Entrar
          <i class="fa-solid fa-right-to-bracket ms-1"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    components: {},

    directives: {},

    props: [],

    data(){
      return {
        isLoading:    false,
        loadingLogin: false,
        disabled:     false,
        user:         '',
        pass:         '',
      }
    },

    computed: {},

    watch: {},

    created(){
      window.app = this;
      axios.get('/sanctum/csrf-cookie')
    },

    mounted(){},

    updated(){},

    activated(){},

    deactivated(){},

    methods: {
      login(){
        if (this.user == '' || this.pass == ''){
          return this.$refs.alert.showAlert('warning', 'Usuário e Senha devem ser preenchidos.');
        }
        this.isLoading    = true;
        this.disabled     = true;
        this.loadingLogin = true;
        api.login(this.user, this.pass)
          .then((response)=>{
            if (response.data.session_started){
              this.$refs.alert.showAlert('success', 'Sessão iniciada com sucesso.');
              setTimeout(()=>{window.location.href = `${window.location.origin}/app`}, 1000);
            } else {
              switch (response.data.error) {
                case 'no match or inactive':
                  this.$refs.alert.showAlert('error', 'Credenciais inválidas ou cadastro inativo.');
                  break;
                case 'user':
                  this.$refs.alert.showAlert('error', 'Usuário inválido.');
                  break;
                case 'pass':
                  this.$refs.alert.showAlert('error', 'Senha inválida.');
                  break;
                case 'active':
                  this.$refs.alert.showAlert('error', 'Seu cadastro encontra-se inativo.');
                  break;
                case 'sql':
                  this.$refs.alert.showAlert('error', 'SQL inválido, informe o suporte.');
                  break;
              }
            }
          })
          .catch((error)=>{
            console.log(error);
            this.$refs.alert.showAlert('error', `Ocorreu um erro no processo: ${error}`);
          })
          .finally(()=>{
            this.isLoading    = false;
            this.disabled     = false;
            this.loadingLogin = false;
          });
      },
    },
  }
</script>