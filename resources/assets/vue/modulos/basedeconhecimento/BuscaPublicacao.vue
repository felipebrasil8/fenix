<template>   
	<div>
		<div class="box box-warning" style="border-top-color:#ed7d31;min-height: 300px;" v-if="carregando">
            <div class="overlay">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
        </div>	
		<div class="box box-warning" style="border-top-color: #ed7d31" v-else>
			<div class="box-header with-border">
				<span class="pull-right" v-if="publicacoes.length > 1">
					{{ pagination.total }} resultados					
				</span>
				<span class="pull-right" v-if="publicacoes.length > 0 && publicacoes.length < 2">
					{{ pagination.total }} resultado					
				</span>
				<h3 class="box-title">RESULTADO DA PESQUISA</h3>
		    </div> 
			<div v-if="publicacoes.length > 0">
				<div class="box-body" > 
			    	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 margin-top" style="width:290px;" v-for="object in publicacoes">
			    		<vc-componente-publicacao :object="object"></vc-componente-publicacao>
					</div>
				</div>				
        		
        		
        		<div class="box-body"> 
			 		<div class="col-xs-12 text-center">
			 			<vc-pagination :source="pagination" @navigate="navigate"></vc-pagination>

			 		</div>
				</div>
				
			</div>
			<div class="box-body" v-else> 
		 		<div class="col-xs-12 text-center">
		 		Nenhum resultado para sua pesquisa.
		 		</div>
			</div>

			<div class="overlay" v-show="paginando">
                <i class="fa fa-refresh fa-spin"></i>
            </div>
		</div>
	</div>
</template>
<script>

	 
	export default {
	
	 	props:['busca'],
	 	name: 'app',
	 	data(){
            return {
                titulo:'CATEGORIA',
                publicacoes:[],                
                object:[],
                can:[],
                item:{
                	busca: '',
                },
				carregando: true,
				pagination: {},
				paginando: false
            }
        },
        methods: {
        	get(){
        		this.ajaxGetBusca();
        	},         	
            getStatusEdicao(){
                if( this.$cookie.get('editar_publicacao') == 'true' )
                    this.edicao = true;
                else
                    this.edicao = false;
            },
            ajaxGetBusca(){
            	this.paginando = true

				this.item.busca = this.busca;
				window.axios.post( '/base-de-conhecimento/publicacoes/busca_ajax?page='+this.getCookiePaginaBusca( 1 ), this.item )
                .then(response=>{

                	this.publicacoes = response.data.publicacoes.data;
                    this.pagination = response.data.publicacoes;

            		this.paginando = false;
            		this.carregando = false;

                    return 0;
         		})
                .catch(error => {
                    this.errors = error.response.data.errors;
                    return 0; 
                });
     
            },
            
            navigate( page ){
            	this.paginando = true
            	// this.carregando = true
            	this.item.busca = this.busca;
            	this.setCookiePaginaBusca( page );
				window.axios.post( '/base-de-conhecimento/publicacoes/busca_ajax?page='+this.getCookiePaginaBusca( page ), this.item )
                .then(response=>{

                	this.publicacoes = response.data.publicacoes.data;
                    this.pagination = response.data.publicacoes;
					
					this.paginando = false
					this.carregando = false
                    return 0;
         		})
                .catch(error => {
                    this.errors = error.response.data.errors;
                    return 0; 
                });
            },
            setCookiePaginaBusca( page ){
                
                this.$cookie.set('page_busca', ''+page+'', 1);
                this.$cookie.set('valor_busca', ''+this.busca+'', 1);
                
            },
            getCookiePaginaBusca( page ){
                if( this.$cookie.get('page_busca') != '' && this.$cookie.get('valor_busca') == this.busca)
                	return this.$cookie.get('page_busca')
                else {
                    return page;
                }
                    
            },           
        },     
        mounted(){
         this.get();
         this.getStatusEdicao()
        }
	};
</script>
<style>
	a.href_no_color{
	   	color: #333;
    	text-decoration: none;
	}
	a.href_no_color:hover{
	   	color: #000;
    	text-decoration: none;
	}
	.publicacao-conteiner{
		min-height: 200px;    
	    background-color: #f1f1f1;
	}
	.time{
		color: #777;
    	padding: 10px 0 7px 0;
    	font-size: 13px;
	}
	.text-center .img-responsive{
	    margin: 0 auto;
	    padding: 3px;
	    max-height: 165px;
	}
	.text-titulo-publicacao{
		font-size: 16px; 
		font-weight: bold;
		max-height: 65px;
	}
	.margin-top{
	    margin-top: 7px;
	}
	.text-resumo-publicacao{
		height: 60px;
	}
	.truncate{
		white-space: unset !important;
	}
	.altura_publicacao{
		height: 140px;
		padding: 0px;
		margin: 0px;
	}
</style>
