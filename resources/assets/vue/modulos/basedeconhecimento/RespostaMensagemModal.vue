<template>
    <modal :name="'resposta-mensagem-modal'+mensagem_id" transition="pop-out" :width="modalWidth" height="auto" @before-close="beforeClose"  >
        <div class="modal-content" >
            <div class="modal-body">
                <form method="post"  data-toggle="validator" role="form" class="formcadastrar">
                    <div class="box-body row">
                        
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-11 col-xs-11">
                                    <textarea class="form-control input-sm" id="resposta" name="resposta" v-model="item.resposta"  placeholder="Resposta da mensagem" required data-error="Este campo é obrigatório." style="resize: none; text-transform: uppercase;" rows="3"></textarea>  
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                   
                    <div class="modal-footer">
                        <button class="btn btn-danger" @click="$modal.hide('resposta-mensagem-modal'+mensagem_id)" type="button">Cancelar</button> 
                        <button class="btn btn-primary" @click="store" :disabled="isDisabled"  type="button">Salvar</button>
                    </div> 
                    <div class="row" v-if="mensagem">
                        <div class="col-md-12">
                            <div class="callout no-margin-bottom callout-success" style="margin-top: 15px;">
                                <div>{{ mensagem }}</div>
                            </div>
                        </div>
                    </div>   
                    <div class="row"  v-else>
                        <div class="col-md-12">
                            <div class="callout no-margin-bottom callout-danger" v-for="value in errors" style="margin-top: 15px;">
                                <div>{{ value[0] }}</div>
                            </div>
                        </div>
                    </div>   
                </form>
            </div>
        </div>
    </modal>
</template>

<script>
    const MODAL_WIDTH = 600
    export default {
        props:['mensagem_id'],
        name: 'RespostaMensagemModal',
        data (){
            return {
                alterMethod:"",
                modalWidth: MODAL_WIDTH,
                modal_titulo: '',
                mensagem: "",
                errors:[],
                item:{
                    resposta: '',
                    mensagem_id: '',
                    
                },
                edit:false,
                dados:[],
                validaBotao:false,
                selected: '',
                options: []
            }
        },
        methods: {
            store(){
                if( this.item.resposta != ''){
                   this.validaBotao = true;
                   this.limpaResponse()
                    var s = this;
                   this.item.resposta = this.item.resposta.toUpperCase();
                   this.item.mensagem_id = this.mensagem_id;
                   
                    window.axios.post( '/base-de-conhecimento/mensagens/setResposta' , this.item)
                        .then(response=>{
                            this.mensagem = response.data.mensagem
                            
                            setTimeout(function(){ 
                                s.atualizar()
                                s.$modal.hide('resposta-mensagem-modal'+s.mensagem_id)
                            }, 1000);
                            
                            return 0;
                        });

                } 
            },
            beforeClose (event) {
                this.limpaInputs();  
                this.limpaResponse();  

            },
            limpaInputs () {
                  this.item.resposta = '';
            },
            limpaResponse(){
                  this.errors = [];
                  this.mensagem = "";
            },
            getUrl(){
                return '/base-de-conhecimento/publicacao/'+this.publicacao_id;
            },
            atualizar (ev) {
                // ev.preventDefault()
                this.$emit('atualizar')
      
            },
                  
              
        }, 
        computed:{
            isDisabled(){
                if( this.item.resposta != '' && !this.validaBotao ) {
                    return false;
                }
                return true; 
            }
        },
        mounted(){
            this.limpaInputs()
           
        },     
        created () {
          this.modalWidth = window.innerWidth < MODAL_WIDTH
            ? MODAL_WIDTH / 2
            : MODAL_WIDTH
        }
    };
</script>