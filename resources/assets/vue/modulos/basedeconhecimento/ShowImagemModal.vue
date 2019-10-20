<template>
   	<modal :name="'show-imagem-modal'+conteudoId" transition="pop-out" :width="modalWidth" :height="modalHeight">
        <div class="modal-content text-center" style="background-color: #fff0">
            <img :src="getUrlImagem() + conteudoId">
        </div>
    </modal>
</template>
<script>
    const MODAL_WIDTH = 1200;
    export default {
        props:['conteudoId','imagemWidth','imagemHeight'],
        name: 'ImagemModal',
        data (){
            return {
                modalWidth: this.imagemWidth,
                modalHeight: this.imagemHeight,
            }
        },
        methods: {
            getUrlImagem(){
                return '/base-de-conhecimento/publicacoes/img-conteudo-original/';
            },
        }
    };
</script>
<style>
    .v--modal-box.v--modal{
        background-color: #fff0 !important;
        box-shadow: none !important;
    }
    .v--modal-box.v--modal img{
        background-color: #fff;
    }
</style>

