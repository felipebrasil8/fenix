<template>
<modal :name="'modal-confirm'+modalId" transition="pop-out" :width="modalWidth" height="auto" @before-close="beforeClose"  @before-open="beforeOpen"  @after-open="afterOpen">

<div class="modal-content" >
    <div class="modal-header">    
        <button type="button" class="close" @click="$modal.hide('modal-confirm'+modalId)">
            <span aria-hidden="true">&times;</span>
        </button>
    
        <h4 class="modal-title" id="modal-title">{{tituloModal}}</h4>
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
      <button type="button" class="btn btn-danger" @click="$modal.hide('modal-confirm'+modalId)" style="margin-right: 10px;">NÃO</button>
      <button type="button" class="btn btn-primary" @click="confirma(content)" autofocus v-if="evento == 'true'">SIM</button>
      <button type="button" class="btn btn-primary" @click="alterarStatus" autofocus v-else>SIM</button>
    </div>
</div>

</modal>

</template>

<script>

function atualizaPagina(pagina) {
    window.location.href = pagina;
}

const MODAL_WIDTH = 600

export default {
    props:['modalId', 'tituloModal', 'ajax', 'refresh', 'evento', 'content'],
    name: 'ModalConfirm',
    data (){
        return {
            modalWidth: MODAL_WIDTH,
            mensagem: "",
            dados_response:[],
            errors:[],
            edit:false,            
        }
    },
      
    methods: {
        alterarStatus(){
            if( this.modalId != '' && this.ajax != '' ){

                window.axios.delete(this.ajax+this.modalId)
                .then(response=>{
                 
                    this.mensagem = response.data.mensagem;

                    // Caso esse parametro esteja setado recerrega a página solicitada
                    if( this.refresh != '' )
                    {
                        setTimeout( atualizaPagina(this.refresh), 1000); 
                    }

                    return 0;
                 })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.validaBotao = false;
                    return 0; 
                });
            }
        },
        beforeClose (event) {
          this.limpaResponse();  
        },
        beforeOpen (event){
            this.limpaResponse();
        },
        afterOpen (event){
            
        },
        limpaResponse(){
          this.errors = [];
          this.mensagem = "";
        },
        atualiza () {
            this.$emit('confirma')
        },
        confirma(obj){
            var s = this
            window.axios.post( this.ajax, obj)
            .then(response=>{
                this.mensagem = response.data.mensagem;
                setTimeout(
                    function()
                    { 
                        s.atualiza();
                        s.$modal.hide('modal-confirm'+s.modalId);
                        // alert("Hello"); 
                    } 
                , 1000); 

            })
            .catch(error => {
                this.errors = error.response.data.errors;
                return 0; 
            });




        }
    
    }, 
    
    mounted(){
        
    },     
    created () {
      this.modalWidth = window.innerWidth < MODAL_WIDTH
        ? MODAL_WIDTH / 2
        : MODAL_WIDTH
    }
};
</script>