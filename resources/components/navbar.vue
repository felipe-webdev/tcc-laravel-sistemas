<template>
    <nav
      class="
        navbar
        navbar-expand-lg
        navbar-dark
        text-bg-dark
        sticky-top
        flex-column
        shadow-plus
        p-0
      "
    >
      <div class="container-fluid bg-light bg-opacity-10 border-bottom border-light border-opacity-25">
        <div class="w-100 collapse" id="userInfo">
          <div
            class="
              w-100
              d-flex
              justify-content-end
              flex-wrap
              gap-2
              py-1
            "
          >
            <div
              class="
                d-flex
                justify-content-start
                align-content-center
                align-items-center
                flex-wrap
                flex-grow-1
                gap-2
              "
            >
              <span class="text-light p-2">
                <i class="fa-solid fa-briefcase me-1"></i>
                <b class="me-2">Empresa:</b> {{session_user.entity}}
              </span>
              <span class="text-light p-2">
                <i class="fa-solid fa-user me-1"></i>
                <b class="me-2">Usuário:</b> {{session_user.user}}
              </span>
              <span class="text-light p-2">
                <i class="fa-solid fa-user-group me-1"></i>
                <b class="me-2">Grupo:</b> {{session_user.group}}
              </span>
            </div>

            <div
              class="
                d-flex
                justify-content-end
                flex-wrap
                gap-2
              "
            >
              <button
                class="btn btn-outline-info flex-grow-1"
                @click="$emit('sessionAction', 'alterPass')"
              >
                Alterar Senha
                <i class="fa-solid fa-key ms-2"></i>
              </button>
              <button
                class="btn btn-outline-danger btn-outline-orange flex-grow-1"
                @click="$emit('sessionAction', 'logout')"
              >
                Sair
                <i class="fa-solid fa-right-from-bracket ms-2"></i>
              </button>
            </div>
          </div>
        </div>
      </div>


      <div class="container-fluid border-bottom border-light border-opacity-25">
        <button
          class="navbar-toggler"
          data-bs-toggle="offcanvas"
          data-bs-target="#navbarContent"
        >
          <i class="fa-sharp fa-solid fa-bars"></i>
        </button>
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

        <div class="offcanvas offcanvas-start text-bg-dark" id="navbarContent">
          <div class="offcanvas-header">
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
            <button
              class="btn-close btn-close-white"
              data-bs-dismiss="offcanvas"
            ></button>
          </div>

          <div class="offcanvas-body">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a
                  data-bs-dismiss="offcanvas"
                  class="nav-link nav-pointer"
                  :class="{'disabled': activeTab == 'dashboard'}"
                  @click="changeTab('dashboard')"
                >
                  Dashboard
                </a>
              </li>
              <li class="nav-item">
                <a
                  data-bs-dismiss="offcanvas"
                  class="nav-link nav-pointer"
                  :class="{'disabled': activeTab == 'list'}"
                  @click="changeTab('list')"
                >
                  Funcionários
                </a>
              </li>
              <li class="nav-item">
                <a
                  data-bs-dismiss="offcanvas"
                  class="nav-link nav-pointer"
                  :class="{'disabled': activeTab == 'job'}"
                  @click="changeTab('job')"
                >
                  Trabalho
                </a>
              </li>
              <!-- <li class="nav-item">
                <a
                  data-bs-dismiss="offcanvas"
                  class="nav-link nav-pointer"
                  :class="{'disabled': activeTab == 'cropper'}"
                  @click="changeTab('cropper')"
                >
                  Teste cropper
                </a>
              </li> -->
              <li class="nav-item dropdown">
                <a
                  class="nav-link dropdown-toggle nav-pointer"
                  data-bs-toggle="dropdown"
                >
                  Ações rápidas
                </a>
                <ul class="dropdown-menu dropdown-menu-dark">
                  <li>
                    <a
                      data-bs-dismiss="offcanvas"
                      class="dropdown-item nav-pointer"
                      @click="$emit('newRegistration', 'employee')"
                    >
                      Cadastrar Funcionário
                    </a>
                  </li>
                  <li>
                    <a
                      data-bs-dismiss="offcanvas"
                      class="dropdown-item nav-pointer"
                      @click="$emit('newRegistration', 'family')"
                    >
                      Cadastrar Dependente
                    </a>
                  </li>
                  <li>
                    <a
                      data-bs-dismiss="offcanvas"
                      class="dropdown-item nav-pointer"
                      @click="$emit('newRegistration', 'user')"
                    >
                      Cadastrar Usuário
                    </a>
                  </li>
                  <!-- <li><hr class="dropdown-divider"></li>
                  <li>
                    <a
                      data-bs-dismiss="offcanvas"
                      class="dropdown-item nav-pointer disabled"
                    >
                      Em Breve
                    </a>
                  </li> -->
                </ul>
              </li>
            </ul>
          </div>
        </div>

        <button
          class="btn btn-outline-light"
          data-bs-toggle="collapse"
          data-bs-target="#userInfo"
        >
          {{session_user.name_user}}
          <i class="fa-solid fa-user-gear ms-2"></i>
        </button>
      </div>
    </nav>
</template>


<script>
  export default {
    components: {},

    directives: {},

    props: [
      'session_user',
      'activeTab',
    ],

    data(){ return {} },

    computed: {},

    watch: {},

    created(){},

    mounted(){},

    updated(){},

    activated(){},

    deactivated(){},

    methods: {
      changeTab(tab){
        this.$emit('changeTab', tab)
      },
    },
  }
</script>