<template>
    
    <div class="row">

        <div class="col-md-3">
            <div class="box box-widget widget-user-2">
                <div class="widget-user-header bg-green">
                    <div class="widget-user-image">
                        <img class="img-circle user-image" :src="'/rh/funcionario/avatar-pequeno/'+funcionario.id" :alt="funcionario.nome" style=" background-color: #FFF;" @error="imageLoadError($event)" v-if="funcionario.id">
                    </div>                                        
                    <h3 class="widget-user-username">{{ funcionario.nome | ucfirst }}</h3>
                    <h5 class="widget-user-desc">{{ funcionarioDepartamento.nome | ucfirst }}</h5>                    
                </div>
            </div>
                            
            <div v-if="JSON.parse(this.can).BOTAO_EDITAR">                
                <a :href="funcionario.id+'/edit'" class="btn btn-primary pull-left"><i class="fa fa-edit margin-r-5"></i>Editar</a>                
            </div>
        </div>

        <!-- left column -->
        <div class="col-md-9">
            
           <vue-tabs class="nav-tabs-custom tab-success">
                
                <v-tab title="Dados pessoais" icon="fa fa-user margin-r-5" v-if="JSON.parse(this.can).ABA_DADOS_PESSOAIS">
                    <div class="tab-content">
                        <div class="box-body">
                            <div>
                                <strong>Nome completo:</strong>
                                <p class="text-muted">{{ funcionario.nome_completo }}</p>
                            </div>
                            <div>
                                <strong>Data de nascimento:</strong>
                                <p class="text-muted">{{ funcionario.dt_nascimento | filtroData }}</p>
                            </div>
                        </div>
                    </div>
                </v-tab>

                <v-tab title="Contato" icon="fa fa-book margin-r-5" v-if="JSON.parse(this.can).ABA_CONTATO">
                    <div class="tab-content">
                        <div class="box-body">
                            <div>
                                <strong>E-mail:</strong>
                                <p class="text-muted">{{ funcionario.email }}</p>
                            </div>

                            <div>
                                <strong>Celular pessoal:</strong>
                                <p class="text-muted">{{ funcionario.celular_pessoal | celular}}</p>
                            </div>
                
                            <div>
                                <strong>Celular corporativo:</strong>
                                <p class="text-muted" v-if='funcionario.celular_corporativo'>{{ funcionario.celular_corporativo | celular }}</p>
                                <p class="text-muted" v-if='!funcionario.celular_corporativo'>-</p>
                            </div>
                
                            <div>
                                <strong>Telefone comercial:</strong>
                                <p class="text-muted">{{ funcionario.telefone_comercial | telefone }}</p>
                            </div>
                
                            <div>
                                <strong>Ramal:</strong>
                                <p class="text-muted">*{{ funcionario.ramal }}</p>
                            </div>
                        </div>
                    </div>
                </v-tab>

                <v-tab title="Dados do funcionário" icon="fa fa-id-card-o margin-r-5" v-if="JSON.parse(this.can).ABA_DADOS_FUNCIONARIO">
                    <div class="tab-content">
                        <div class="box-body">                            
                            <div>
                                <strong>Cargo:</strong>
                                 <p class="text-muted">{{ funcionarioCargo.nome}}</p>
                              
                            </div>                
                            <div>
                                <strong>Gestor:</strong>                                
                                <p class="text-muted">{{ funcionarioGestor.nome }}</p>
                              
                            </div>
                        </div>
                    </div>
                </v-tab>

                <v-tab title="Dados do cadastro" icon="fa fa-file-text-o margin-r-5" v-if="JSON.parse(this.can).ABA_DADOS_DE_CADASTRO">                    
                    <div class="tab-content">
                        <div class="box-body">                            
                            <div>
                                <strong>Data de inclusão:</strong>
                                <p class="text-muted" v-if='funcionario.created_at'>
                                    
                                    {{ funcionario.created_at | filtroDataHora }}

                                    <span v-if='funcionario.usuario_inclusao'>
                                        ({{ funcionario.usuario_inclusao }})
                                    </span>
                                </p>
                                <p class="text-muted" v-if='!funcionario.created_at'>-</p>
                            </div>                
                            <div>
                                <strong>Data de alteração:</strong>
                                <p class="text-muted" v-if='funcionario.updated_at'>
                                    
                                    {{ funcionario.updated_at | filtroDataHora  }}

                                    <span v-if='funcionario.usuario_alteracao'>
                                        ({{ funcionario.usuario_alteracao }})
                                    </span>
                                </p>                                
                            </div>
                        </div>
                    </div>
                </v-tab>

                
                <v-tab title="Histórico de avatar" icon="fa fa-camera margin-r-5" v-if="JSON.parse(this.can).ABA_HISTORICO_AVATAR && JSON.parse(this.dados).historicoAvatar.length > 0">
                    <div class="tab-content">
                        <div class="box-body">
                            <section class="content">
                                <!-- row -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- The time line -->
                                        <ul class="timeline">
                                            <!-- timeline time label/item -->
                                            <li v-for="h in historicos" :class="[ h.data  ? 'time-label': ''] ">
                                                <span class="bg-green" v-if="h.data">
                                                    {{ h.data | filtroData}}
                                                </span>
                                                <i v-if="!h.data" class="fa fa-camera bg-purple"></i>

                                                <div class="timeline-item" v-if="!h.data">
                                                    <span class="time"><i class="fa fa-clock-o"></i>&nbsp;{{ h.created_at | filtroHora }}</span>
                                                    <h3 class="timeline-header"><a href="#">{{ h.nome }}</a>, incluiu o avatar.</h3>
                                                    <div class="timeline-body">
                                                        <a href="#" @click="$modal.show('show-imagem-modal'+h.id)">
                                                            <img :src="'/rh/funcionario/avatar/'+h.id" class="margin" style="height: 84px; width: 70px;" v-if="h.id">
                                                        </a>

                                                        <span v-if="h.id">
                                                            <vc-modal-imagem :id="h.id" :src="'/rh/funcionario/avatar/'+h.id" :modalWidth="150"></vc-modal-imagem>
                                                        </span>

                                                        <span v-if="botaExcluir">
                                                            <vc-modal-link icone="fa-trash" :modal="'modal-confirm'+h.id" title="Remover Histórico"></vc-modal-link>
                                                            <vc-modal-confirm :modalId="h.id" tituloModal="Confirmar a exclusão desse histórico?" ajax="/rh/funcionario/avatar/" :refresh="'/rh/funcionario/'+funcionario.id" ></vc-modal-confirm>
                                                        </span>
                                                    </div>                                                    
                                                </div>
                                            </li>
                                            <!-- /.timeline-label/itemmodulos/rh/funcionario/visualizar.vue -->
                                            
                                            <li>
                                                <i class="fa fa-clock-o bg-gray"></i>
                                            </li>
                                        </ul>
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->
                            </section>
                        </div>
                    </div>
                </v-tab>
                
            </vue-tabs>
                  
        </div>
        <!-- /.left colum -->
        
    </div>

</template>

<script>

    //https://github.com/cristijora/vue-tabs
    import {VueTabs, VTab} from 'vue-nav-tabs'
    
    import moment from 'moment'

    export default {
        props:['dados', 'can'],        
        components: {
            VueTabs,
            VTab            
        },
		data () {
            return {
                funcionario: {},                
                funcionarioInclusao: {},
                funcionarioAlteracao: {},
                funcionarioCargo: {},
                funcionarioDepartamento: {},
                funcionarioGestor: {},
                historicos: {},
                mostraHistorico: false,
                botaExcluir: false
	        }
        },
        filters: {
            ucfirst(value){
                if (!value) return ''
                value = value.toString()
                value = value.toLowerCase()                
                return value.charAt(0).toUpperCase() + value.slice(1)
            },            
            filtroData(value){

                if( value !== null ){
                   return moment(String(value)).format('DD/MM/YYYY')
                }
                return null
            },            
            filtroDataHora(value){
                
                if( value !== null ){
                   return moment(String(value)).format('DD/MM/YYYY - HH:mm:ss')
                }
                return null;
            },        
            telefone(value){
                if( value !== null ){
                    var telefone = new String(value)
                    var re = new RegExp("^([0-9]{2})([0-9]{4})([0-9]{4})$");                
                    return telefone.replace(re, '($1) $2-$3');
                }
                return null
            },
            celular(value){
                if( value !== null ){
                    var telefone = new String(value)
                    var re = new RegExp("^([0-9]{2})([0-9]{5})([0-9]{4})$");                
                    return telefone.replace(re, '($1) $2-$3');
                }
                return null
            },
            filtroHora(value){

                if( value !== null ){
                   return moment(String(value)).format('HH:mm')
                }
                return null
            },            
        },
        methods:{           
            montaHistoricoAvatar(){
                var novaLista = JSON.parse(this.dados).historicoAvatar
                        
                var lista = [];
                var novaData = null;
                
                novaLista.map(function(value, index){

                    if( moment(String(novaData)).format('DD/MM/YYYY') != moment(String(value.created_at)).format('DD/MM/YYYY') ){
                                                
                        novaData = value.created_at;
                        lista.push( { data: novaData } );
                    }

                    lista.push( value );

                });
                this.historicos = lista;
            },
            imageLoadError(event){
                event.target.style.display = 'none';                
            }
        },
        mounted()
        {
            this.funcionario= JSON.parse(this.dados).funcionario
            this.funcionarioInclusao= JSON.parse(this.dados).funcionarioInclusao
            this.funcionarioAlteracao= JSON.parse(this.dados).funcionarioAlteracao            
            this.funcionarioCargo= JSON.parse(this.dados).funcionarioCargo
            this.funcionarioDepartamento= JSON.parse(this.dados).funcionarioDepartamento
            this.funcionarioGestor= JSON.parse(this.dados).funcionarioGestor
            this.botaExcluir = JSON.parse(this.can).BOTAO_EXCLUIR_AVATAR

            /**
             *Se não existir nenhum histórico de avatar, não irá mostrar a aba
             */
            if( JSON.parse(this.dados).historicoAvatar.length > 0 ){
                this.montaHistoricoAvatar(JSON.parse(this.dados).historicoAvatar)
                this.mostraHistorico = true
            }
        }
    };
</script>

<style>
    .nav-tabs>li>a {
        color: #444 !important;
    }

    .nav-tabs>li.active>a, 
    .nav-tabs>li.active>a:focus, 
    .nav-tabs>li.active>a:active {
        color: #444;
        border-top-width: 3px;
        border-top-color: #00a65a;
        border-radius: 5px;
    }

</style>
<style scoped>
    .timeline-body{
        background-color: #f0f0f0;
    }
</style>


