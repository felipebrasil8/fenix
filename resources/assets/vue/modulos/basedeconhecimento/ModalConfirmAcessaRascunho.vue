<template>    
    <modal :name="'modal-confirm-acessa-rascunho'" transition="pop-out" :width="600" height="auto">
        <div class="modal-content" >
            <div class="modal-header">    
                <button type="button" class="close" @click="$modal.hide('modal-confirm-acessa-rascunho')">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modal-title">{{ titulo }}</h4>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" v-on:click="$modal.hide('modal-confirm-acessa-rascunho')" style="margin-right: 10px;">NÃO</button>
                <button type="button" class="btn btn-primary" v-on:click="confirma()" autofocus>SIM</button>                
            </div>
        </div>
    </modal>
</template>

<script>
    const MODAL_WIDTH = 600

    export default {        
        props:['id', 'titulo'],
        name: 'ModalConfirmAcessaRascunho',
        data(){
            return {
                modalWidth: 600
            }                
        },
        methods: {
            confirma(){
                window.location.href = '/base-de-conhecimento/publicacoes/'+this.id+'/rascunho';                
            }
        },
        computed: {
            
        },
        mounted(){            
        },
        created() {            
            this.modalWidth = window.innerWidth < 600
                ? 600 / 2
                : 600
        }
    };
</script>