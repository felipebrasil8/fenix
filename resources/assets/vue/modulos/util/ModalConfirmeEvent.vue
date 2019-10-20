<template>
<modal name="modal-confirm" transition="pop-out" :width="modalWidth" height="auto" @before-open="beforeOpen" @opened="opened" before-close="beforeClose">

<div class="modal-content" >
    <div class="modal-header">    
        <button type="button" class="close" @click="$modal.hide('modal-confirm')">
            <span aria-hidden="true">&times;</span>
        </button>
    
        <h4 class="modal-title" id="modal-title">{{titulo}}</h4>
    </div>
    
    <div class="modal-body" v-if="errors.length > 0 || mensagem != ''">   
        <div class="row">
            <div class="col-md-12">
                <div class="callout no-margin-bottom callout-danger" v-for="value in errors" style="margin-top: 15px;">
                    <div>{{ value }}</div>
                </div>
            </div>
        </div>
    
        <div class="row" v-if="mensagem">
            <div class="col-md-12">
                <div class="callout no-margin-bottom callout-success" style="margin-top: 15px;">
                    <div>{{ mensagem }}</div>
                </div>
            </div>
        </div>
    </div>      
    <div class="modal-footer">
      
        <button type="button" class="btn btn-danger" @click="$modal.hide('modal-confirm')" style="margin-right: 10px;">NÃO</button>
        <button type="button" class="btn btn-primary btn-sim" @click="confirma" autofocus>SIM</button>
      
    </div>
</div>

</modal>

</template>

<script>
const MODAL_WIDTH = 600
export default {
    props:['erros'],
    name: 'ModalConfirm',
    data (){
        return {
            modalWidth: MODAL_WIDTH,
            mensagem: "",
            errors:[],
            edit:false,
            titulo:'',
            id:''            
        }
    },
    watch:{
        erros(){
            this.errors = this.erros
        }
    },
    methods: {
        beforeOpen (event){
            this.limpaResponse();
            this.titulo = event.params.titulo
            this.id = event.params.id
        },
        opened()
        {
            this.$el.querySelector('.btn-sim').focus()
        },
        beforeClose()
        {
            this.titulo = '';
            this.id = '';
        },
        limpaResponse(){
          this.errors = [];
          this.mensagem = "";
        },
        confirma(){
            if( this.id != '' )
            {
                this.$emit('confirma', this.id)
            }
        },
    },
    created () {
      this.modalWidth = window.innerWidth < MODAL_WIDTH
        ? MODAL_WIDTH / 2
        : MODAL_WIDTH
    }
};
</script>