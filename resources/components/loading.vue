<template>
	<div
		v-if="isLoading"
		class="
			w-100
			h-100
			d-flex
			justify-content-center
			align-items-center
			loading-fundo
		"
	>
		<div 
			class="spinner-grow"
			style="
				height: 50px;
				width: 50px;
				position: absolute;
				color: darkorange;
			"
		></div>
		<div 
			class="spinner-border text-info fs-1"
			style="
				height: 50px;
				width: 50px;
				position: absolute;
			"
		></div>
	</div>
</template>


<script>
  export default {
    components: {},

    directives: {},

    props: [ 'isLoading', ],

    data(){ return {} },

    computed: {},

    watch: {},

    created(){},

    mounted(){},
		
    updated(){},

    activated(){},

    deactivated(){},

    methods: {},
  }
</script>