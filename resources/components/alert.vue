<template>
  <div id="toasts" ref="toasts" class="toast-container p-3 fixed top-0 end-0"></div>
</template>


<script>
  export default {
    components: {},

    directives: {},

    props: [],

    data(){ return {} },

    computed: {},

    watch: {},

    created(){},

    mounted(){},

    updated(){},

    activated(){},

    deactivated(){},

    methods: {
      showAlert(type, msg){
        const toasts      = this.$refs.toasts
        const toastOption = { animation: true, autohide: true, delay: 7000 }

        if (type === 'error') {
          let toast = document.createElement("div")
          toast.innerHTML=`
            <div class="toast-header text-bg-dark">
              <img
                alt="logotipo do sistema"
                src="/img/fav.png"
                class="rounded me-2"
                style="
                  width: 24px;
                  height: 24px;
                "
              >
              <strong>Xpert</strong>
              <small class="m-auto">Erro</small>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body text-light bg-danger bg-opacity-25 border-top border-danger border-opacity-25 d-flex align-items-center">
              <i class="fa-solid fa-circle-xmark me-2 fs-4"></i>
              ${msg}
            </div>
          `
          toast.setAttribute('class', 'toast bg-dark border border-danger border-opacity-25 shadow-plus')
          toast.addEventListener('hidden.bs.toast', () => {
            toast.remove()
          })
          toasts.append(toast)
          new bootstrap.Toast(toast, toastOption).show()
        }

        if (type === 'success') {
          let toast = document.createElement("div")
          toast.innerHTML=`
            <div class="toast-header text-bg-dark">
              <img
                alt="logotipo do sistema"
                src="/img/fav.png"
                class="rounded me-2"
                style="
                  width: 24px;
                  height: 24px;
                "
              >
              <strong>Xpert</strong>
              <small class="m-auto">Sucesso</small>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body text-light bg-success bg-opacity-25 border-top border-success border-opacity-25 d-flex align-items-center">
              <i class="fa-solid fa-circle-check me-2 fs-4"></i>
              ${msg}
            </div>
          `
          toast.setAttribute('class', 'toast bg-dark border border-success border-opacity-25 shadow-plus')
          toast.addEventListener('hidden.bs.toast', () => {
            toast.remove()
          })
          toasts.append(toast)
          new bootstrap.Toast(toast, toastOption).show()
        }

        if (type === 'warning') {
          let toast = document.createElement("div")
          toast.innerHTML=`
            <div class="toast-header text-bg-dark">
              <img
                alt="logotipo do sistema"
                src="/img/fav.png"
                class="rounded me-2"
                style="
                  width: 24px;
                  height: 24px;
                "
              >
              <strong>Xpert</strong>
              <small class="m-auto">Aviso</small>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body text-light bg-warning bg-opacity-25 border-top border-warning border-opacity-25 d-flex align-items-center">
              <i class="fa-solid fa-circle-exclamation me-2 fs-4"></i>
              ${msg}
            </div>
          `
          toast.setAttribute('class', 'toast bg-dark border border-warning border-opacity-25 shadow-plus')
          toast.addEventListener('hidden.bs.toast', () => {
            toast.remove()
          })
          toasts.append(toast)
          new bootstrap.Toast(toast, toastOption).show()
        }
      },
    },
  }
</script>