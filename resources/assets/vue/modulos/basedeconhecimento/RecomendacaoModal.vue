<template>
    <modal :name="'modal-recomendacao'+publicacaoId" transition="pop-out" :width="modalWidth" height="auto" @before-open="beforeOpen">

        <div class="modal-content">
            <div class="modal-header">    
                <button type="button" class="close" @click="$modal.hide('modal-recomendacao'+publicacaoId)">
                    <span aria-hidden="true">&times;</span>
                </button>    
                
                <h4 class="modal-title" style="color: #333">{{ titulo }}</h4>        
            </div>
            <div class="modal-body">           
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group recomendacao">
                                <label>Funcionário:</label>
                                <div class="row">
                                    <div class="col-md-11 col-xs-11">
                                        <vc-auto-complete :options="options" @input="inputFuncionario" @select="onOptionSelect" @limpaInputs="limpaInputs" :limpaInput="limpaFuncionarios"></vc-auto-complete>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="col-md-1 col-xs-1 recomendacao" style="padding-left: 0;padding-top: 9px;">
                                        <i class="fa fa-asterisk"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group recomendacao">
                                <label>Mensagem:</label>
                                <div class="row">
                                    <div class="col-md-11 col-xs-11">
                                        <input type="text" class="form-control input-sm" maxlength="100" name="mensagem" v-model="form.mensagem"  style="text-transform: uppercase;">                                        
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                        <i class="fa fa-asterisk"></i>
                                    </div>
                                </div>
                            </div>
                        </div>                
                    </div>

                    <div class="row" style="height: 270px;" v-if="recomendados.length > 0">
                        <div class="col-md-12 col-sm-12" style="height: 270px; overflow-y: scroll">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Data e hora</th>                                        
                                        <th>Usuário</th>
                                        <th>Mensagem</th>                                    
                                        <th class="text-center">Visualizada</th>
                                    </tr>
                                </thead>                                                                    
                                <tbody>
                                    <tr v-for="rec in recomendados">
                                        <td>{{ rec.data }}</td>                                        
                                        <td>{{ rec.nome }}</td>
                                        <td>                                                
                                            <div class="truncate" style="width:200px; white-space: nowrap !important">
                                                <span  data-toggle="tooltip" :title="rec.mensagem">{{rec.mensagem}}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">                              
                                            <div v-if="rec.visualizada == true">
                                                <i class="fa fa-check-square-o fa fa-check-square-o"></i>
                                            </div>
                                            <div v-else>
                                                <i class="fa fa-check-square-o fa fa-square-o"></i>
                                            </div>
                                        </td>                                    
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">                
                    <button type="button" class="btn btn-danger" @click="$modal.hide('modal-recomendacao'+publicacaoId)" style="margin-right: 10px;">Cancelar</button>
                    <button class="btn btn-primary" @click="enviaForm" :disabled="isDisabled" type="button" autofocus>Recomendar</button>
                    <input type="hidden" v-model="funcionario">
                </div>

                <div class="row" v-if="errors">
                    <div class="col-md-12">
                        <div class="callout no-margin-bottom callout-danger" v-for="value in errors" style="margin-top: 15px;" v-if="errors">                            
                            <div v-html="value[0]"></div>
                        </div>
                    </div>
                </div>
                
                <div class="row" v-if="success">
                    <div class="col-md-12">
                        <div class="callout no-margin-bottom callout-success" style="margin-top: 15px;">
                            <div>{{ mensagem }}</div>                    
                        </div>
                    </div>
                </div>        
            </div>
        </div>

    </modal>
</template>

<script>

function atualizaPagina(pagina) {
    window.location.href = pagina;
}

const MODAL_WIDTH = 800

export default {
    props:['modalId', 'titulo', 'ajax', 'refresh', 'publicacaoId'],

    name: 'ModalRecomendacao',
    data (){
        return {
            modalWidth: MODAL_WIDTH,
            mensagem: "",
            dados_response:[],
            errors:[],
            edit:false,
            options: [],
            form: {},
            success: false,
            errors: false,
            funcionario: '',
            botaoDesabilita: true,
            limpaFuncionarios: '',
            recomendados: {}
        }
    },
    methods: {        
        onOptionSelect(option) {

            this.form.funcionario_recomendado_id = option.id;
            this.form.publicacao_id = this.publicacaoId;
            this.form.funcionario = option.nome;

        },
        enviaForm(){

            this.escondeMensagem()
            window.axios.post('/base-de-conhecimento/publicacao/recomendacao', {
                recomendacao: this.form
            })
            .then(response=>{
                this.success = true
                this.mensagem = response.data.mensagem                
                this.limpaFuncionarios = 'limpa';
                this.carregaRecomendados()
            })
            .catch(error => {    
                this.errors = error.response.data.errors;
                this.validaBotao = false;
                return 0; 
            });

        },
        firstLoad(){

            var value = " "
            window.axios.get('/rh/funcionario/publicacao/'+this.publicacaoId+'/'+value+'/')
            .then(response=>{
                this.options = response.data                
            });
        },
        escondeMensagem(){
            this.success = false,
            this.errors = false
        },
        beforeOpen(){
            this.form = {}
            this.errors = false;
            this.success = false;
            this.firstLoad()
            this.carregaRecomendados()
        },
        inputFuncionario(e){            
            this.funcionario = e
            this.form.funcionario = e;
        },
        limpaInputs(e){
            this.limpaFuncionarios= ''
            this.form.mensagem = ''            
        },
        carregaRecomendados( $publicacaoId ){

            window.axios.get('/base-de-conhecimento/publicacao/'+ this.publicacaoId +'/recomendados')
            .then(response=>{

                this.recomendados = response.data                
            });

        }
    },    
    computed:{
        isDisabled(){            
            if( this.funcionario && this.form.mensagem ){

                return false
            }
            return true
        }
    },
    created () {      

        this.modalWidth = window.innerWidth < MODAL_WIDTH
        ? MODAL_WIDTH / 2 : MODAL_WIDTH
        this.firstLoad()
    },
};
</script>
<style>
    .recomendacao label, .recomendacao i{

        color: #333;
    }
</style>
