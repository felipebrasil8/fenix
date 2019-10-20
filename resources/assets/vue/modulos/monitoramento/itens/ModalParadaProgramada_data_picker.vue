<template>
    <modal name="modal-parada-programada" transition="pop-out" :width="modalWidth" height="600px" @before-open="beforeOpen" :clickToClose="false">
        <div class="modal-content" >
            <form target="_blank" method="post">
                <div class="modal-header">    
                  <button type="button" class="close" @click="$modal.hide('modal-parada-programada')" ><span aria-hidden="true">&times;</span></button>
                  <h3 class="modal-title" id="modal-title">{{ title }}</h3>
                </div>
                
                <div class="modal-body" v-if="this.cadastrar">                
                    <div class="row">
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
                        </div>
                        <div class="row">
                            <div class="col-md-11 col-xs-11">
                                
                                <div class="col-sm-12">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label >Parada de:</label>
                                            <div class="">                                       
                                                <div class="">
                                                    <select name="parada" v-model="parada" class="form-control input-sm" >
                                                        <option value="10">10 minutos</option>
                                                        <option value="30" selected>30 minutos</option>
                                                        <option value="1">1 horas</option>
                                                        <option value="2">2 horas</option>
                                                        <option value="4">4 horas</option>
                                                        <option value="8">8 horas</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                              
                                </div>


                                <!-- <div class="col-sm-6">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label >De:</label>
                                            <div class="">                                       
                                                <div class="input-group date">
                                                    <date-picker v-model="pesquisa.de" name="de" :config="config1" @dp-change="setMaxDate" id="pesquisa_de" readonly></date-picker>
                                                    <label class="input-group-addon" for="pesquisa_de">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                              
                                </div> -->
                                <!-- < div class="col-sm-6">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label >Até:</label>
                                            <div class="">                                       
                                                <div class="input-group date">
                                                    <date-picker v-model="pesquisa.ate" name="ate" :config="config2"  @dp-change="setMinDate" id="pesquisa_ate" readonly></date-picker>
                                                    <label class="input-group-addon" for="pesquisa_ate">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                    </label>
                                                </div>        
                                            </div>
                                        </div>
                                    </div>                              
                                </div> -->
                            </div>
                        </div>
                        
                        <div class="col-sm-12">
                            <div class="box-body">
                                <div class="form-group">
                                    <label >Observação:</label>
                                    <div class="row">
                                        <div class="col-md-11 col-xs-11">
                                            <textarea rows="4" class="form-control input-sm" v-model="pesquisa.observacao" style="text-transform: uppercase; resize: none;"></textarea>
                                        </div>
                                        <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                            <i class="fa fa-asterisk"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div><!-- .row -->
                </div><!-- .modal-body -->
               
                <div class="modal-body" v-else >     
                    <div class="row box-body">
                        <div class="col-sm-6">
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
                        <div class="col-sm-12">
                            <div class="">
                                <div class="form-group">
                                    <label >Status:</label>
                                    <span>{{ status }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="">
                                <div class="form-group">
                                    <label >De:</label>
                                    <span>{{ paradaProgramada.dt_inicio }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="">
                                <div class="form-group">
                                    <label >Até:</label>
                                    <span>{{ paradaProgramada.dt_fim }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="">
                                <div class="form-group">
                                    <label >Usuário:</label>
                                    <span>{{ paradaProgramada.nome }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6" v-if="paradaProgramada.nome_alteracao">
                            <div class="">
                                <div class="form-group">
                                    <label >Usuário alteração:</label>
                                    <span>{{ paradaProgramada.nome_alteracao }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="">
                                <div class="form-group">
                                    <label >Observação:</label>
                                    <span v-html="this.nl2br(this.paradaProgramada.observacao)"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button v-if="!this.cadastrar && can.monitoramento_cadastrar" class="btn btn-warning pull-left" @click="$modal.show('modal-confirm', {titulo: 'Deseja remover a parada programada?'})" type="button" :disabled="bloqueiaBotao"><i class="fa fa-trash" aria-hidden="true"></i> Remover</button>
                    <button class="btn btn-danger" @click="$modal.hide('modal-parada-programada')" type="button">Cancelar</button> 
                    <button v-if="!this.cadastrar && can.monitoramento_cadastrar" class="btn btn-primary" @click="edit" type="button" :disabled="bloqueiaBotao"> Editar </button>
                    <button v-if="this.cadastrar && can.monitoramento_cadastrar" class="btn btn-primary" :disabled="isDisabled" @click.prevent="salvarParada()" type="submit" >Salvar</button>
                </div>

                <div class="row" v-if="errors">
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
        name: 'VcParadaProgramadaModal',
        data (){
            return {                
                pesquisa: {
                    parada: '',
                    observacao: '',
                    id:'',
                },
                cliente: '',
                status: '',
                produto: '',
                tipo: '',
                projeto: '',
                validaBotao:false, 
                bloqueiaBotao: false,
                hoje: '',
                mes_ate:'',
                mes_de:'',
                title: '',
                cadastrar: false,
                mensagem: '',
                errors:[],
                paradaProgramada: [],

                // Configuração do campo de data
                config1: {
                    format: 'DD/MM/YYYY HH:mm',
                    useCurrent: false,                    
                    showClose: true,
                    locale: 'pt-br',
                    ignoreReadonly: true,
                    minDate: new Date(),
                    sideBySide: true,

                },
                config2: {
                    format: 'DD/MM/YYYY HH:mm',
                    useCurrent: false,                    
                    showClose: true,
                    locale: 'pt-br',
                    ignoreReadonly: true,
                    sideBySide: true,
                    widgetPositioning:{
                        horizontal: 'right',
                        vertical: 'bottom'
                    }
                    //maxDate: new Date( new Date().getFullYear()+'-'+(new Date().getMonth()+1)+'-'+new Date().getDate()+' '+new Date().getHours()+':'+new Date().getMinutes()+':'+new Date().getSeconds() )
                }                
            }
        },
        methods: {            
          
            beforeOpen (event)
            {
                this.cadastrar = false
                this.title = 'Parada programada';
                this.pesquisa.id = event.params.id
                this.pesquisa.observacao = ''
                this.mensagem = ''
                this.errors = []
                this.validaBotao = false
                this.bloqueiaBotao = false


                this.cliente = event.params.cliente
                this.projeto = event.params.id_projeto
                this.produto = event.params.grupo
                this.tipo = event.params.tipo

                this.paradaProgramada = event.params.paradas_programadas 
                
                if ( this.paradaProgramada.length == 0  )
                {
                    this.cadastrar = true

                    // this.config1.minDate = ''+new Date().getFullYear()+'-'+(("0" + (new Date().getMonth() + 1)).slice(-2))+'-'+(("0" + (new Date().getDate())).slice(-2))+' '+(("0" + (new Date().getHours())).slice(-2))+':'+(("0" + (new Date().getMinutes())).slice(-2))
                    // this.setData()
                }
                else
                {
                    this.paradaProgramada = this.paradaProgramada[0]

                    
                    this.status = 'Parada programada agendada'
                    if( this.paradaProgramada.parada_programada ){
                        this.status = 'Parada programada no momento'
                    }

                }
            }, 
            setMaxDate()
            {
                var agora = new Date();
                var data_ini = this.pesquisa.de.split(' ');
                data_ini = data_ini[0].split('/').reverse().join('/') +' '+ data_ini[1];

                var data_fim = this.pesquisa.ate.split(' ');
                data_fim = data_fim[0].split('/').reverse().join('/') +' '+ data_fim[1];                
                
                if ( new Date(data_ini) >= new Date(data_fim) || new Date(data_ini) < agora )
                {
                    this.hoje = new Date();
                    this.pesquisa.de = ''+this.hoje.getDate()+'/'+(this.hoje.getMonth()+1)+'/'+this.hoje.getFullYear()+' '+this.hoje.getHours()+':'+this.hoje.getMinutes()+':'+this.hoje.getSeconds()
                }
            },
            setMinDate()
            {
                var data_ini = this.pesquisa.de.split(' ');
                data_ini = data_ini[0].split('/').reverse().join('/') +' '+ data_ini[1];

                var data_fim = this.pesquisa.ate.split(' ');
                data_fim = data_fim[0].split('/').reverse().join('/') +' '+ data_fim[1];                
        
                if (new Date(data_fim) <= new Date(data_ini) ){
                    this.hoje = new Date();
                    let s = new Date();
                    s.setHours(this.hoje.getHours() + 1);

                    this.pesquisa.ate = ''+s.getDate()+'/'+(s.getMonth()+1)+'/'+s.getFullYear()+' '+s.getHours()+':'+s.getMinutes()+':'+s.getSeconds();
                }
            },
            salvarParada()
            {
                this.validaBotao = true;

                if( this.paradaProgramada.length == 0 )
                {
                    this.store()
                }
                else
                {
                    this.update()
                }
            },
            store()
            {
                window.axios.post( '/monitoramento/parada-programada', this.pesquisa)
                            .then(response=>{
                                this.mensagem = response.data.mensagem;
                            }).catch(error => {
                                this.errors = error.response.data.errors;
                                this.validaBotao = false;
                            });

                    window.setTimeout(() => {
                        this.$modal.hide('modal-parada-programada');
                         filtroBus.$emit('editTable')
                    }, 2000);
            },
            update()
            {
                this.pesquisa.old_de = this.paradaProgramada.dt_inicio;
                this.pesquisa.old_ate = this.paradaProgramada.dt_fim;
                this.pesquisa.old_observacao = this.paradaProgramada.observacao;

                window.axios.put( '/monitoramento/parada-programada/'+this.paradaProgramada.id, this.pesquisa)
                            .then(response=>{
                                this.mensagem = response.data.mensagem;
                            }).catch(error => {
                                this.errors = error.response.data.errors;
                                this.validaBotao = false;
                            });

                    window.setTimeout(() => {
                        this.$modal.hide('modal-parada-programada');
                         filtroBus.$emit('editTable')
                    }, 2000);
            },
            removerParada()
            {

                this.$modal.hide('modal-confirm');
                
                this.pesquisa.old_de = this.paradaProgramada.dt_inicio;
                this.pesquisa.old_ate = this.paradaProgramada.dt_fim;
                this.pesquisa.old_observacao = this.paradaProgramada.observacao;

                window.axios.delete( '/monitoramento/parada-programada/'+this.paradaProgramada.id, {data: this.pesquisa})
                        .then(response=>{
                            this.mensagem = response.data.mensagem;
                            //this.setData();
                            //this.validaBotao = false;
                            //this.setMinDateConfig1();
                            //this.paradaProgramada = [];
                            //this.cadastrar = true;
                            //filtroBus.$emit('editTable')
                            this.bloqueiaBotao = true;
                            this.$modal.hide('modal-confirm');
                        }).catch(error => {
                            this.errors = error.response.data.errors;
                        });

                        window.setTimeout(() => {
                            this.$modal.hide('modal-parada-programada');
                             filtroBus.$emit('editTable')
                        }, 2000);
            },
            setData()
            {
                this.hoje = new Date();
                let s = new Date();
                s.setHours(this.hoje.getHours() + 1);

                this.pesquisa.ate = ''+s.getDate()+'/'+(s.getMonth()+1)+'/'+s.getFullYear()+' '+s.getHours()+':'+s.getMinutes()+':'+s.getSeconds();
                this.pesquisa.de = ''+this.hoje.getDate()+'/'+(this.hoje.getMonth()+1)+'/'+this.hoje.getFullYear()+' '+this.hoje.getHours()+':'+this.hoje.getMinutes()+':'+this.hoje.getSeconds()
            },
            edit(){
                this.cadastrar = true;

                this.setMinDateConfig1()
                this.pesquisa.observacao = this.paradaProgramada.observacao
                this.pesquisa.de = this.paradaProgramada.dt_inicio
                this.pesquisa.ate = this.paradaProgramada.dt_fim
            },
            setMinDateConfig1(){
                var data_ini = this.paradaProgramada.dt_inicio.split(' ');
                var hora_ini = data_ini[1].split(':')
                data_ini = data_ini[0].split('/').reverse().join('/') +' '+ hora_ini[0]+':'+hora_ini[1];

                this.config1.minDate = new Date(data_ini)
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
                if( this.pesquisa.de != '' && this.pesquisa.ate != '' && this.pesquisa.observacao != '' && !this.validaBotao){
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
<style>
    .bootstrap-datetimepicker-widget{        
        display: flex;
    }
    .v--modal 
    {
        background-color: #ffffff00;
        text-align: left;
        border-radius: 3px;
        box-shadow: none;
        padding: 0;
    }
</style>