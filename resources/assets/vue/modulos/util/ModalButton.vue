<template>
   	<button :class="'btn '+ btnClass" @click="eventoclick();$modal.show(''+modal+'')" :title="title">
        <span :class="textoBotaoDirecao">
            <i :class="'fa ' + icone" v-if="icone" ></i> 
            &nbsp;{{ texto }}
        </span>
    </button>
</template>

<script>
    export default {
      props:['texto', 'icone', 'modal', 'title', 'btnClass', 'textoBotaoDirecao', 'param'],
      methods: { 
      	eventoclick(){
      		this.$emit('eventoclick', this.param)
      	}
      }
    };
</script> 
