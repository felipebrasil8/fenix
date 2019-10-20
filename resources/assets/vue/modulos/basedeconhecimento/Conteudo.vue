<template>   
    <div>
        <div class="box box-warning" style="border-top-color:#ed7d31;min-height: 300px;" v-if="carregando || carregandoConteudo">
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
                        <a :href="'/base-de-conhecimento/publicacoes/'+this.publicacao_id+'/historico'" >
                            <span title="Visualizar histórico das publicações">
                                <i class="fa fa-history margin-r-5" style="font-size: 16px;"></i>
                                Histórico                                
                            </span>
                        </a>
                    </span>

                </div>
                <h3 class="box-title "><strong>{{ object.titulo }} <span v-if="request == 'rascunho'">(MODO RASCUNHO)</span></strong></h3>
            </div>
            <!-- /.box-header -->
            <div class="row publicacao">

                <div v-if="request == 'edit' || request == 'rascunho' && existe_rascunho == 'true'">
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
                        <vc-modal-link icone="fa-pencil-square-o" modal="tags-modal" layout="pull-right" v-if="edit" title="Editar TAGS" ></vc-modal-link>
                        <div class="col-xs-12 no-padding">
                            <div class="linha"></div>
                        </div>
                    </div>

                    <!-- CONTEÚDO -->
                    <div class="corpo">
                        <draggable v-model="dados_response.conteudo" @end="endDrag" :move="checkMove">
                            <div v-for="item in dados_response.conteudo" :key="item.id" >

                                <!-- INICIO DO BLOCO DE LINHA -->
                                <div v-if="(item.nome == 'LINHA')">
                                    <div v-if="edit">
                                        <div class="col-lg-11 col-xs-9" style="padding: 15px 0;cursor: move;">
                                            <div class="linha"></div>
                                        </div>
                                        <div class="col-lg-1 col-xs-3" style="padding: 25px 0; margin-top: -20px;">
                                            <div class="col-xs-9 no-padding"></div>
                                            <div class="col-xs-3 no-padding" style="text-align: right;">
                                                <vc-modal-link icone="fa-minus-circle" :modal="'modal-confirm'+item.id" layout="" v-if="edit" title="Remover linha"></vc-modal-link>
                                                <vc-modal-confirm :modalId="item.id" :tituloModal="msgModalConfirm+item.nome+'?'" :ajax="getUrlConteudo()" :refresh="refreshModalConfirm(publicacao_id)"></vc-modal-confirm>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12" style="padding: 15px 0;" v-else>
                                        <div class="linha"></div>
                                    </div>
                                </div>
                                <!-- FIM DO BLOCO DE LINHA -->

                                <!-- INICIO DO BLOCO DE TEXTO -->
                                <div v-if="(item.nome == 'TEXTO')" >
                                    <div v-if="edit">
                                        <div class="col-lg-11 col-xs-9 conteudo-texto" style="padding: 15px 0; cursor: move;" v-html="item.conteudo ? item.conteudo : 'NENHUM TEXTO'"></div>
                                        <div class="col-lg-1 col-xs-3" style="padding: 15px 0; margin-top: -10px;">
                                            <div class="col-xs-6"></div>
                                            <div class="col-xs-3 no-padding" style="text-align: right;">
                                                 <vc-modal-link icone="fa-pencil-square-o" :modal="'texto-modal'+item.id" layout="" v-if="edit" title="Editar conteúdo"></vc-modal-link>     
                                            </div>
                                            <div class="col-xs-3 no-padding" style="text-align: right;">
                                                <vc-modal-link icone="fa-minus-circle" :modal="'modal-confirm'+item.id" layout="" v-if="edit" title="Remover conteúdo"></vc-modal-link>
                                                <vc-modal-confirm :modalId="item.id" :tituloModal="msgModalConfirm+item.nome+'?'" :ajax="getUrlConteudo()" :refresh="refreshModalConfirm(publicacao_id)"></vc-modal-confirm>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 conteudo-texto" :style="item.conteudo ? 'padding: 15px 0;' : '' " v-else v-html="item.conteudo"></div>
                                    <texto-modal :conteudoId="item.id" :conteudo="item.conteudo" :conteudoUrl="request"></texto-modal>
                                </div>
                                <!-- FIM DO BLOCO DE TEXTO -->

                                <!-- INICIO DO BLOCO DE IMAGEM -->
                                <div v-if="(item.nome == 'IMAGEM')">
                                    <div v-if="edit">
                                        <div v-if="item.dados" class="col-lg-11 col-xs-9" style="padding: 15px 0;cursor: move; ">
                                            <img v-if="item.dados" :src="getUrlImagemMiniatura() + item.id" @click="$modal.show('show-imagem-modal'+item.id)" class="img-responsive img-cursos-pointer">
                                        </div>
                                        <div v-else class="col-lg-11 col-xs-9" style="padding: 15px 0;cursor: move;">
                                            <p> NENHUMA IMAGEM</p>
                                        </div>
                                        <div class="col-lg-1 col-xs-3" style="padding: 25px 0; margin-top: -10px;">
                                            <div class="col-xs-6"></div>
                                            <div class="col-xs-3 no-padding" style="text-align: right;">
                                                 <vc-modal-link icone="fa-pencil-square-o" :modal="'imagem-modal'+item.id" layout="" v-if="edit" title="Editar imagem"></vc-modal-link>          
                                            </div>
                                            <div class="col-xs-3 no-padding" style="text-align: right;">
                                                <vc-modal-link icone="fa-minus-circle" :modal="'modal-confirm'+item.id" layout="" v-if="edit" title="Remover imagem"></vc-modal-link>
                                                <vc-modal-confirm :modalId="item.id" :tituloModal="msgModalConfirm+item.nome+'?'" :ajax="getUrlConteudo()" :refresh="refreshModalConfirm(publicacao_id)"></vc-modal-confirm>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12" :style="item.dados ? 'padding: 15px 0;' : '' " v-else>
                                        <img v-if="item.dados" :src="getUrlImagemMiniatura() + item.id" @click="$modal.show('show-imagem-modal'+item.id)" class="img-responsive img-cursos-pointer">
                                    </div>
                                    <span v-if="item.dados">
                                        <show-imagem-modal :conteudoId="item.id" :imagemWidth="item.width" :imagemHeight="item.height"></show-imagem-modal>
                                    </span>
                                    <imagem-modal :conteudoId="item.id" class="fixo index" :conteudoUrl="request"></imagem-modal>
                                </div>
                                <!-- FIM DO BLOCO DE IMAGEM -->

                                <!-- INICIO DO BLOCO DE IMAGEM COM LINK -->
                                <div v-if="(item.nome == 'IMAGEM COM LINK')">
                                    <div v-if="edit">
                                        <div v-if="JSON.parse(item.dados)" class="col-lg-11 col-xs-9" style="padding: 15px 0;cursor: move;">
                                            <a :href="item.adicional" target="_blank" >
                                                <img v-if="item.dados" :src="getUrlImagemOriginal() + item.id" @click="$modal.show('show-imagem-modal'+item.id)" class="img-responsive img-cursos-pointer">
                                            </a>
                                        </div>
                                        <div v-else class="col-lg-11 col-xs-9" style="padding: 15px 0;cursor: move;">
                                            <p> NENHUMA IMAGEM COM LINK</p>
                                        </div>
                                        <div class="col-lg-1 col-xs-3" style="padding: 25px 0; margin-top: -10px;">
                                            <div class="col-xs-6"></div>
                                            <div class="col-xs-3 no-padding" style="text-align: right;">
                                              <vc-modal-link icone="fa-pencil-square-o" :modal="'imagem-link-modal'+item.id" :parametros="item" layout="" v-if="edit" title="Editar imagem"></vc-modal-link>          
                                            </div>
                                            <div class="col-xs-3 no-padding" style="text-align: right;">
                                                <vc-modal-link icone="fa-minus-circle" :modal="'modal-confirm'+item.id" layout="" v-if="edit" title="Remover imagem"></vc-modal-link>
                                                <vc-modal-confirm :modalId="item.id" :tituloModal="msgModalConfirm+item.nome+'?'" :ajax="getUrlConteudo()" :refresh="refreshModalConfirm(publicacao_id)"></vc-modal-confirm>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12" :style="item.dados ? 'padding: 15px 0;' : '' " v-else>
                                        <span v-if="item.dados">
                                            <a :href="item.adicional" target="_blank" >
                                                <img v-if="item.dados" :src="getUrlImagemOriginal() + item.id" @click="$modal.show('show-imagem-modal'+item.id)" class="img-responsive img-cursos-pointer">
                                            </a>
                                        </span>
                                    </div>
                                    <imagem-link-modal :conteudoId="item.id" :link="item.adicional" class="fixo index" :conteudoUrl="request"></imagem-link-modal>
                                </div>
                                <!-- FIM DO BLOCO DE IMAGEM COM LINK -->

                            </div>  
                        </draggable>
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
                        <vc-modal-link icone="fa-pencil-square-o" modal="colaboradores-modal" layout="pull-right" v-if="edit" title="Editar colaboradores" ></vc-modal-link>
                    </div>

                     <!-- PUBLICAÇOES RELACIONADAS -->
                    <div class="relacionadas" v-if="relacionadosExibir">
                        <div class="col-xs-12" style="padding: 10px 0 10px 0;">
                            <div class="linha"></div>
                        </div>
                        <div class="col-xs-12">
                            <b>OUTRAS PUBLICAÇÕES RELACIONADAS:</b>
                        </div>
                        <div class="col-md-12">
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
                            <div v-if="publicacaoDatas.dt_desativacao"><b>Data de desativação:</b> <span v-html="publicacaoDatas.dt_desativacao"></span></div>
                        </div>
                        <div class="col-xs-3 text-right">
                            <div><b>{{publicacaoVisualizacoes}} visualizações</b></div>
                        </div>
                    </div>

                </div>

                <!-- BOTÕES -->
                <div class="col-xs-12 botoes" v-if="edit">
                    <div v-if="request == 'rascunho'">
                        <div v-if="existe_rascunho == 'false'">
                            <a href="#" @click="setRascunho()">
                                <button type="button" class="btn btn-primary">Criar rascunho da publicação</button>
                            </a>
                        </div>
                        <div v-else-if="existe_rascunho == 'true'">
                            <div class="btn-group" v-if="conteudo_tipo">
                                <button type="button" class="btn btn-primary">Adicionar conteúdo</button>
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li v-for="item in conteudo_tipo">
                                        <a href="#" @click="setConteudoTipo(item.id)">{{item.nome}}</a>
                                    </li>
                                </ul>
                            </div>
                            <a :href="'/base-de-conhecimento/publicacoes/'+publicacao_id">
                                <button class="btn btn-primary" >Finalizar edição do rascunho</button>
                            </a>
                            <vc-modal-button texto="Converter rascunho em publicação" btnClass="btn btn-primary" modal="rascunho-modal-confirm-confirmRascunho"></vc-modal-button>
                            <rascunho-modal-confirm :publicacaoId="publicacao_id" :tipo="'confirmRascunho'"></rascunho-modal-confirm>
                            
                            <vc-modal-button texto="Excluir rascunho" btnClass="btn btn-primary" modal="rascunho-modal-confirm-deleteRascunho"></vc-modal-button>
                            <rascunho-modal-confirm :publicacaoId="publicacao_id" :tipo="'deleteRascunho'"></rascunho-modal-confirm>
                        </div>
                    </div>
                    <div v-if="request == 'edit'">
                        <div class="btn-group" v-if="conteudo_tipo">
                            <button type="button" class="btn btn-primary">Adicionar conteúdo</button>
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span>
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li v-for="item in conteudo_tipo">
                                    <a href="#" @click="setConteudoTipo(item.id)">{{item.nome}}</a>
                                </li>
                            </ul>
                        </div>
                        <a :href="'/base-de-conhecimento/publicacoes/'+publicacao_id">
                            <button class="btn btn-primary" >Finalizar edição da publicação</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <vc-modal-confirm-acessa-rascunho :id="publicacao_id" :titulo="'Existe um rascunho criado para a publicação, deseja acessar o rascunho?'"></vc-modal-confirm-acessa-rascunho>
    </div>
</template>

<script>
// https://github.com/SortableJS/Vue.Draggable
import draggable from 'vuedraggable'

//Documentacao do VUE-SLICK
//https://github.com/staskjs/vue-slick
import Slick from 'vue-slick'

      export default {
        components: {
            draggable,
            Slick
        },
        props:['publicacao_id', 'edit', 'request', 'existe_rascunho'],
        data(){
            return {                
                dados_response:[],
                conteudo_tipo:[],  
                can:[],
                carregando: true,
                carregandoConteudo: true,
                conteudoID:'',
                msgModalConfirm:'Confirmar a exclusão desse conteúdo do tipo ',
                item:{
                    conteudo: '',
                },
                object:[],
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
                }
            }
        },
        methods: {
            get(){
                window.axios.get(this.getUrl()).then((response) =>{
                    this.object = response.data.publicacao;
                    this.publicacaoDatas = response.data.datas;
                    this.publicacaoVisualizacoes = response.data.visualizacoes;
                    this.can = response.data.can;                    
                    this.relacionadas = response.data.relacionadas;
                    this.carregando = false;
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
                getUrlImagemPublicacao(){
                return '/base-de-conhecimento/publicacoes/img-publicacao/';
            },  
            getUrlImagemOriginal(){
                return '/base-de-conhecimento/publicacoes/img-conteudo-original/';
            },
            exibir( lista ){
                if( this.edit )
                {
                    return true;
                }
                else
                {
                    if(lista == undefined || lista.length == 0){
                        return false;
                    }
                    return true;
                }
            },
            getStatusEdicao(){
                if( this.$cookie.get('editar_publicacao') == 'true' )
                    this.edicao = true;
                else
                    this.edicao = false;
            },
            setConteudoTipo( conteudo_tipo_id ){
                if( conteudo_tipo_id != '' && this.publicacao_id ){
                    var item = {};
                    item.publicacao_id = this.publicacao_id;
                    item.conteudo_tipo_id = conteudo_tipo_id;
                    item.rascunho = (this.request == 'rascunho' ? true : false);
                    window.axios.post( this.getUrlConteudo()+'conteudo_tipo', item)
                        .then(response=>{
                            window.location.href = '/base-de-conhecimento/publicacoes/'+this.publicacao_id+'/'+this.request;
                            return 0;
                        })
                        .catch(error => {
                            this.errors = error.response.data.errors;
                            return 0; 
                        });
                }
            },
            setRascunho(){
                if( this.publicacao_id )
                {
                    var item = {};
                    item.publicacao_id = this.publicacao_id;
                    window.axios.post( '/base-de-conhecimento/publicacoes/'+this.publicacao_id+'/rascunho/novo', item)
                        .then(response=>{
                            window.location.href = '/base-de-conhecimento/publicacoes/'+this.publicacao_id+'/rascunho';
                            return 0;
                        })
                        .catch(error => {
                            this.errors = error.response.data.errors;
                            return 0; 
                        });
                }
            },
            getConteudoRascunho(){
                if( this.publicacao_id )
                {
                    var item = {};
                    item.publicacao_id = this.publicacao_id;
                    window.axios.post( this.getUrlConteudo()+this.publicacao_id+'/rascunho', item)
                        .then(response=>{
                            this.dados_response = response.data.conteudo;  
                            this.conteudo_tipo = response.data.conteudo_tipo;
                            this.carregandoConteudo = false;
                        })
                        .catch(error => {
                            this.errors = error.response.data.errors;
                            return 0; 
                        });
                }
            },
            refreshModalConfirm(publicacao_id){
                return '/base-de-conhecimento/publicacoes/'+publicacao_id+'/'+this.request;
            },
            endDrag () {
                this.enviaOrdem();
            },
            enviaOrdem () {
                this.item.conteudo = this.dados_response.conteudo;
                window.axios.put('/base-de-conhecimento/conteudo/ordem', this.item).then(response=>{
                   return 0;
                })
                .catch(error => {
                    return 0; 
                });
            },
            checkMove(){
                if(this.edit){
                    return true
                }
                return false
            },
            alertEdicaoConteudo(){
                if( this.existe_rascunho == 'true' )           {
                    this.$modal.show('modal-confirm-acessa-rascunho')
                }else{
                    window.location.href = '/base-de-conhecimento/publicacoes/'+this.publicacao_id+'/edit';
                }                
            }
        },
        computed: {
            tagExibir(){
                return this.exibir( this.dados_response.tags );
            },
            colaboradoresExibir(){
                return this.exibir( this.dados_response.colaboradores );
            },
            relacionadosExibir(){
                return this.request != 'rascunho' && (this.relacionadas != undefined || this.relacionadas.length != 0);
            },
        },
        mounted(){
            this.get();

            if( this.request == 'rascunho' )
            {
                if( this.existe_rascunho == 'true' )
                {
                    this.getConteudoRascunho();
                }
                else if( this.existe_rascunho == 'false' )
                {
                    this.carregandoConteudo = false;
                }
            }
            else if( this.request == 'edit' )
            {
                this.getConteudo();                
                if( this.existe_rascunho == 'true' ){
                    this.$modal.show('modal-confirm-acessa-rascunho')
                }
            }
            else
            {
                this.request = 'edit';
                this.getConteudo();
            }

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
    .img-cursos-pointer{
        cursor: pointer;
    }
    .conteudo-texto p{
        margin-bottom: 2px;
    }
    .conteudo-texto img{
        max-width: 100%;
        height: auto;
        display: block;
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
    
    .ql-font-monospace {
        font-family: Monaco,Courier New,monospace;
    }

</style>