<template>


<div class="col-lg-3 col-md-12" id="mensagens_filtros_menu">
    <div :class="{ 'affix fixo_mensagens_filtros': isActive }">
        <div :class="{ 'row': isActive }">
            <div :class="{ 'col-md-12': isActive }">

                <div class="overlay" v-if="carregando">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
                <div :class="{ 'ajusteChome' : isScrollActive }" v-else>
                    <div class="box box-warning" style="border-top-color: #ed7d31">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-search"></i> Filtrar Resultado</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label >De:</label>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="input-group date">
                                            <date-picker v-model="pesquisa.data_de" class="input-sm" :config="config" name="data_de" id="dt_publicacao_de" readonly></date-picker>
                                            <label class="input-group-addon" for="dt_publicacao_de">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label >Até:</label>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="input-group date">
                                            <date-picker v-model="pesquisa.data_ate" class="input-sm" :config="config" name="data_ate" id="dt_publicacao_ate" readonly></date-picker>
                                            <label class="input-group-addon" for="dt_publicacao_ate">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label >Funcionário:</label>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <select class="form-control input-sm" v-model="pesquisa.funcionarios">
                                            <option value=""></option>                           
                                            <option v-for="item in funcionariosObj" :value="item.id">{{item.nome}}</option>                           
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label >Publicação:</label>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <input type="text" id="" class="form-control input-sm" v-model="pesquisa.publicacao">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label >Mensagem:</label>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <input type="text" id="" class="form-control input-sm" v-model="pesquisa.mensagem">
                                    </div>
                                </div>
                            </div>
							
							<div class="form-group">
                                <label >Respondida:</label>
                                <div class="row">
                                    <div class="col-lg-12">
		                                <label>
	                                        <input type="radio" v-model="pesquisa.respondida" value="true"> Sim
	                                    </label>
	                                    &nbsp;
	                                    <label>
	                                        <input type="radio" v-model="pesquisa.respondida" value="false"> Não
	                                    </label>
	                                     &nbsp;
	                                    <label>
	                                        <input type="radio" v-model="pesquisa.respondida" value="ambos"> Ambos
	                                    </label>
                                    </div>
                                </div>
                            </div>



                        </div>
                        <div class="box-footer">
                            <button class="btn btn-primary" @click="pesquisar($event)" type="button">Pesquisar</button>
                            &nbsp;
                            <button class="btn btn-default" @click="limpar($event)" type="button">Limpar</button> 
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

</template>

<script>
    import 'eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css'; 
    export default {
        props:['funcionarios', 'id_mensagem', 'teste'],
        data(){
            return {
                dados_response:[],
                funcionariosObj: {},
                pesquisa: {
                    funcionarios: [],
                    data_de: '',
                    data_ate: '',
                    mensagem: '',
                    publicacao: '',
                    respondida: 'false'
                },
                carregando: true,

                // Configuração do campo de data
                config: {
                    format: 'DD/MM/YYYY',
                    useCurrent: false,                    
                    showClose: true,
                    locale: 'pt-br',
                    ignoreReadonly: true
                },
                topPos: '',
                laguraTotal: '',
                isActive: false,
                isScrollActive: true,
                alturaTotal:'',
            }
        },
        methods:{
            limpar( ev ){
                this.limparCampos();

                ev.preventDefault();
                this.$emit('limpar');
            },
            pesquisar( ev )
            {
                this.setCookies();
                ev.preventDefault();
                this.$emit('pesquisar');
            },
            limparCampos()
            {
                this.$cookie.remove('base_mensagem_data_de');
                this.$cookie.remove('base_mensagem_data_ate');
                this.$cookie.remove('base_mensagem_funcionario');
                this.$cookie.remove('base_mensagem_publicacao');
                this.$cookie.remove('base_mensagem_mensagem');
                this.pesquisa.funcionarios = '';
                this.pesquisa.publicacao = '';
                this.pesquisa.mensagem = '';
                this.pesquisa.respondida = 'false';
                this.dataDefault();
                this.setCookies();
            },
            dataDefault()
            {
                this.hoje = new Date();
                this.pesquisa.data_ate = this.hoje;
                this.pesquisa.data_de = new Date(this.hoje.getFullYear() ,  eval(this.hoje.getMonth()-1), this.hoje.getDate());
            },
            setCookies()
            {
                if( this.pesquisa.data_de != '' )
                {
                    var de;

                    if( this.pesquisa.data_de instanceof Date )
                    {
                        de = new Date( ''+this.pesquisa.data_de );
                    }
                    else
                    {
                        de = new Date( ''+this.pesquisa.data_de.split("/").reverse().join("/") );
                    }

                    this.$cookie.set('base_mensagem_data_de', ''+de.getDate()+'/'+(de.getMonth()+1)+'/'+de.getFullYear());
                }

                if( this.pesquisa.data_ate != '' )
                {
                    var ate;

                    if( this.pesquisa.data_ate instanceof Date )
                    {
                        ate = new Date( ''+this.pesquisa.data_ate );
                    }
                    else
                    {
                        ate = new Date( ''+this.pesquisa.data_ate.split("/").reverse().join("/") );
                    }

                    this.$cookie.set('base_mensagem_data_ate', ''+ate.getDate()+'/'+(ate.getMonth()+1)+'/'+ate.getFullYear());
                }

                this.$cookie.set('base_mensagem_funcionario', ''+this.pesquisa.funcionarios);
                this.$cookie.set('base_mensagem_publicacao', ''+this.pesquisa.publicacao);
                this.$cookie.set('base_mensagem_mensagem', ''+this.pesquisa.mensagem);
                this.$cookie.set('base_mensagem_respondida', ''+this.pesquisa.respondida);
            },
            getCookies()
            {
                if( this.$cookie.get('base_mensagem_data_de') )
                {
                    this.pesquisa.data_de = this.$cookie.get('base_mensagem_data_de');
                }
                if( this.$cookie.get('base_mensagem_data_ate') )
                {
                    this.pesquisa.data_ate = this.$cookie.get('base_mensagem_data_ate');
                }
                if( this.$cookie.get('base_mensagem_funcionario') )
                {
                    this.pesquisa.funcionarios = this.$cookie.get('base_mensagem_funcionario');
                }
                if( this.$cookie.get('base_mensagem_publicacao') )
                {
                    this.pesquisa.publicacao = this.$cookie.get('base_mensagem_publicacao');
                }
                if( this.$cookie.get('base_mensagem_mensagem') )
                {
                    this.pesquisa.mensagem = this.$cookie.get('base_mensagem_mensagem');
                }
				if( this.$cookie.get('base_mensagem_respondida'))
                {
                    this.pesquisa.respondida = this.$cookie.get('base_mensagem_respondida');
                }

            },
            fixaMenu(){
                var laguraTotal = $(window).width()   // largura do browser
                var topPos = $(this).scrollTop() 
                var alturaTotal = $(window).height();   // largura do browser
                var laguraObjeto = $('#mensagens_filtros_menu').width();   
    
                if(laguraTotal > 920)
                {
                    $('.ajusteChome').css('width',''+(laguraObjeto)+'px');
                    
                    if ( topPos > 32  )
                    {
                        this.isActive = true
                        
                    } else 
                    {
                        this.isActive = false
                    }
                            
                }else
                {
                    this.isScrollActive = false
                }  
            },
        },
        mounted(){
            this.dataDefault();
            this.getCookies();
            this.setCookies();
            this.funcionariosObj = JSON.parse(this.funcionarios);
            this.fixaMenu();
            this.carregando = false;
           	
            if( this.id_mensagem != 0 )
            {
                this.limparCampos();
            }

            var self = this

            $(window).scroll(function() {
                var laguraTotal = $(window).width();   // largura do browser
                var topPos = $(this).scrollTop(); 
                var alturaTotal = $(window).height();   // largura do browser
                var laguraObjeto = $('#mensagens_filtros_menu').width();   
                    
                if(laguraTotal > 920){
                    $('.ajusteChome').css('width',''+(laguraObjeto)+'px');
                    
                    if ( topPos > 32  ){
                        self.isActive = true
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
                var laguraObjeto = $('#mensagens_filtros_menu').width();   

                if(laguraTotal > 920){
                    $('.ajusteChome').css('width',''+(laguraObjeto)+'px');

                    
                    if ( topPos > 32  ){
                        self.isActive = true
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
    .fixo_mensagens_filtros{
        top : 65px;
    }
</style>
