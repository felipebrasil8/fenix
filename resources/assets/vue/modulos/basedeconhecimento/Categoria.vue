<template>
    <div id="categoriaInit" :class="{ 'affix fixo_menu_categoria': isActive }">
        <div :class="{ 'row': isActive }">
            <div :class="{ 'col-md-12': isActive }">
                <div class="overlay" v-if="carregando">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
                <VuePerfectScrollbar :class="{ 'scroll-area ajusteChome' : isScrollActive }" v-once :settings="settings" :swicher="isScrollActive" v-else>
                    <div style="padding-right: 4%;" >
                        
                        <div id="custom-search-input" class="box busca_div box-warning" style="border-top-color: #ed7d31">
                           <form method="head" v-on:submit.prevent="pesquisa">
                                <div class="input-group busca">
                                <input type="text" v-model="busca" maxlength="100" class="form-control" placeholder="Pesquise...">
                                    <span class="input-group-btn">
                                        <button type="submit" id="search-btn" style="background-color: #fff;border: 1px solid #0000002e;border-left: none;" class="btn btn-flat">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </form>
                        </div>
                        <div class="box box-solid categoria">
                            <div class="box-body no-padding">
                                <ul class="nav nav-pills nav-stacked" id="accordion1" >
                                    <li v-for="pai in dados_response" 
                                        :class="[ pai.id == categoria_id  ? 'active': '']">
                                        <a v-if="pai.filho.length == 0" :href="url+pai.id" data-parent="#accordion1">
                                            <i class="fa" v-bind:class="'fa-'+pai.icone"></i>
                                            {{pai.nome}}
                                            <vc-modal-link v-if="edicaoCategoria" :modal="'categoria-modal'" layout="pull-right" icone="fa-pencil-square-o" @eventoclick="abreModalCategoria(pai)"></vc-modal-link>
                                        </a>
                                        <span v-else :href="'#'+pai.id" data-toggle="collapse" data-parent="#accordion1">
                                            <i class="fa" v-bind:class="'fa-'+pai.icone" style="margin-right: 5px;"></i>
                                            {{pai.nome}}
                                            <vc-modal-link v-if="edicaoCategoria" :modal="'categoria-modal'" layout="pull-right" icone="fa-pencil-square-o" @eventoclick="abreModalCategoria(pai)"></vc-modal-link>
                                        </span>

                                        <ul class="nav nav-pills nav-stacked collapse" :class="verificaFilho(pai, categoria_id)"
                                            v-if="pai.filho.length != ''" 
                                            :id="pai.id">
                                            <li v-for="filho in pai.filho" 
                                                style=" border-bottom: 1px #f4f4f4 !important; "
                                                v-if="filho.publicacao_categoria_id != null" 
                                                :class="[ filho.id == categoria_id  ? 'active': ''] " >
                                                <a href="" v-bind:href="url+filho.id" style="padding-left: 30px;">
                                                    <i class="fa" v-bind:class="'fa-'+filho.icone"></i>
                                                    {{filho.nome}}
                                                    <vc-modal-link v-if="edicaoCategoria" :modal="'categoria-modal'" layout="pull-right" icone="fa-pencil-square-o" @eventoclick="abreModalCategoria(filho)"></vc-modal-link>
                                                </a> 
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>  
                        <div class="box box-solid categoria">
                            <div class="box-body no-padding">
                                <ul class="nav nav-pills nav-stacked">
                                    <li :class="[ novas_publicacoes ? 'active': '']">
                                        <a style="color: #ed7d31" href="/base-de-conhecimento/publicacoes/novas">
                                            <i class="fa fa-plus"></i>
                                            <b>NOVAS PUBLICAÇÕES</b>
                                        </a>                        
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="box box-solid categoria">
                            <div class="box-body no-padding">
                                <ul class="nav nav-pills nav-stacked">
                                    <li :class="[ favoritos ? 'active': '']">
                                        <a style="color: #ed7d31" href="/base-de-conhecimento/publicacoes/favoritos">
                                            <i class="fa fa-star"></i>
                                            <b>MEUS FAVORITOS</b>
                                        </a>                        
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="box box-solid categoria" v-if="edicao">
                            <div class="box-body no-padding">
                                <ul class="nav nav-pills nav-stacked">
                                    <li :class="[ proximas_publicacoes ? 'active': '']">
                                        <a style="color: #ed7d31" href="/base-de-conhecimento/publicacoes/proximas">
                                            <i class="fa fa-pencil"></i>
                                            <b>PRÓXIMAS PUBLICAÇÕES</b>
                                        </a>                        
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div v-if="permissao_botao == 'true'">
                            <div v-if="edicao">
                                <div class="row">
                                    <div class="col-md-12" style="margin-bottom: 20px;" v-if="permissao_categoria.categoria_cadastrar || permissao_categoria.categoria_editar">
                                        <div v-if="edicaoCategoria" class="btn-group">
                                            <button type="button" class="btn btn-danger" @click="setCategoriaEditar(false)">
                                                Cancelar
                                            </button>
                                        </div>
                                        <div v-else class="btn-group">
                                            <vc-modal-button texto="Adicionar" btn-class="btn btn-primary" v-if="permissao_categoria.categoria_cadastrar" icone="fa-plus" @eventoclick="abreModalCategoria(0)"></vc-modal-button>
                                            <button type="button" class="btn btn-primary" v-if="permissao_categoria.categoria_editar" @click="setCategoriaEditar(true)">
                                                <i class="fa fa-pencil-square-o"></i>
                                                &nbsp;Editar
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <a class="btn btn-danger" href="#" v-on:click="setStatusEdicao()">
                                            <i class="fa fa-pencil-square-o"></i>
                                            &nbsp;Finalizar edição
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <a class="btn btn-primary" href="#" v-on:click="setStatusEdicao()">
                                    <i class="fa fa-pencil-square-o"></i>
                                    &nbsp;Habilitar edição
                                </a>
                            </div>
                        </div>
                    </div>
                </VuePerfectScrollbar>
            </div>
        </div>
    </div> 
</template>
<script>
    //https://www.npmjs.com/package/vue-perfect-scrollbar
    import VuePerfectScrollbar from 'vue-perfect-scrollbar';

    export default {
        components: { 
            VuePerfectScrollbar
        },
        props:['categoria_id', 'permissao_botao', 'busca_usr', 'novas_publicacoes', 'favoritos', 'can_categoria', 'proximas_publicacoes'],
        data(){
            return {
                dados_response:[],
                pai:[], 
                filho:[], 
                url: '/base-de-conhecimento/publicacoes/categoria/',
                edicao: false,
                busca:'',
                busca_final:'',
                carregando: true,
                topPos: '',
                laguraTotal: '',
                isActive: false,
                isScrollActive: true,
                alturaTotal:'',
                
                settings: {
                    maxScrollbarLength: 60
                },

                permissao_categoria: {},
                edicaoCategoria: false,
            }
        },
        methods:{
            
            get(){
                window.axios.get(this.getUrl()).then((response) =>
                {
                    this.dados_response = response.data;
                    this.carregando = false;
                    this.fixaMenuCategoria()
                });
            },
           
            getUrl(){
                return '/base-de-conhecimento/categoria';
            },

            fixaMenuCategoria(){
                var laguraTotal = $(window).width()   // largura do browser
                var topPos = $(this).scrollTop() 
                var alturaTotal = $(window).height();   // largura do browser
                var laguraObjeto = $('#categoria_menu').width();   

                
                if(laguraTotal > 920)
                {
                    $('.scroll-area').css('height',''+(alturaTotal-125)+'px');
                    $('.ajusteChome').css('width',''+(laguraObjeto)+'px');
                    
                    if ( topPos > 32  )
                    {
                        this.isActive = true
                         // $('.scroll-area').css('height',''+(alturaTotal-80)+'px')
                        
                    } else 
                    {
                        this.isActive = false
                    }
                            
                }
                else
                {
                    this.isScrollActive = false
                }  
            },
           
            verificaFilho( pai, categoria_id )
            {
                for( var key in pai.filho )
                {
                    if( pai.filho[key].id == categoria_id )
                    {
                        return 'in';
                    }
                }
            },
            setStatusEdicao(){
                this.edicao = !this.edicao;
                this.$cookie.set('editar_publicacao', ''+this.edicao+'', 1);
                if( this.edicao == false )
                {
                    this.$cookie.set('editar_categoria', 'false', 1);
                }
                window.location.reload();
            },
            getStatusEdicao(){
                if( this.$cookie.get('editar_publicacao') == 'true' )
                {
                    this.edicao = true;
                    if(this.$cookie.get('editar_categoria') == 'true')
                    {
                        this.edicaoCategoria = true;
                    }
                }
                else
                {
                    this.edicao = false;
                    this.edicaoCategoria = false;
                }
            },
            pesquisa(){
                if (this.busca != '' ){
                    if (this.busca.indexOf("#") == 0){
                        this.busca_final = this.busca.replace("#", "")
                        window.location.href = "/base-de-conhecimento/publicacoes/tag/"+this.busca_final;
                    }else if (this.busca.indexOf("@") == 0){
                        this.busca_final = this.busca.replace("@", "")
                        window.location.href = "/base-de-conhecimento/publicacoes/colaborador/"+this.busca_final;
                    }else{
                        this.busca_final = this.busca;
                        window.location.href = "/base-de-conhecimento/publicacoes/busca/"+this.busca_final;
                    }
                }
                return false;
            },
            setCookieSemPermissao(){
                if( this.permissao_botao != 'true' ){
                    this.$cookie.set('editar_publicacao', 'false', 1);
                    this.$cookie.set('editar_categoria', 'false', 1);
                }
                else
                {
                    if( this.permissao_categoria.categoria_editar != true )
                    {
                        this.$cookie.set('editar_categoria', 'false', 1);
                    }
                }
            },
            setCategoriaEditar( edit ){
                if( edit )
                {
                    this.$cookie.set('editar_categoria', 'true', 1);
                }
                else
                {
                    this.$cookie.set('editar_categoria', 'false', 1);
                }

                window.location.reload();
            },
            abreModalCategoria( categoria )
            {
                this.$modal.show('categoria-modal', categoria);
            },
        },
        mounted(){
            this.permissao_categoria = JSON.parse(this.can_categoria);
            this.setCookieSemPermissao()
            this.get()
            this.getStatusEdicao()
            this.busca = this.busca_usr
            this.fixaMenuCategoria()
            var self = this

            $(window).scroll(function() {
                var laguraTotal = $(window).width();   // largura do browser
                var topPos = $(this).scrollTop(); 
                var alturaTotal = $(window).height();   // largura do browser
                var laguraObjeto = $('#categoria_menu').width();   

                              
                if(laguraTotal > 920){
                    $('.scroll-area').css('height',''+(alturaTotal-125)+'px');
                    $('.ajusteChome').css('width',''+(laguraObjeto)+'px');
                    
                    if ( topPos > 32  ){
                        self.isActive = true
                         // $('.scroll-area').css('height',''+(alturaTotal-80)+'px')

                    } else {
                        self.isActive = false
                    }
                            
                }else{
                    self.isScrollActive = false
                }
                
            
            });

            $(window).resize(function() {
                var laguraTotal = $(window).width()   // largura do browser
                var topPos = $(this).scrollTop() 
                var alturaTotal = $(window).height();   // largura do browser
                var laguraObjeto = $('#categoria_menu').width();   

                
                if(laguraTotal > 920){
                    $('.scroll-area').css('height',''+(alturaTotal-125)+'px');
                    $('.ajusteChome').css('width',''+(laguraObjeto)+'px');

                    
                    if ( topPos > 32  ){
                        self.isActive = true
                         // $('.scroll-area').css('height',''+(alturaTotal-80)+'px')

                    } else {
                        self.isActive = false
                    }
                            
                }else{
                    self.isScrollActive = false
                }
                        
            });

        }
    };

</script>

<style>
    .nav-stacked>li.active>a, .nav-stacked>li.active {
       border-left-color: #ed7d31;
       background: #f7f7f7;
    }
    .nav-stacked>li.active>a, .nav-stacked>li.active>a:hover, .nav-stacked>li>span:hover, .nav-stacked>li>a:hover {
       border-left-color: #ed7d31;
       background: #f7f7f7;
    }
    .nav-stacked>li>span {
        border-radius: 0;
        border-top: 0;
        border-left: 3px solid transparent;
        color: #444;
    }
    .nav>li>span {
        position: relative;
        display: block;
        padding: 10px 15px;
        cursor: pointer;
    }
    .busca{
        padding: 10px;
    }
    .busca_div{
        background-color: #fff;
        margin-bottom: 15px;
    }

    .fixo_menu_categoria{
        top : 65px;
    }
    .scrol_externo{
        padding-right: 20px;
    }
    .scroll-area {
      position: relative;

    }

    .nav-stacked>li>a>a.pull-right{  
       color: #444;
    }
    .nav-stacked>li>span>a.pull-right{  
       color: #444;
    }
   
@-moz-document url-prefix() { 
   
}
</style>
