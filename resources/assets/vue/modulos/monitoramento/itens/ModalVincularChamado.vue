<template>
    <modal name="modal-vincular-chamado" transition="pop-out" :width="modalWidth" height="600px" @before-open="beforeOpen" :clickToClose="false">
        <div class="modal-content" >
            <form target="_blank" method="post">
                <div class="modal-header">    
                  <button type="button" class="close" @click="$modal.hide('modal-vincular-chamado')" ><span aria-hidden="true">&times;</span></button>
                  <h3 class="modal-title" id="modal-title">{{ title }}</h3>
                </div>
                <div class="modal-body box"  v-if="this.cadastrar" style="margin-bottom: 0px; border-radius: 0px; background:none; border-top: none;">
                    <div class="overlay" v-if="carregandoDadosModalVisualizarChamado">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>                

                    <div class="row">
                      
                        <div class="box-body" style="padding-bottom: 0;">
                            
                            <div class="col-sm-6 ">
                                <div class="form-group">
                                    <label >Cliente:</label>
                                    <span>{{ cliente }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label >Projeto:</label>
                                    <span>{{ projeto }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label >Produto:</label>
                                    <span>{{ produto }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label >Tipo:</label>
                                    <span>{{ tipo }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label >Chamado:</label>
                                    <span> <a :href="'https://crm.novaxtelecom.com.br/suporte/fluxo/criar_chamado.php?id='+projeto" target="_blank"> Abrir chamado no CRM </a> </span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="box-body">
                                <div class="form-group">
                                    <label >Chamado :</label>
                                    <div class="overlay" v-if="carregandoDadosModalVisualizarChamado" style="height: 40px;"></div>   
                                    <div class="row" v-else>
                                        <div v-if="chamados.length > 0">
                                            <div class="col-md-11 col-xs-11">
                                                <multiselect
                                                    v-model="pesquisa.chamado" 
                                                    :options="chamados"
                                                    :multiple="true" 
                                                    :hide-selected="true"
                                                    select-label=""
                                                    :custom-label="nameWithLang"
                                                    :showNoResults="false"
                                                    track-by="numero"
                                                    placeholder="Chamados"
                                                    :max="1"
                                                    :max-height="250"
                                                >
                                                <template slot="maxElements">Maximo de 1 chamado para cada alerta. Remova o chamado antes de adicionar.</template>
                                                <template slot="noOptions">A lista está vazia.</template>
                                                </multiselect>
                                            </div>
                                            <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                                <i class="fa fa-asterisk"></i>
                                            </div>
                                        </div>
                                        <div class="col-md-11 col-xs-11" v-else>
                                            <div class="form-group">
                                            <span>Este projeto não tem nenhum chamado associado.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                    </div><!-- .row -->
                </div><!-- .modal-body -->
               
                <div class="modal-body box" v-else style="margin-bottom: 0px; border-radius: 0px; background:none; border-top: none;">
                    <div class="overlay" v-if="carregandoDadosModalVisualizarChamado">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                    <div class="row">
                        <div class="col-md-11 col-xs-11">
                            <div class="box-body" style="padding-bottom: 0;">
                                <div class="col-sm-6 ">
                                    <div class="">
                                        <div class="form-group">
                                            <label >Cliente:</label>
                                            <span>{{ cliente }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="">
                                        <div class="form-group">
                                            <label >Projeto:</label>
                                            <span>{{ projeto }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="">
                                        <div class="form-group">
                                            <label >Produto:</label>
                                            <span>{{ produto }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="">
                                        <div class="form-group">
                                            <label >Tipo:</label>
                                            <span>{{ tipo }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-11 col-xs-11">
                            <div class="box-body" style="padding-bottom: 0;">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Data de vínculo:</label>
                                        <span> {{ data_insert }} </span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Usuario do vínculo:</label>
                                        <span> {{ usuario  || '-' }} </span>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Chamado:</label>
                                        <span> <a :href="'https://crm.novaxtelecom.com.br/suporte/fluxo/visualizar.php?id='+chamado" target="_blank"> {{ chamado }} </a> </span>
                                    </div>
                                </div>
                                <div v-if="!infoChamadoFinalizado">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Título:</label>
                                            <span v-if="carregandoDadosModalVisualizarChamado"> - </span>
                                            <span v-else> {{ infoChamado.titulo }} </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Status:</label>
                                            <span v-if="carregandoDadosModalVisualizarChamado"> - </span>
                                            <span v-else> {{ infoChamado.status }} </span>
                                        </div>
                                    </div> 
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Responsável:</label>
                                            <span v-if="carregandoDadosModalVisualizarChamado"> - </span>
                                            <span v-else> {{ infoChamado.responsavel || '-' }} </span>
                                        </div>
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <span>O chamado {{ chamado }} foi finalizado, para vincular um novo chamado esse deve ser removido.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button v-if="!this.cadastrar && can.monitoramento_cadastrar" class="btn btn-warning pull-left" @click="$modal.show('modal-confirm', {titulo: 'Deseja remover o chamado vinculado?'})" type="button" :disabled="bloqueiaBotao"><i class="fa fa-trash" aria-hidden="true"></i> Remover</button>
                    <button class="btn btn-danger" @click="$modal.hide('modal-vincular-chamado')" type="button">Cancelar</button> 
                    <button v-if="this.cadastrar && can.monitoramento_cadastrar" class="btn btn-primary" :disabled="isDisabled" @click.prevent="store()" type="submit" >Salvar</button>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <div class="callout no-margin-bottom callout-danger" v-for="value in errors" style="margin-top: 15px;">
                                <div>{{ value[0] }}</div>
                            </div>
                        </div>
                    </div>
                </div>   
                <div class="row" v-if="mensagem">
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <div class="callout no-margin-bottom callout-success" style="margin-top: 15px;">
                                <div>{{ mensagem }}</div>
                            </div>
                        </div>
                    </div>
                </div>
     
            </form>                
            <vc-modal-confirme @confirma="removerParada"></vc-modal-confirme>
        </div><!-- .modal-content -->
    </modal>
</template>

<script>
    import 'eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css';    
    import { filtroBus } from './../../util/filtroDataTable.js';
    import carregandoStore from './../../util/carregandoStore.js';
    const MODAL_WIDTH = 700
    export default {
        props:['can'],
        name: 'VcVincularChamadoModal',
        data (){
            return {                
                pesquisa: {
                    chamado:'',
                    id_servidor:'',
                    id_item:'',
                },
                title: 'Vincular chamado',
                validaBotao:false, 
                bloqueiaBotao: false,
                cadastrar: false,
                cliente: '',
                chamadoVinculado: [],
                chamados:[],
                chamado: '',
                usuario: '',
                projeto: '',
                produto: '',
                titulo: '',
                data_insert: '',
                usuario_insert: '',
                chamadoAtual: '',
                tipo: '',
                mensagem: "",
                errors:[],
                infoChamado:[],
                carregandoDadosModalVisualizarChamado: false,
                infoChamadoFinalizado: true,
            }
        },
        methods: {            
            

            beforeOpen (event)
            {
                this.limpaVeriaveis()
                
                this.cliente = event.params.item.cliente
                this.projeto = event.params.item.id_projeto
                this.produto = event.params.item.grupo
                this.tipo = event.params.item.tipo


                this.pesquisa.id_servidor = event.params.item.id
                this.projeto = event.params.item.id_projeto
                
                if ( event.params.alertas == false )
                {
                    // SERVIDOR
                    this.pesquisa.id_item = false
                                        
                    if ( event.params.item.itens[0].chamado_vinculado )
                    {
                        this.chamado = event.params.item.itens[0].chamado_vinculado
                        this.data_insert = event.params.item.itens[0].chamado_vinculado_at
                        this.usuario = event.params.item.itens[0].usuario_inclusao_chamado_id 
                        this.title = 'Chamado vinculado'
                        
                        this.pesquisa.chamado =  { 'numero': this.chamado  }
                        this.getInfoChamado()
                    }
                    else
                    {
                        this.cadastrar = true
                    }

                }
                else
                {
                    // ITEM
                    this.pesquisa.id_item = event.params.alertas.id
                    
                    if ( event.params.alertas.chamado_vinculado )
                    {

                        this.chamado = event.params.alertas.chamado_vinculado
                        this.data_insert = event.params.alertas.chamado_vinculado_at
                        this.usuario = event.params.alertas.usuario_inclusao_chamado_id 
                        this.title = 'Chamado vinculado'
                        this.pesquisa.chamado =  { 'numero': this.chamado   }
                        this.getInfoChamado()
                        
                        
                    }
                    else
                    {
                        this.cadastrar = true
                    
                    }

                
                }    


                if ( this.cadastrar )
                {
                    this.getChamadas()
                }
                // this.chamadoVinculado = event.params.alertas.chamado_vinculdao
                
                // if ( this.chamadoVinculado  )
                // {
                
                //     this.chamadoVinculado = event.params.alertas.chamado_vinculado

                // }
                // else
                // {

                // }
            }, 
            getChamadas()
            {
                this.carregandoDadosModalVisualizarChamado = true;
                window.axios.get( '/monitoramento/itens/chamados-crm/'+this.projeto )
                    .then(response=>{
                        this.chamados = response.data.chamados;
                        this.carregandoDadosModalVisualizarChamado = false;
                    }).catch(error => {
                        
                    });

            },
            getInfoChamado()
            {
                this.carregandoDadosModalVisualizarChamado = true;
                window.axios.get( '/monitoramento/itens/chamados-crm-info/'+this.chamado )
                .then(response=>{
                    if( response.data.chamado ){
                        this.infoChamado = response.data.chamado;
                        this.infoChamadoFinalizado = false;
                    }
                    else
                    {
                        this.infoChamadoFinalizado = true;
                    }
                    this.carregandoDadosModalVisualizarChamado = false;
                }).catch(error => {
                    
                });
            },
            nameWithLang ({ numero, titulo }) {
                return `${numero} — ${titulo}`
            },
            limpaVeriaveis(){
                this.mensagem = ''

                this.errors = []
                this.chamados = []

                this.cadastrar = false
                this.validaBotao = false
                this.bloqueiaBotao = false

                this.chamado = ''
                this.usuario = ''

                this.pesquisa.chamado = ''
                this.pesquisa.id_servidor = ''
                this.pesquisa.id_item = ''
            },
            store()
            {
                window.axios.post( '/monitoramento/itens/chamados', this.pesquisa )
                    .then(response=>{
                        this.mensagem = response.data.mensagem;
                    }).catch(error => {
                        this.errors = error.response.data.errors;
                        this.validaBotao = false;
                    });

                    window.setTimeout(() => {
                        this.$modal.hide('modal-vincular-chamado');
                         filtroBus.$emit('editTable')
                    }, 2000);
            },
            removerParada()
            {

                this.validaBotao = true
                window.axios.delete( '/monitoramento/itens/chamados/'+this.pesquisa.id_item , {'data': this.pesquisa})
                        .then(response=>{
                            this.mensagem = response.data.mensagem;

                            this.$modal.hide('modal-confirm');
                        }).catch(error => {
                            this.errors = error.response.data.errors;
                        });

                        window.setTimeout(() => {
                            this.$modal.hide('modal-vincular-chamado');
                             filtroBus.$emit('editTable')
                        }, 2000);
            },
            edit(){
                // this.cadastrar = true;

                // this.setMinDateConfig1()
                // this.pesquisa.observacao = this.paradaProgramada.observacao
                // this.pesquisa.de = this.paradaProgramada.dt_inicio
                // this.pesquisa.ate = this.paradaProgramada.dt_fim
            },
            nl2br(text)
            {
              if (text && text !== null) {
                let i, s = '', lines = text.split('\n'), l = lines.length;
                 for (i = 0; i < l; ++i) {
                  s += lines[i];
                  (i !== l - 1) && (s += '<br/>');
                }
                 return s;
              }
              return text;
            },
        }, 
        computed:{
            isDisabled(){
                if( this.pesquisa.chamado != '' && this.pesquisa.id_servidor != '' && !this.validaBotao){
                    return false; 
                }
                 return true; 
            }
        },
        mounted(){
            // if(this.titulo){
                // this.modal_titulo = this.titulo 
            // }
        },
        created () {
          this.modalWidth = window.innerWidth < MODAL_WIDTH
            ? MODAL_WIDTH / 2
            : MODAL_WIDTH
        }
    }; 
</script>
