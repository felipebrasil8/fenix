<template>
    <div>
        <div class="overlay" v-if="carregando">
            <i class="fa fa-refresh fa-spin"></i>
        </div>

        <div class="box box-warning" style="border-top-color: #ed7d31" v-else>
            <div class="box-header with-border">
                <div v-if="edicao">

                    <span class="pull-right href_no_color" v-if="can.editar || can.conteudo_editar">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);" aria-expanded="false">
                            <i class="fa fa-pencil-square-o margin-r-5"></i> 
                            Editar <span class="caret"></span>
                        </a>
                        <vc-dropdown-menu :can="can" :publicacaoId="publicacao_id" ></vc-dropdown-menu>
                    
                    </span>

                    <span class="pull-right href_no_color" style="padding-right: 15px;" v-if="can.cadastrar">
                        <vc-modal-link texto="Adicionar publicação" modal="publicacao-nova-modal" icone="fa-plus-square" v-if="can.cadastrar"></vc-modal-link>
                    </span>

                    <span class="pull-right href_no_color" style="padding-right: 15px;" v-if="can.historico">
                        <a :href="'/base-de-conhecimento/publicacoes/'+object.id">
                            <span title="Visualizar histórico das publicações">
                                <i class="fa fa-arrow-left margin-r-5" style="font-size: 16px;"></i>
                                Voltar                                
                            </span>
                        </a>
                    </span>

                </div>
                <div v-else>                    
                    <span v-if="!edicao" class="href_no_color">                        
                        <a href="#" class="pull-right" v-if="object.favorito" @click.prevent="setFavorito( object.id )">
                            <span title="Remover de favoritos">
                                <i class="fa fa-star" style="font-size: 16px; color: #f39c12;"></i>
                            </span>
                        </a>
                        <a href="#" class="pull-right" v-else @click.prevent="setFavorito( object.id )">
                            <span title="Adicionar aos favoritos">
                                <i class="fa fa-star-o" style="font-size: 16px; color: #f39c12;"></i>
                            </span>
                        </a>
                        <a :href="'/base-de-conhecimento/publicacoes/'+object.id"  class="pull-right" style="padding-right: 15px;" v-if="can.historico">
                            <span title="Visualizar histórico das publicações">
                                <i class="fa fa-arrow-left margin-r-5" style="font-size: 16px;"></i>
                                Voltar                                
                            </span>
                        </a>
                    </span>                    
                </div>

                <h3 class="box-title "><strong>{{ object.titulo }}</strong></h3>
            </div>

            <div class="content" v-if="historicos.length > 0">                
                <div class="row">
                    <div class="col-xs-12">
                        <div id="timeline">
                            <ul class="timeline timeline-inverse">
                                    
                                <li v-for="h in novos_historicos" :class="[ h.data  ? 'time-label': ''] ">
                                   
                                    <span class="bg-light-blue" v-if="h.data">
                                        {{ h.data }}
                                    </span>

                                    <i class="fa" 
                                        :class="'fa-'+h.icone" 
                                        style="color:white" 
                                        :style="'background-color:'+ h.cor" 
                                        v-if="!h.data"></i>

                                    <div class="timeline-item" v-if="!h.data">
                                    
                                        <span class="time"><i class="fa fa-clock-o"></i>
                                            {{ h.horario }}
                                        </span>
                                        <h3 class="timeline-header no-border">
                                            <a href="#">{{ h.nome }}</a> 
                                            {{ h.mensagem }}
                                        
                                            &nbsp;

                                            <vc-modal-link icone="fa-comment" :modal="'modal-text'+h.id" v-if="h.observacao != null && h.observacao != ''"></vc-modal-link>
                                        
                                            <vc-modal-text :modalId="h.id" :titulo="h.observacao"></vc-modal-text>                                            
                                            
                                        <span v-if="can.historico_excluir && h.alteracao == 'ATUALIZACAO' && edicao">
                                             <vc-modal-link icone="fa-trash" :modal="'modal-confirm'+h.id" v-if="h.alteracao = 'ATUALIZACAO'" title="Remover Histórico"></vc-modal-link>

                                            <vc-modal-confirm :modalId="h.id" tituloModal="Confirmar a exclusão desse histórico?" ajax="/base-de-conhecimento/publicacoes/historico-delete/" :refresh="'/base-de-conhecimento/publicacoes/'+publicacao_id+'/historico'" ></vc-modal-confirm>
                                            
                                        </span>
                                        </h3>
                                    </div>
                                </li>

                                <li>
                                    <i class="fa fa-clock-o bg-gray"></i>
                                </li>
                                
                            </ul>
                        </div>
                    </div>
                </div><!-- ./row -->
            </div><!-- ./content -->
        </div>
    </div>	
</template>

<script>
    export default {
        props:['publicacoes_historicos', 'publicacao_id'],
        data(){
            return {
            	historicos: {},            	
                novos_historicos: {},
                carregando: true,
                can:[],
                object:[],
                edicao:false
            }
        },
        methods:{            
            getHistorico(){

                window.axios.post(this.getUrlHistorico()).then((response) =>{

                    this.historicos = response.data.historicos;                    
                    this.carregando = false;                

                    if( this.historicos.length > 0 ){
                    
                        
                        var lista = [];                
                        var novaData = null;
                        this.historicos.forEach(function(value, index){

                            if( novaData != value.created_at ){
                                
                                novaData = value.created_at;
                                lista.push( { data: novaData } );
                            }

                            lista.push( value );

                        });                

                        this.novos_historicos = lista;
                    }
                });
            },
            
            get(){                
                window.axios.get(this.getUrl()).then((response) =>{
                    
                    this.can = response.data.can;
                    this.object = response.data.publicacao;

                });

            },
            getStatusEdicao(){
                if( this.$cookie.get('editar_publicacao') == 'true' )
                    this.edicao = true;
                else
                    this.edicao = false;
            },
            getUrl(){
                return '/base-de-conhecimento/publicacao/'+this.publicacao_id;
            },
            getUrlHistorico(){
                return '/base-de-conhecimento/publicacoes/'+this.publicacao_id+'/historico';
            },
            exclir(id){
                 window.axios.delete('/base-de-conhecimento/publicacoes/historico-delete/'+id, {
                    }).then((response) =>{
                        var s = this
                        setTimeout(function(){ 
                           window.location.href = '/base-de-conhecimento/publicacoes/'+s.publicacao_id+'/historico';
                        }, 1000);
                        return 0;
                    
                    }).catch(function (error) {
                    
                        this.errors = error.response.data.errors;
                        return 0;
                    });
            },
            
            /**
             * [setFavorito]
             * @param {[type]} id    [id da publicacao_favoritos]
             * @param {[type]} index [index of object]
             */
            setFavorito( id ){

                let fav = this.object.favorito;
                this.object.favorito = !fav;

                if( fav ){
                
                    let url = '/base-de-conhecimento/publicacao/favoritos/'+id;
                    window.axios.delete(url, {
                    }).then((response) =>{
                        this.object.favorito = !fav;
                    }).catch(function (error) {
                        this.object.favorito = fav;
                    });

                }else{

                    let url = '/base-de-conhecimento/publicacao/favoritos';
                    window.axios.post(url, {
                       publicacao_id: id
                    }).then((response) =>{
                        this.object.favorito = !fav;
                    }).catch(function (error) {
                        this.object.favorito = fav;
                    });
                }
            },
        },
        mounted(){
            
            this.get();
            this.getHistorico();
            this.getStatusEdicao();
        }
    };

</script>

<style>
    .timeline > li > .timeline-item > .timeline-header {
        font-size: 14px;
    }   
    .href_no_color a{
        color: #333;
        text-decoration: none;
    }
    .href_no_color a:hover{
        color: #000;
        text-decoration: none;
    } 
</style>

