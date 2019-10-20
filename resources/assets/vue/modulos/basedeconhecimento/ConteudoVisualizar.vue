<template>   
    <div>
        <div class="box box-warning" style="border-top-color:#ed7d31; min-height: 300px;" v-if="carregando || carregandoConteudo">
            <div class="overlay">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
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

                    <span class="pull-right href_no_color" style="padding-right: 15px;" v-if="can.historico && edicao">
                        <a :href="'/base-de-conhecimento/publicacoes/'+this.publicacao_id+'/historico'">
                            <span title="Visualizar histórico das publicações">
                                <i class="fa fa-history margin-r-5" style="font-size: 16px;"></i>
                                Histórico                                
                            </span>
                        </a>
                    </span>
                </div>
                <div >
                    <h3 class="box-title "><strong>{{ object.titulo }}</strong></h3>
                    <span v-if="!edicao" class="pull-right">
                        <a href="#">
                            <i class="fa fa-share-alt" style="font-size: 16px; color: #f39c12;" @click="$modal.show('modal-recomendacao'+object.id )" title="Recomendar publicação para outro usuário"></i>
                        </a>
                        &nbsp;
                        <a href="#" v-if="object.favorito" @click.prevent="setFavorito( object.id )">
                            <span title="Remover de favoritos">
                                <i class="fa fa-star" style="font-size: 16px; color: #f39c12;"></i>
                            </span>
                        </a>
                        <a href="#" v-else @click.prevent="setFavorito( object.id )">
                            <span title="Adicionar aos favoritos">
                                <i class="fa fa-star-o" style="font-size: 16px; color: #f39c12;"></i>
                            </span>
                        </a>
                    </span>
                </div>
                <vc-recomendacao-modal :publicacaoId="object.id" :titulo="object.titulo" modal="publicacao-restricao-modal"></vc-recomendacao-modal>

            </div>
            <!-- /.box-header -->
            <div class="row publicacao">

                <!-- TAG -->
                <div v-if="tagExibir">
                    <div class="tag">
                        <h5><strong>TAGS:</strong></h5>
                    </div>    
                    <div class="tags" v-for="item in dados_response.tags">
                        <a :href="'/base-de-conhecimento/publicacoes/tag/'+item.tag" style="color: #000;">
                            <strong> {{ item.tag }} </strong>
                        </a>
                    </div>              
                    <div class="col-xs-12 no-padding">
                        <div class="linha"></div>
                    </div>
                </div>

                <!-- CONTEÚDO -->
                <div class="corpo">
                    <div v-for="item in dados_response.conteudo" :key="item.id" >

                        <!-- INICIO DO BLOCO DE LINHA -->
                        <div v-if="(item.nome == 'LINHA')">
                            <div class="col-xs-12" style="padding: 15px 0;">
                                <div class="linha"></div>
                            </div>
                        </div>
                        <!-- FIM DO BLOCO DE LINHA -->

                        <!-- INICIO DO BLOCO DE TEXTO -->
                        <div v-if="(item.nome == 'TEXTO')" >
                            <div class="col-xs-12 conteudo-texto" :style="item.conteudo ? 'padding: 15px 0;' : '' " v-html="item.conteudo"></div>
                            <texto-modal :conteudoId="item.id" :conteudo="item.conteudo"></texto-modal>
                        </div>
                        <!-- FIM DO BLOCO DE TEXTO -->

                        <!-- INICIO DO BLOCO DE IMAGEM -->
                        <div v-if="(item.nome == 'IMAGEM')">
                            <div class="col-xs-12" :style="item.dados ? 'padding: 15px 0;' : '' ">
                                <img v-if="item.dados" :src="getUrlImagemMiniatura() + item.id" @click="$modal.show('show-imagem-modal'+item.id)" class="img-responsive img-cursos-pointer">
                            </div>
                            <span v-if="item.dados">
                                <show-imagem-modal :conteudoId="item.id" :imagemWidth="item.width" :imagemHeight="item.height"></show-imagem-modal>
                            </span>
                            <imagem-modal :conteudoId="item.id" ></imagem-modal>
                        </div>
                        <!-- FIM DO BLOCO DE IMAGEM -->

                        <!-- INICIO DO BLOCO DE IMAGEM COM LINK -->
                        <div v-if="(item.nome == 'IMAGEM COM LINK')">
                            <div class="col-xs-12" :style="item.dados ? 'padding: 15px 0;' : '' ">
                                <a :href="item.adicional" target="_blank" >
                                    <img v-if="item.dados" :src="getUrlImagemOriginal() + item.id" @click="$modal.show('show-imagem-modal'+item.id)" class="img-responsive img-cursos-pointer">
                                </a>
                            </div>
                        </div>
                        <!-- FIM DO BLOCO DE IMAGEM COM LINK -->

                    </div>
                </div>

                <!-- COLABORADORES -->
                <div class="colaboradores" v-if="colaboradoresExibir">
                    <div class="col-xs-12" style="padding: 0 0 10px 0;">
                        <div class="linha"></div>
                    </div>
                    <div class="avatar" v-for="item in dados_response.colaboradores">
                        <a :href="'/base-de-conhecimento/publicacoes/colaborador/'+item.nome">
                            <img height="110px" :src="'/configuracao/usuario/avatar-grande/'+item.funcionario_id" data-toggle="tooltip" :data-original-title="item.nome">
                        </a>
                    </div>
                </div>
                
                <!-- PUBLICAÇOES RELACIONADAS -->
                <div class="relacionadas" v-if="relacionadosExibir">
                    <div class="col-xs-12" style="padding: 10px 0 10px 0;">
                        <div class="linha"></div>
                    </div>
                    <div class="col-xs-12">
                        <b>OUTRAS PUBLICAÇÕES RELACIONADAS:</b>
                    </div>
                    <div class="col-xs-12">
                        <slick ref="slick" :options="slickOptions">
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 margin-top tamanho" v-for="object in relacionadas">
                                <vc-componente-publicacao :object="object"></vc-componente-publicacao>
                            </div>
                        </slick>
                    </div>
                </div>

              

                <!-- DATAS DE PUBLICAÇÃO -->
                <div class="datas_publicaco">
                    <div class="col-xs-12" style="padding: 10px 0 10px 0;">
                        <div class="linha"></div>
                    </div>
                    <div class="col-xs-3">
                        <div v-if="publicacaoDatas.dt_publicacao"><b>Data de publicação:</b> <span v-html="publicacaoDatas.dt_publicacao"></span></div>
                    </div>
                    <div class="col-xs-3 text-center">
                        <div v-if="publicacaoDatas.dt_ultima_atualizacao"><b>Última atualização:</b> <span v-html="publicacaoDatas.dt_ultima_atualizacao"></span></div>
                    </div>
                    <div class="col-xs-3 text-center">
                        <div v-if="!publicacaoDatas.dt_desativacao && publicacaoDatas.dt_revisao"><b>Data de revisão:</b> <span v-html="publicacaoDatas.dt_revisao"></span></div>
                        <div v-else>
                            <div v-if="publicacaoDatas.dt_desativacao"><b>Data de desativação:</b> <span v-html="publicacaoDatas.dt_desativacao"></span></div>    
                        </div>
                    </div>
                    <div class="col-xs-3 text-right">
                        <div>
                            <b>{{publicacaoVisualizacoes}} visualizações</b>
                            <a href="#" v-if="edicao && can.exportarVisualizacao">
                                <i class="fa fa-download" @click="setExcel('visualizacaoPublicacao');downloadExcel()" style="padding-left: 8px;" ></i>
                            </a>
                        </div>
                    </div>
                </div>
                <form target="_blank" action="/base-de-conhecimento/exportacoes/download" method="post" id="excel">
                    <input type="text" name="titulo_excel" :value="titulo_excel"  hidden="hidden">
                    <input type="text" name="publicacao_id" :value="publicacao_id"  hidden="hidden">
                </form>

                  <!-- MENSAGEM -->
                <div class="mensagem" v-if="!edicao">
                    <div class="col-xs-12" style="padding: 10px 0 10px 0;">
                        <div class="linha"></div>
                    </div>
                    <div class="col-xs-12">
                        <div><b>Envie seu comentário ou sugestão em relação à publicação:</b></div>
                    </div>
                    <div class="col-xs-12" style="padding: 10px 0 10px 0;">
                        <div class="form-group">
                            <div class="col-lg-10 col-md-8 col-sm-12">
                                <textarea rows="4" class="form-control input-sm" style="text-transform: uppercase; resize: none;" v-model.trim="mensagem.mensagem"></textarea>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-12">
                                <button type="button" class="btn btn-sm btn-block btn-primary" :disabled="isDisabled" @click="setMensagem">
                                    <i class="fa fa-comment-o margin-r-5"></i>Enviar
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="callout no-margin-bottom callout-danger" v-for="value in errors" style="margin-top: 15px;">
                            <div>{{ value[0] }}</div>
                        </div>
                    </div>
                    <div v-if="mensagem_request">
                        <div class="col-md-12">
                            <div class="callout no-margin-bottom callout-success" style="margin-top: 15px;">
                                <div>{{ mensagem_request }}</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <vc-recomendacao-publicacao-mensagem-modal :recomendacao="recomendacao" @recomendacaoVisualizada="recomendacaoVisualizada"></vc-recomendacao-publicacao-mensagem-modal>
    </div>
</template>

<script>
    import Slick from 'vue-slick'
    export default {
        components: {
            Slick
        },
        props:['publicacao_id'],
        data(){
            return {                
                dados_response:[],
                conteudo_tipo:[],  
                can:[],
                carregando: true,
                carregandoConteudo: true,
                conteudoID:'',
                item:{
                    conteudo: '',
                },
                object: {
                    favorito: ''
                },
                publicacaoDatas:[],
                publicacaoVisualizacoes: [],
                relacionadas: [],
                slickOptions: {
                    infinite: false,
                    mobileFirst: true,
                    responsive: [
                    {
                        breakpoint: 1820,
                        settings: {
                            slidesToShow: 4,
                            slidesToScroll: 4,
                        }
                    },
                        {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                        }
                    },
                        {
                        breakpoint: 990,
                        settings: {
                         
                            slidesToShow: 2,
                            slidesToScroll: 2,
                        }
                    },
                        {
                        breakpoint: 420,
                         settings: {
                           slidesToShow: 1,
                            slidesToScroll: 1,
                                
                            }
                        }
                    ]
                },
                mensagem:{
                    publicacao_id: this.publicacao_id,
                    mensagem: ''
                },
                mensagem_request: '',
                errors:[],
                validaBotao: false,
                recomendacao:{
                    id: '',
                    usuario: '',
                    mensagem: '',
                },
                titulo_excel:'',
            }
        },
        methods: {
            get(){
                window.axios.get(this.getUrl()).then((response) =>{                    
                    this.object = response.data.publicacao;
                    this.can = response.data.can;                    
                    this.publicacaoDatas = response.data.datas;
                    this.publicacaoVisualizacoes = response.data.visualizacoes;
                    this.relacionadas = response.data.relacionadas;

                    this.carregando = false;

                    if( response.data.recomendadas )
                    {
                        this.recomendacao.id = response.data.recomendadas.id;
                        this.recomendacao.usuario = response.data.recomendadas.usuario;
                        this.recomendacao.mensagem = response.data.recomendadas.mensagem;
                        this.$modal.show('modal-recomendacao-publicacao-mensagem');
                    }
                });
            }, 
            getConteudo(){
                window.axios.get(this.getUrlConteudo()+this.publicacao_id).then((response) =>{
                    this.dados_response = response.data.conteudo;  
                    this.conteudo_tipo = response.data.conteudo_tipo;
                    this.carregandoConteudo = false;
                });
            },
            getUrl(){
                return '/base-de-conhecimento/publicacao/'+this.publicacao_id;
            },
            getUrlConteudo(){
                return '/base-de-conhecimento/conteudo/';
            },
            getUrlImagemMiniatura(){
                return '/base-de-conhecimento/publicacoes/img-conteudo-miniatura/';
            },
            getUrlImagemOriginal(){
                return '/base-de-conhecimento/publicacoes/img-conteudo-original/';
            },
            getUrlImagemPublicacao(){
                return '/base-de-conhecimento/publicacoes/img-publicacao/';
            },  
            exibir( lista ){
                if(lista == undefined || lista.length == 0){
                    return false;
                }
                return true;
            },
            getStatusEdicao(){
                if( this.$cookie.get('editar_publicacao') == 'true' )
                    this.edicao = true;
                else
                    this.edicao = false;
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
            setMensagem(){
                this.validaBotao = true;
                if( this.mensagem.mensagem != '' && this.mensagem.publicacao_id != '' )
                {
                    this.mensagem.mensagem = this.mensagem.mensagem.toUpperCase();
                    window.axios.post('/base-de-conhecimento/publicacao/mensagem', this.mensagem)
                    .then(response=>{
                        this.mensagem_request = response.data.mensagem;
                        this.mensagem.mensagem = '';

                        var self = this;
                        setTimeout(function(){ 
                            self.mensagem_request = '';
                        }, 3000);
                        this.validaBotao = false;
                        return 0; 
                     })
                    .catch(error => {
                        this.errors = error.response.data.errors;
                        this.validaBotao = false;
                        return 0; 
                    });
                    
                }
            },
            downloadExcel(){
                              
                setTimeout(function(){ 
                    $('#excel').submit();
                }, 100);
                return 0;
            
            },
            setExcel(excel){
                this.titulo_excel = excel
            },
            recomendacaoVisualizada( obj )
            {
                if( obj && obj.id != '' )
                {
                    window.axios.get('/base-de-conhecimento/publicacao/recomendacaoVisualizada/'+obj.id)
                        .then(response=>{
                            this.$modal.hide('modal-recomendacao-publicacao-mensagem');
                            window.location.reload();
                         })
                        .catch(error => {
                            window.location.reload();
                        });
                }else{
                    this.$modal.hide('modal-recomendacao-publicacao-mensagem');
                }
            }, 
        },
        computed: {
            tagExibir(){
                return this.exibir( this.dados_response.tags );
            },
            colaboradoresExibir(){
                return this.exibir( this.dados_response.colaboradores );
            },
            relacionadosExibir(){
                return this.exibir( this.relacionadas );
            },
            isDisabled(){
                if( this.mensagem.mensagem != '' && !this.validaBotao){
                    return false;
                }

                return true;
            },
        },
        mounted(){
            this.get();
            this.getConteudo();
            this.getStatusEdicao();
        }
    };
</script>
<style>
    .href_no_color a{
        color: #333;
        text-decoration: none;
    }
    .href_no_color a:hover{
        color: #000;
        text-decoration: none;
    }
    .tags{
        display: inline-block;
        background-color: #d2d6df;
        font-size: 14px;
        padding: 1px 10px;
        margin: 7px 2px !important;
        vertical-align: middle;
        border-radius: 4px;
        font-size: 12px;
    }
    .tag{
        margin-right: 15px;
        margin-left: 10px;
        float: left;
    }
    .publicacao{
        margin: 10px;
        padding-bottom: 25px;
    }
    .colaboradores{
       
    }
    .avatar{
        margin: 0px 10px;
        float: left;
    }
    .linha{
        border-top-width: 1px;
        border-top-style: solid;
        border-top-color: #d2d6e0;
    }
    .botoes{
        margin-top: 25px;
    }
    .conteudo-texto p{
        margin-bottom: 2px;
    }
    .conteudo-texto img{
        max-width: 100%;
        height: auto;
        display: block;
    }
    .img-cursos-pointer{
        cursor: pointer;
    }
    .no-padding{
        padding: 0;
    }
    .corpo {
        padding-left: 10px;
    }
    .ql-align-center{
        text-align: center; 
    }
    .ql-align-right {
        text-align: right;
    }
    .ql-editor .ql-align-justify {
        text-align: justify;
    }
    .ql-indent-1{
         padding-left: 3em; 
    }
    .ql-indent-2{
         padding-left: 6em; 
    }
    .ql-indent-3{
         padding-left: 9em; 
    }
    .ql-indent-4{
         padding-left: 12em; 
    }
    .ql-indent-5{
         padding-left: 15em; 
    }
    .ql-indent-6{
         padding-left: 18em; 
    }
    .ql-indent-7{
         padding-left: 21em; 
    }
    .ql-indent-8{
         padding-left: 24em; 
    }
    .ql-indent-9{
         padding-left: 27em; 
    }
    .slick-slider {
        margin-bottom: 0px !important;
    }
    .ql-font-monospace {
        font-family: Monaco,Courier New,monospace;
    }
   
</style>
