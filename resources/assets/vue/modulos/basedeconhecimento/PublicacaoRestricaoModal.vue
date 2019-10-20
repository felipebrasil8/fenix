<template>
    <modal name="publicacao-restricao-modal" transition="pop-out" :width="modalWidth" height="auto" @before-close="beforeClose"  @before-open="beforeOpen" :clickToClose="false">
        <div class="modal-content" >
            <div class="modal-header">    
                <button type="button" class="close" @click="$modal.hide('publicacao-restricao-modal')" ><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="modal-title">{{ modal_titulo }}</h3>
            </div>
            <div class="modal-body">
                <form method="post"  data-toggle="validator" role="form" class="formcadastrar">
                    <div class="box-body row">
                        <div class="form-group">
                            <label >Restringir acesso:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-11">
                                    <label>
                                        <input type="radio" v-model="item.restricaoAcesso" value="true"> Sim
                                    </label>
                                    &nbsp;
                                    <label>
                                        <input type="radio" v-model="item.restricaoAcesso" value="false"> Não
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" v-if="exibirCargo">
                            <label >Liberar para cargos:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-11">
                                    <select multiple="" class="form-control input-sm" v-model="item.cargos" style="width: 100%;" size="10">
                                        <option v-for="item in cargosObj" :value="item.id">{{item.nome}}</option>                           
                                    </select>
                                </div>
                                <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- second row -->
                    <div class="modal-footer">
                        <button class="btn btn-danger" @click="$modal.hide('publicacao-restricao-modal')" type="button">Cancelar</button> 
                        <button class="btn btn-primary" @click="update" :disabled="isDisabled" type="button">Salvar</button>
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
    const MODAL_WIDTH = 600
    export default {
        props:['publicacao_id', 'cargos', 'publicacao_cargos', 'restricao_acesso'],
        name: 'PublicacaoRestricaoModal',
        data (){
            return {
                modalWidth: MODAL_WIDTH,
                modal_titulo: 'Editar restrições de acesso',
                mensagem: "",
                errors:[],
                item:{
                    cargos: [],
                    restricaoAcesso: ""
                },
                cargosObj: {},
                validaBotao:false,
                publicacaoCargoObj: {},
            }
        },
        methods: {
            update(){
                this.validaBotao = true;
                this.limpaResponse()
                if( this.exibir() == false || (this.exibir() && this.item.cargos.length > 0 ) ){
                    this.item.publicacao_id = this.publicacao_id;
                    window.axios.post('/base-de-conhecimento/restricao', this.item)
                    .then(response=>{
                        this.mensagem = response.data.mensagem;
                        setTimeout(function(){ 
                            window.location.href = "/base-de-conhecimento/publicacoes/"+response.data.id+"/edit";
                        }, 1000);
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
                this.limpaResponse()           
            },
            limpaResponse(){
                this.errors = [];
                this.mensagem = "";
            },
            exibir()
            {
                if(this.item.restricaoAcesso == 1 || this.item.restricaoAcesso == true || this.item.restricaoAcesso == "true"){
                    return true;
                }
                
                return false;
            }
        }, 
        computed:{
            isDisabled(){
                if( this.exibir() == false && !this.validaBotao){
                    return false;
                }
                else if( this.exibir() == true && this.item.cargos.length > 0 && !this.validaBotao){
                    return false;
                }
                
                return true;
            },
            exibirCargo(){
                return this.exibir();
            }
        },
        mounted(){
            this.cargosObj = JSON.parse(this.cargos);
            this.publicacaoCargoObj = JSON.parse(this.publicacao_cargos);

            if( this.restricao_acesso == true || this.restricao_acesso == 1 )
            {
                this.item.restricaoAcesso = true;
            }
            else
            {
                this.item.restricaoAcesso = false;   
            }
           
            // Adiciona
            this.item.cargos = this.publicacaoCargoObj.map(function (item) {
                return item.cargo_id;
            });
        },     
        created () {
          this.modalWidth = window.innerWidth < MODAL_WIDTH
            ? MODAL_WIDTH / 2
            : MODAL_WIDTH
        }
    };
</script>