<template>   	
  
   	<a href="#" :class="layout" @click="eventoclick();$modal.show(''+modal+'')" :title="title" >
  	 	<i :class="'fa '+icone" v-if="icone" ></i> 
  	 	 &nbsp;{{ texto }}
   	</a>
   
</template>

<script>
    export default {
      props:['texto', 'icone', 'modal', 'layout', 'title', 'param'],
      methods: { 
        eventoclick(){
            this.$emit('eventoclick', this.param)
        }
      }
    };
</script> 
