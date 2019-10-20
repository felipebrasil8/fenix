<template>
    <modal name="publicacao-datas-modal" transition="pop-out" :width="modalWidth" height="800px" @before-close="beforeClose"  @before-open="beforeOpen" :clickToClose="false">
        <div class="modal-content" >
            <div class="modal-header">    
              <button type="button" class="close" @click="$modal.hide('publicacao-datas-modal')" ><span aria-hidden="true">&times;</span></button>
              <h3 class="modal-title" id="modal-title">{{ modal_titulo }}</h3>
            </div>
            <div class="modal-body">
                <form method="post"  data-toggle="validator" role="form" class="formcadastrar">
              
                    <div class="box-body row">
                        <div class="form-group">
                            <label >Data de publicação:</label>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="input-group date">
                                        <date-picker v-model="dt_publicacao" name="dt_publicacao" :config="config" id="dt_publicacao" readonly></date-picker>
                                        <label class="input-group-addon" for="dt_publicacao">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

         
                    <div class="box-body row">
                        <div class="form-group">
                            <label >Data de atualização:</label>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="input-group date">
                                        <date-picker v-model="dt_ultima_atualizacao" name="dt_ultima_atualizacao" :config="config" id="dt_ultima_atualizacao" readonly></date-picker>
                                        <label class="input-group-addon" for="dt_ultima_atualizacao">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </label>
                                    </div>        
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-body row">
                        <div class="form-group">
                            <label >Data de revisão:</label>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="input-group date">
                                        <date-picker v-model="dt_revisao" name="dt_revisao" :config="config" id="dt_revisao" readonly></date-picker>
                                        <label class="input-group-addon" for="dt_revisao">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </label>
                                    </div>        
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-body row">
                        <div class="form-group">
                            <label >Data de desativação:</label>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="input-group date" style="positon:absolute">
                                        <date-picker v-model="dt_desativacao" name="dt_desativacao" :config="config" id="dt_desativacao" readonly></date-picker>
                                        <label class="input-group-addon" for="dt_desativacao">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>  
                    
                    <div class="box-body row">
                        <div class="form-group">
                            <label >Observação:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-11">
                                    <textarea rows="4" class="form-control input-sm" v-model="observacao" style="text-transform: uppercase; resize: none;"></textarea>
                                </div>
                                <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-danger" @click="$modal.hide('publicacao-datas-modal')" type="button">Cancelar</button> 
                        <button class="btn btn-primary" @click="update" :disabled="isDisabled" type="button">Editar</button>
                    </div> 
                    <div class="row">
                        <div class="col-md-12">
                            <div class="callout no-margin-bottom callout-danger" v-for="value in errors" style="margin-top: 15px;">
                                <div>{{ value[0] }}</div>
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
                </form>
            </div>
        </div>
    </modal>
</template>

<script>
    import 'eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css';
    const MODAL_WIDTH = 600
    export default {
        props:['publicacao_id', 'datas'],
        name: 'PublicacaoDatasModal',
        data (){
            return {
                alterMethod:"",
                modalWidth: MODAL_WIDTH,
                modal_titulo: 'Editar Publicação',
                mensagem: "",
                errors:[],
                edit:false,
                validaBotao:false,
                options:[],
                item:{
                    dt_publicacao: '',
                    dt_ultima_atualizacao:  '',
                    dt_desativacao:  '',
                    observacao: '',
                    dt_revisao:  ''
                },
                dt_publicacao: null,
                dt_ultima_atualizacao: null,
                dt_desativacao:  null,
                dt_revisao:  null,
                observacao: '',
                // Configuração do campo de data
                config: {
                    format: 'DD/MM/YYYY',
                    useCurrent: false,
                    showClear: true,
                    showClose: true,
                    locale: 'pt-br',
                    ignoreReadonly: true,
                },
                configTop: {
                    format: 'DD/MM/YYYY',
                    useCurrent: false,
                    showClear: true,
                    showClose: true,
                    locale: 'pt-br',
                    ignoreReadonly: true,
                    widgetPositioning: {
                        horizontal: 'auto',
                        vertical: 'top'
                     }
                }
            }
        },
        methods: {
            update(){
                this.validaBotao = true;
                this.limpaResponse()
                
                this.item.dt_publicacao = this.dt_publicacao
                this.item.dt_ultima_atualizacao = this.dt_ultima_atualizacao
                this.item.dt_desativacao = this.dt_desativacao
                this.item.observacao = this.observacao
                this.item.dt_revisao = this.dt_revisao

                window.axios.post( '/base-de-conhecimento/publicacao/'+this.publicacao_id+'/datas', this.item)
                    .then(response=>{
                        this.mensagem = response.data.mensagem;
                    
                        setTimeout(function(){ 
                           window.location.href = "/base-de-conhecimento/publicacoes/"+response.data.publicacao_id;
                        }, 1000);
                        return 0;
                    })
                    .catch(error => {
                        this.validaBotao = false;
                        this.errors = error.response.data.errors;
                        return 0; 
                    });
            },
            beforeClose (event) {
                this.limpaInputs();  
                this.limpaResponse();  
            },
            beforeOpen (event){
                this.options = JSON.parse(this.datas)
                
                if(this.options.dt_publicacao != null){    
                    this.dt_publicacao = new Date(this.options.dt_publicacao.split("/").reverse().join("-")+' 00:00:00')
                }
                if(this.options.dt_ultima_atualizacao != null){
                    this.dt_ultima_atualizacao = new Date(this.options.dt_ultima_atualizacao.split("/").reverse().join("-")+' 00:00:00')
                }
                if(this.options.dt_desativacao != null){    
                    this.dt_desativacao = new Date(this.options.dt_desativacao.split("/").reverse().join("-")+' 00:00:00')
                }
                if(this.options.dt_revisao != null){
                    this.dt_revisao = new Date(this.options.dt_revisao.split("/").reverse().join("-")+' 00:00:00')
                }
                
            },
            limpaInputs () {
                this.observacao = "";
            },
            limpaResponse(){
                this.errors = [];
                this.mensagem = "";
            },
          
            
        }, 
        computed:{
            isDisabled(){
                if(this.observacao.length > 0 && !this.validaBotao){                
                    return false;
                }
                    return true;
            }
        },
        created () {
          this.modalWidth = window.innerWidth < MODAL_WIDTH
            ? MODAL_WIDTH / 2
            : MODAL_WIDTH
        }
    };

 
</script>
