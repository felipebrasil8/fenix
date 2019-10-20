<template>

<modal :name="'rascunho-modal-confirm-'+tipo" transition="pop-out" :width="modalWidth" height="auto" @before-close="beforeClose"  @before-open="beforeOpen" :clickToClose="true">
    <div class="modal-content" >
        <div class="modal-header">    
            <button type="button" class="close" @click="$modal.hide('rascunho-modal-confirm-'+tipo)">
                <span aria-hidden="true">&times;</span>
            </button>
        
            <h4 class="modal-title" id="modal-title" v-if="tipo == 'confirmRascunho'">
                <div style="font-size: 22px;"><b>Atenção:</b></div>
                <div>Todos os dados atuais da publicação serão excluídos e substituídos pelo rascunho. </div>
                <div>Esta operação não poderá ser desfeita, deseja continuar?</div>
            </h4>

            <h4 class="modal-title" id="modal-title" v-if="tipo == 'deleteRascunho'">
                <div style="font-size: 22px;"><b>Atenção:</b></div>
                <div>Todos os dados de rascunho serão excluídos.</div>
                <div>Esta operação não poderá ser desfeita, deseja continuar?</div>
            </h4>
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
          <button type="button" class="btn btn-danger" @click="$modal.hide('rascunho-modal-confirm-'+tipo)" style="margin-right: 10px;">NÃO</button>
          <button type="button" class="btn btn-primary" @click="setRascunho" autofocus>SIM</button>
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
    props:['publicacaoId', 'tipo'],
    name: 'RascunhoModalConfirm',
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
        setRascunho(){
            if( this.publicacaoId != '' && this.tipo != '' )
            {
                window.axios.delete( '/base-de-conhecimento/conteudo/'+this.publicacaoId+'/'+this.tipo )
                .then(response=>{
 
                    this.mensagem = response.data.mensagem;
                    setTimeout( atualizaPagina('/base-de-conhecimento/publicacoes/'+this.publicacaoId), 1000);
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
    }, 
    // computed:{
        
    // },
    mounted(){
        
    },     
    created () {
      this.modalWidth = window.innerWidth < MODAL_WIDTH
        ? MODAL_WIDTH / 2
        : MODAL_WIDTH
    }
};
</script>