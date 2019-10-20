<template>        
    <!-- 
        Este componente, tem por responsabilidade apenas abrir um modal de imagem, sendo que esta abre por link. 
        Não mais carregando um base46. 
    -->
        <modal :name="'show-imagem-modal'+id" transition="pop-out" :width="modalWidth" :height="modalHeight">
            <div class="modal-content text-center modal-vue-img"  >
                <img :src="src">            
            </div>
        </modal>    
    
</template>
<script>    
    export default {
        props:['src','id','modalWidth', 'modalHeight'],
        name: 'ImagemModal',        
    };
</script>
<style scope>

    .v--modal-box.v--modal{
        background-color: #fff0 !important;
        box-shadow: none !important;
    }
    .modal-vue-img{
        background-color: transparent;
        box-shadow: none;
    }


</style>
