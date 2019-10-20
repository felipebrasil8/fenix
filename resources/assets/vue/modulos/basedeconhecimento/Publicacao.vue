<template>   
	<div>
        <div class="box box-warning" style="border-top-color: #ed7d31; min-height: 300px;" v-if="carregando">
    		<div class="overlay">
    			<i class="fa fa-refresh fa-spin"></i>
    		</div>
        </div>
		<div class="box box-warning" style="border-top-color: #ed7d31" v-else>
			<div class="box-header with-border">				
				<div v-if="(total && !edicao) || ( publicacoesNovas )">	
					<span class="pull-right" v-if="total > 1">
						{{ total }} resultados					
					</span>
					<span class="pull-right" v-if="total > 0 && total < 2">
						{{ total }} resultado					
					</span>
				</div>

                <div v-if="proximas_publicacoes">
                    <span class="pull-right" v-if="publicacoes.count > 1">
                        {{ publicacoes.count }} publicações                    
                    </span>
                    <span class="pull-right" v-else>
                        <div v-if="publicacoes.count == 1">
                            {{ publicacoes.count }} publicação                         
                        </div>                      
                    </span>
                </div>
				<div v-else>
					<span class="pull-right" v-if="edicao">
						<vc-modal-link texto="Adicionar publicação" modal="publicacao-nova-modal" icone="fa-plus-square" v-if="can.cadastrar" layout="href_no_color"></vc-modal-link>		
					</span>

					<div v-if="favoritos">
						<span class="pull-right" v-if="publicacoes.novas.length > 1">
							{{ publicacoes.novas.length }} favoritos					
						</span>
						<span class="pull-right" v-else>
							<div v-if="publicacoes.novas.length == 1">
								{{ publicacoes.novas.length }} favorito							
							</div>						
						</span>
					</div>
					
				</div> 

				<h3 class="box-title">{{ titulo }}</h3>
		    </div><!-- /.box-header -->

		    <div v-if="publicacoes.naoPublicadas">
				<div class="box-body" v-if="publicacoes.naoPublicadas.dados.length != 0"> 
					<h3>{{ publicacoes.naoPublicadas.titulo }}</h3>
			    	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 margin-top" style="width:290px;" v-for="object in publicacoes.naoPublicadas.dados">
			    		
						<vc-componente-publicacao :object="object"></vc-componente-publicacao>
			    		

	        		</div>
			    </div>
			</div>
	    			    
			<div v-if="publicacoes.publicadas">
				<div class="box-body" v-if="publicacoes.publicadas.dados.length != 0"> 
				<hr v-if="edicao && publicacoes.naoPublicadas.dados.length != 0">				
					<h3 v-if="edicao">{{ publicacoes.publicadas.titulo }}</h3>
			    	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 margin-top" style="width:290px;" v-for="object  in publicacoes.publicadas.dados">
		    			<vc-componente-publicacao :object="object"></vc-componente-publicacao>
					</div>
			    </div>				
			</div>
						
			<div v-if="publicacoes.desativadas">
				<div class="box-body" v-if="publicacoes.desativadas.dados.length != 0"> 
				<hr v-if="edicao && publicacoes.publicadas">
					<h3>{{ publicacoes.desativadas.titulo }}</h3>
			    	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 margin-top" style="width:290px;" v-for="object in publicacoes.desativadas.dados">
			    		<vc-componente-publicacao :object="object"></vc-componente-publicacao>
	        		</div>
			    </div>
			</div>	


			<div v-if="publicacoes.novas">
                <div v-if="publicacoes.novas != ''">
                    <div class="box-body" v-for="(publicacoes, index) in publicacoes.novas">
                        <h3 v-if="index == 'favoritos'" >Favoritos</h3>
                        <h3 v-else-if="index != ''" >{{ index }}</h3>
                        <h3 v-else > Não publicadas </h3>
    			    	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 margin-top" style="width:290px;" v-for="object in publicacoes">
                            <div class="publicacao-conteiner">
    							<div class="text-center">
    								<a :href="'/base-de-conhecimento/publicacoes/'+object.id">
    									<img :src="getUrlImagemPublicacao()+object.id" class="img-responsive">									
    								</a>
    							</div>
    							<div class="row">
    								<div class="col-xs-12">
    									<div class="col-xs-8">
    										<span class="pull-left time" :title="object.tooltip"><i class="fa fa-calendar" style="margin-right: 5px;"></i>{{ object.data }}</span>
    									</div>

    									<div class="col-xs-4" style="padding-top: 8px;" v-if="!edicao">
    										<a href="#" v-if='object.favorito' @click.prevent="setFavorito30Dias( object.id, index )">
    											<span class="pull-right" title="Remover de favoritos">
    												<i class="fa fa-star" style="font-size: 16px; color: #f39c12;"></i>
    											</span>
    										</a>
    										<a href="" v-else @click.prevent="setFavorito30Dias( object.id, index )">
    											<span class="pull-right" title="Adicionar aos favoritos">
    												<i class="fa fa-star-o" style="font-size: 16px; color: #f39c12;"></i>
    											</span>
    										</a>
    									</div>
    									<div class="col-xs-12 altura_publicacao">
    										<div class="col-xs-12 text-titulo-publicacao truncate">
    											<a :href="'/base-de-conhecimento/publicacoes/'+object.id" class="href_no_color">
    												<span>{{ object.titulo }}</span>
    											</a>	
    										</div>
    										<div class="col-xs-12 text-justify margin-top text-resumo-publicacao truncate">
    											<a :href="'/base-de-conhecimento/publicacoes/'+object.id" class="href_no_color">
    												<small v-html="object.resumo"></small>
    											</a>
    										</div>
    									</div>
    									<div class="col-xs-12" style="margin: 10px 0;">
    										<a :href="'/base-de-conhecimento/publicacoes/'+object.id" type="button" class="btn btn-block btn-primary btn-sm">VEJA!</a>
    									</div>
    								</div>
    							</div>
    						</div>
    	        		</div>
    			    </div>
                </div>

			    <div v-else>
				 	<div class="box-body" v-if="publicacoes.novas.length == 0"> 
				 		<div class="col-xs-12 text-center">
				 			<span v-if="favoritos">Nenhuma publicação favorita.</span>
				 			<span v-else>Não existem novas publicações.</span>
				 		</div>
					</div>
				</div>
			</div>

            <div v-if="proximas_publicacoes">
                <div v-if="publicacoes.count > 0">
                    <div class="box-body" v-for="(publicacoes, index) in publicacoes.proximas">
                        <h3>{{index == '' ? 'Não publicadas' : index}}</h3>
                        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 margin-top" style="width:290px;" v-for="object in publicacoes">
                            <vc-componente-publicacao :object="object" :tipo="'proximas_publicacoes'"></vc-componente-publicacao>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <div class="box-body"> 
                        <div class="col-md-12 text-center">
                            Não existem próximas publicações.
                        </div>
                    </div>
                </div>
            </div>

			<div v-if="!(proximas_publicacoes) && publicacoes_novas == 'false' && publicacoes.publicadas.dados && edicao == false">
			 	<div class="box-body" v-if="publicacoes.publicadas.dados.length == 0"> 
			 		<div class="col-xs-12 text-center">
			 		A categoria {{titulo}} não possui publicações.
			 		</div>
				</div>
			</div>

		</div>
	</div>
</template>
<script>
	  export default {
	 	props:['categoria_id', 'imagem_capa', 'favoritos', 'proximas_publicacoes'],
	 	name: 'app',
	 	data(){
            return {
                dados_response:[],  
                titulo:'CATEGORIA',
                publicacoes:{
                	novas: {},
                    proximas: {},
                    count: '',
                },
                object:[],
                can:[],
				carregando: true,
				publicacoes_novas: 'false',
				total: '',
				publicacoesNovas:false				

            }
        },
        methods: {
        	verificaUltimasPublicacoes(){
        		//chamas Novas publicações, dos últimos 30 dias
        		if( this.categoria_id == 0 )
        			this.getPublicacoesUltimos30Dias();
        		else
        			this.get()
        	},
        	getPublicacoesUltimos30Dias(){        		
        		this.ajaxNovasPublicacoes();   				
        	},
        	get(){        		
     			this.ajaxPublicacoes();
            },
         	
            getUrlNovasPublicacoes(){
				return '/base-de-conhecimento/publicacoes/novas';
            },           

         	getUrl(){
                return '/base-de-conhecimento/publicacoes/categoria_ajax/'+this.categoria_id;
            },            

            getStatusEdicao(){
                if( this.$cookie.get('editar_publicacao') == 'true' )
                    this.edicao = true;
                else
                    this.edicao = false;
            },            
            ajaxPublicacoes(){
            	//como passar parametros de imagem
            	window.axios.get(this.getUrl(), {
                }).then((response) =>{

                	this.publicacoes_novas = 'false';
                	this.dados_response = response.data;
                    this.can = response.data.can;
                    this.titulo = response.data.categoria.nome;
                    this.publicacoes = response.data.publicacoes;
                    this.carregando = false;                   
                	this.total = response.data.publicacoes.publicadas.dados.length;

                });
            },
            ajaxNovasPublicacoes(){

            	window.axios.post(this.getUrlNovasPublicacoes(), {
                }).then((response) =>{
                    this.publicacoes_novas = 'true';
					this.publicacoes.novas = response.data.publicacoes;
                    this.carregando = false;
                    this.titulo = 'NOVAS PUBLICAÇÕES';                    
                	this.total = response.data.publicacoes.length;
                	this.publicacoesNovas = true;
                });
            },
            
            /**
             * [setFavorito30Dias]
             * @param {[type]} id    [id da publicacao_favoritos]
             * @param {[type]} index [index of object]
             */
            setFavorito30Dias( id, index ){

            	let fav = this.publicacoes.novas[index].favorito;
            	this.publicacoes.novas[index].favorito = !fav;

            	if( fav ){
            		if(this.ajaxDeleteFavorito(id))
            			this.publicacoes.novas[index].favorito = !fav;
            	}else{
            		if(this.ajaxSetFavorito(id))
            			this.publicacoes.novas[index].favorito = !fav;
            	}
            },
            ajaxDeleteFavorito(id){
        		let url = '/base-de-conhecimento/publicacao/favoritos/'+id;            		
        		let resposta = false;

        		window.axios.delete(url, {                   
                }).then((response) =>{                	
					resposta = true
                }).catch(function (error) {                    
                    resposta = false;
                });

                return resposta;
            },
            ajaxSetFavorito(id){
        		let url = '/base-de-conhecimento/publicacao/favoritos';
        		let resposta = false;

        		window.axios.post(url, {
                   publicacao_id: id
                }).then((response) =>{                	                	
                	resposta = true
                }).catch(function (error) {                    
                    resposta = false;
                });

                return resposta;
            },
            verificaMenuChecado(){            	
            	if(this.favoritos)
            		this.ajaxFavoritos();
                else if(this.proximas_publicacoes)
                    this.ajaxProximasPublicacoes();
            	else
        			this.verificaUltimasPublicacoes();
            },            
            getUrlFavoritos(){
            	return '/base-de-conhecimento/publicacoes/favoritos';
            },
            getUrlProximasPublicacoes(){
                return '/base-de-conhecimento/publicacoes/proximas';
            },
            ajaxFavoritos(){
                window.axios.post(this.getUrlFavoritos(), {                   
                }).then((response) =>{
                    
                    console.log( response.data.publicacoes)
					this.publicacoes_novas = 'true';					
					this.publicacoes.novas.favoritos = response.data.publicacoes;
                    this.carregando = false;
                    this.titulo = 'MEUS FAVORITOS';					
                });	
            },
            ajaxProximasPublicacoes()
            {
                window.axios.post(this.getUrlProximasPublicacoes(), {                   
                }).then((response) =>{
                    
                    this.publicacoes.proximas = response.data.publicacoes;
                    this.publicacoes.count = response.data.publicacoes_count;
                    this.carregando = false;
                    this.titulo = 'PRÓXIMAS PUBLICAÇÕES';                 
                }); 
            },
            getUrlImagemPublicacao(){
                return '/base-de-conhecimento/publicacoes/img-publicacao/';
            },
            
        },     
        mounted(){
        	this.verificaMenuChecado()
        	this.getStatusEdicao()
        }
	};
</script>
<style>
	.href_no_color{
	   	color: #333;
    	text-decoration: none;
	}
	.href_no_color:hover{
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
	    height: 165px;
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
