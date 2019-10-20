<template>

<div class="row">

<vc-mensagens-filtros :funcionarios="funcionarios" :id_mensagem="id_mensagem" @pesquisar="pesquisar" @limpar="limpar"></vc-mensagens-filtros>

<div class="col-lg-9 col-md-12">
    <div class="overlay" v-if="carregando">
        <i class="fa fa-refresh fa-spin"></i>
    </div>
    <div v-else >
        <div class="box box-warning" style="border-top-color: #ed7d31">
            <div class="box-header with-border">
                <h3 class="box-title">Mensagens recebidas</h3>
                <span class="overlay" v-if="mensagens_carregando">
                </span>
                <span v-else>
                    <span v-if="mensagens.length == 0" class="pull-right">nenhum resultado</span>
                    <span v-else-if="mensagens.length == 1" class="pull-right">1 resultado</span>
                    <span v-else-if="mensagens.length > 1" class="pull-right">{{mensagens.length}} resultados</span>
                </span>
            </div>
            <div class="box-body">

                <div v-if="mensagens.length != 0">                 
                    <div class="post" v-for="(object, index) in mensagens">
                        <div class="user-block">
                            <img class="img-circle img-bordered-sm" :src="getUrlImagemAvatarPequeno()+object.funcionario_id" :alt="object.usuario_nome">
                            <span class="username">
                                <a href="#">{{object.usuario_nome}}</a>
                            </span>
                            <span class="description">
                                <span data-toggle="tooltip" data-placement="right" :data-original-title="object.created_at" data-delay="100">
                                    <small><i class="fa fa-clock-o"></i> {{object.data_interacao}}</small>
                                </span>
                            </span>
                        </div>
                        <p style="margin: 0 0 5px;" class="mensagem_titulo_publicacao">
                            <a :href="'/base-de-conhecimento/publicacoes/'+object.publicacoes_id">
                                <b><i class="fa fa-angle-double-right"></i> {{object.titulo}}</b>
                            </a>
                        </p>
                              
                        <p v-html="object.mensagem"></p>
                   
                        <div v-if="object.respondida">
                            <i class="fa fa-reply" style="padding-right:5px;"></i>{{object.respondeu_nome}} - {{object.dt_resposta}}
                            <i><p v-html="object.resposta"></p></i>
                        </div>
                        <div v-else>
                            <vc-modal-button texto="Marcar como respondida" btnClass="btn-primary" icone="" :modal="'resposta-mensagem-modal'+object.publicacoes_mensagens_id"></vc-modal-button>
                            <vc-resposta-mensagem-modal :mensagem_id="object.publicacoes_mensagens_id" @atualizar="atualiza"> </vc-resposta-mensagem-modal>
                            
                        </div>

                    </div>
                    <!--/post-->
                </div>
                <div v-else>
                    <div class="text-center">
                        <span>Nenhuma mensagem encontrada.</span>
                    </div>
                </div>
              
            </div>
            <div class="overlay" v-if="mensagens_carregando">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div>
    </div>
</div>
</div>

</template>

<script>
    export default {
        props:['funcionarios', 'id_mensagem'],
        data(){
            return {        
                carregando: true,
                mensagens_carregando: true,
                item: {
                    funcionarios: '',
                    data_de: '',
                    data_ate: '',
                    mensagem: '',
                    publicacao: '',
                    respondida:''
                },
                mensagens: {},
            }
        },
        methods:{
            getUrlImagemAvatarPequeno(){
                return '/rh/funcionario/avatar-pequeno/';
            },
            getUrl(){
                return '/base-de-conhecimento/mensagens/getMensagens';
            },                     
            ajaxMensagem()
            {
                this.mensagens_carregando = true;

                //Filtros do componente MensagensFiltros
                if( this.$cookie.get('base_mensagem_data_de') && this.$cookie.get('base_mensagem_data_ate') )
                {
                    this.item.data_de = this.$cookie.get('base_mensagem_data_de');
                    this.item.data_ate = this.$cookie.get('base_mensagem_data_ate');
                    this.item.funcionarios = this.$cookie.get('base_mensagem_funcionario');
                    this.item.mensagem = this.$cookie.get('base_mensagem_mensagem');
                    this.item.publicacao = this.$cookie.get('base_mensagem_publicacao');
                    this.item.respondida = this.$cookie.get('base_mensagem_respondida');

                    window.axios.post( this.getUrl(), this.item)
                    .then(response=>{
                        this.mensagens = response.data;
                        this.carregando = false;
                        this.mensagens_carregando = false;
                    })
                    .catch(error => {
                        this.errors = error.response.data.errors;
                        this.carregando = false;
                        return 0; 
                    });
                }
            },
            ajaxMensagemId()
            {
                this.mensagens_carregando = true;

                window.axios.post( this.getUrl()+'/'+this.id_mensagem )
                .then(response=>{
                    this.mensagens = response.data;
                    this.carregando = false;
                    this.mensagens_carregando = false;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.carregando = false;
                    return 0; 
                });
            },
            pesquisar()
            {
                this.ajaxMensagem();
            },
            limpar()
            {
                this.ajaxMensagem();
            },
            atualiza(){
                this.carregando = false;

                if( this.id_mensagem == 0 )
                {
                    this.ajaxMensagem();
                }
                else
                {
                    this.ajaxMensagemId();
                }
            }
        },
        mounted()
        {
            this.carregando = false;

            if( this.id_mensagem == 0 )
            {
                this.ajaxMensagem();
            }
            else
            {
                this.ajaxMensagemId();
            }
        }
    };
</script>
<style>
    .mensagem_titulo_publicacao a{
        color: #666;
        text-decoration: none;
    }
    .mensagem_titulo_publicacao a:hover{
        color: #333;
        text-decoration: none;
    }
</style>