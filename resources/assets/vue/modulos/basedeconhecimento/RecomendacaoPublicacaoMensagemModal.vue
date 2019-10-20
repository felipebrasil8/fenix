<template>
<modal :name="'modal-recomendacao-publicacao-mensagem'" transition="pop-out" :width="modalWidth" height="auto" :clickToClose="false">

    <div class="modal-content" >
        <div class="modal-header">    
            <button type="button" class="close" @click="recomendacaoVisualizada">
                <span aria-hidden="true">&times;</span>
            </button>    
            <h4 class="modal-title" style="color: #333">Nova recomendação!!!</h4>        
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="box-body">
                        <div class="form-group">
                            <label>{{recomendacao.usuario}} te recomendou esta matéria!!!</label> 
                        </div>
                        <div class="form-group">                   
                            {{recomendacao.mensagem}}
                        </div>
                    </div>
                </div>
            </div>
        </div>        
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="recomendacaoVisualizada" autofocus>Fechar</button>
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
    props:['recomendacao'],
    name: 'ModalRecomendacaoPublicacaoMensagem',
    data (){
        return {
            modalWidth: MODAL_WIDTH,
            mensagem: "",
            dados_response:[],
            errors:[],
        }
    },
    methods: {       
        recomendacaoVisualizada(){
            this.$emit('recomendacaoVisualizada', this.recomendacao);
        }        
    },
    created () {      
      this.modalWidth = window.innerWidth < MODAL_WIDTH
        ? MODAL_WIDTH / 2
        : MODAL_WIDTH
    },
};
</script>