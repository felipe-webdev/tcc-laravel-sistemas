<template>
    <div>
      <div :class="{'d-none': !isLoading}">
        <i class="fa-brands fa-whatsapp"></i>
        <i class="fa-solid fa-font-awesome"></i>
        <i class="fa-regular fa-font-awesome"></i>
      </div>
      <loading
        ref="loading"
        :isLoading="isLoading"
      />
      <alert
        ref="alert"
      />
      <session
        ref="session"
        :session_user="session_user"
        @setSessionUser="setSessionUser"
        @showAlert="showAlert"
        @isLoading="setIsLoading"
      />
      <registration
        ref="registration"
        :session_user="session_user"
        :system_types="system_types"
        @showAlert="showAlert"
        @isLoading="setIsLoading"
      />
      <profile
        ref="profile"
        :session_user="session_user"
        :system_types="system_types"
        @showAlert="showAlert"
        @isLoading="setIsLoading"
      />
      <navbar
        ref="navbar"
        :session_user="session_user"
        :activeTab="activeTab"
        @changeTab="setActiveTab"
        @sessionAction="sessionAction"
        @newRegistration="newRegistration"
        @showAlert="showAlert"
        @isLoading="setIsLoading"
      />
      <keep-alive>
        <component
          ref="activeComp"
          :is="activeTab"
          :session_user="session_user"
          :system_types="system_types"
          @newRegistration="newRegistration"
          @showAlert="showAlert"
          @isLoading="setIsLoading"
          @profileAction="profileAction"
        />
      </keep-alive>
    </div>
</template>

<script>
  export default {
    components: {},

    directives: {},

    props: [],

    data(){
      return {
        activeTab: 'dashboard',
        isLoading: false,
        session_user: {},
        system_types: {
          person:  [],
          family:  [],
          contact: [],
          address: [],
          uf: [
            {label: 'AC - Acre', value: 'AC'},
            {label: 'AP - Amapá', value: 'AP'},
            {label: 'AL - Alagoas', value: 'AL'},
            {label: 'AM - Amazonas', value: 'AM'},
            {label: 'BA - Bahia', value: 'BA'},
            {label: 'CE - Ceará', value: 'CE'},
            {label: 'DF - Distrito Federal', value: 'DF'},
            {label: 'RO - Rondônia', value: 'RO'},
            {label: 'RR - Roraima', value: 'RR'},
            {label: 'PA - Pará', value: 'PA'},
            {label: 'TO - Tocantins', value: 'TO'},
            {label: 'MA - Maranhão', value: 'MA'},
            {label: 'PI - Piauí', value: 'PI'},
            {label: 'RN - Rio Grande do Norte', value: 'RN'},
            {label: 'PB - Paraíba', value: 'PB'},
            {label: 'PE - Pernambuco', value: 'PE'},
            {label: 'SE - Sergipe', value: 'SE'},
            {label: 'MG - Minas Gerais', value: 'MG'},
            {label: 'ES - Espírito Santo', value: 'ES'},
            {label: 'RJ - Rio de Janeiro', value: 'RJ'},
            {label: 'SP - São Paulo', value: 'SP'},
            {label: 'PR - Paraná', value: 'PR'},
            {label: 'SC - Santa Catarina', value: 'SC'},
            {label: 'RS - Rio Grande do Sul', value: 'RS'},
            {label: 'MS - Mato Grosso do Sul', value: 'MS'},
            {label: 'MT - Mato Grosso', value: 'MT'},
            {label: 'GO - Goiás', value: 'GO'},
          ],
          job:        [],
          job_depart: [],
          user_group: []
        }
      }
    },

    computed: {},

    watch: {
      session_user(){
        this.getSystemTypes();
      }
    },

    created(){
      window.app = this;
      axios.get('/sanctum/csrf-cookie').then(response => {
        console.log(response)
      });
    },

    mounted(){},

    updated(){},

    methods: {
      showAlert(type, msg){
        this.$refs.alert.showAlert(type, msg);
      },

      setIsLoading(value){
        this.isLoading = value;
      },

      setActiveTab(value){
        this.activeTab = value;
      },

      setSessionUser(value){
        this.session_user = value;
      },

      sessionAction(action){
        switch(action){
          case 'logout':
            this.$refs.session.openLogoutModal();
            break;
          case 'alterPass':
            this.$refs.session.openAlterPassModal();
            break;
        }
      },

      profileAction(action, args){
        switch(action){
          case 'profile':
            this.$refs.profile.openProfileModal(args);
            break;
        }
      },

      newRegistration(type){
        switch(type){
          case 'employee':
            this.$refs.registration.openNewEmployeeModal();
            break;
          case 'family':
            this.$refs.registration.openNewFamilyModal();
            break;
          case 'user':
            this.$refs.registration.openNewUserModal();
            break;
        }
      },

      getSystemTypes(){
        this.$emit('isLoading', true);
        api.getSystemTypes()
          .then((response)=>{
            this.system_types.person     = response.data.person;
            this.system_types.family     = response.data.family;
            this.system_types.contact    = response.data.contact;
            this.system_types.address    = response.data.address;
            this.system_types.job        = response.data.job;
            this.system_types.job_depart = response.data.job_depart;
            this.system_types.user_group = response.data.user_group.filter((group)=>{
              return group.value >= this.session_user.id_group;
            });
          })
          .catch((error)=>{
            console.log(error);
            this.showAlert('error', `Ocorreu um erro no processo: ${error}`);
          })
          .finally(()=>{
            this.$emit('isLoading', false);
          });
      },
    },
  }
</script>