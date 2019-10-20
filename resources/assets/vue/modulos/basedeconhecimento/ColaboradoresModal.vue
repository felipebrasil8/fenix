<template>
    <modal name="colaboradores-modal" transition="pop-out" :width="modalWidth" height="auto" @before-close="beforeClose"  @before-open="beforeOpen" :clickToClose="false">
        <div class="modal-content" >
            <div class="modal-header">    
                <button type="button" class="close" @click="$modal.hide('colaboradores-modal')" ><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="modal-title">{{ modal_titulo }}</h3>
            </div>
            <div class="modal-body">
                <form method="post"  data-toggle="validator" role="form" class="formcadastrar">
                    <div class="box-body row">
                        <div class="form-group">
                            <label >Colaboradores:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-11">
                                    <select multiple="" class="form-control input-sm" v-model="item.colaboradores" style="width: 100%;" size="10">
                                        <option value=""></option>                           
                                        <option v-for="item in funcionariosObj" :value="item.id">{{item.nome}}</option>                           
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- second row -->
                    <div class="modal-footer">
                        <button class="btn btn-danger" @click="$modal.hide('colaboradores-modal')" type="button">Cancelar</button> 
                        <button class="btn btn-primary" @click="update" :disabled="isDisabled" type="button" >Salvar</button>
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
        props:['publicacao_id', 'funcionarios', 'colaboradores', 'request'],
        name: 'ColaboradoresModal',
        data (){
            return {
                modalWidth: MODAL_WIDTH,
                modal_titulo: 'Editar Colaboradores',
                mensagem: "",
                errors:[],
                item:{
                    colaboradores: [],
                },
                colaboradoresObj: {},
                funcionariosObj: {},
                validaBotao:false,
            }
        },
        methods: {
            update(){
                this.validaBotao = true;
                this.limpaResponse()
                if( this.item.colaboradores.length > 0 ){
                    this.item.publicacao_id = this.publicacao_id;
                    var url = this.request;
                    if( !(url == 'rascunho' || url == 'edit') )
                    {
                        url = 'edit';
                    }
                    this.item.rascunho = (url == 'rascunho' ? true : false);
                    window.axios.post('/base-de-conhecimento/colaboradores', this.item)
                    .then(response=>{
                        this.mensagem = response.data.mensagem;
                        setTimeout(function(){ 
                          window.location.href = "/base-de-conhecimento/publicacoes/"+response.data.id+"/"+url;
                        }, 1000, url);
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
        }, 
        computed:{
            isDisabled(){
                if(this.item.colaboradores.length > 0 && !this.validaBotao){
                    return false;
                }
                    return true;
            }
        },
        mounted(){
            this.funcionariosObj = JSON.parse(this.funcionarios);
            this.colaboradoresObj = JSON.parse(this.colaboradores);
            
            // Adiciona o id dos colaboradores no objeto item.colaboradores que vai ser enviado no ajax
            this.item.colaboradores = this.colaboradoresObj.map(function (item) {
                return item.funcionario_id;
            });
        },     
        created () {
          this.modalWidth = window.innerWidth < MODAL_WIDTH
            ? MODAL_WIDTH / 2
            : MODAL_WIDTH
        }
    };
</script>