<template>
	<div class="publicacao-conteiner">
		<div class="text-center">
			<a :href="getUrlId()">
				<img :src="link" class="img-responsive">
			</a>	
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="col-xs-8">
					<span class="pull-left time" :title="object.tooltip"><i class="fa fa-calendar" style="margin-right: 5px;"></i>{{ object.data }}</span>
				</div>

				<div class="col-xs-4" style="padding-top: 8px;" v-if="!edicao">
					<a href="#" v-if='object.favorito' @click.prevent="setFavorito( object.id )">
						<span class="pull-right" title="Remover de favoritos">
							<i class="fa fa-star" style="font-size: 16px; color: #f39c12;"></i>
						</span>
					</a>
					<a href="" v-else @click.prevent="setFavorito( object.id )">
						<span class="pull-right" title="Adicionar aos favoritos">
							<i class="fa fa-star-o" style="font-size: 16px; color: #f39c12;"></i>
						</span>
					</a>
				</div>

				<div class="col-xs-12 altura_publicacao">
					<div class="col-xs-12 text-titulo-publicacao truncate">
						<a :href="getUrlId()" class="href_no_color">
							<span>{{ object.titulo }}</span>
						</a>	
					</div>
					<div class="col-xs-12 text-justify margin-top text-resumo-publicacao truncate">
						<a :href="getUrlId()" class="href_no_color">
							<small v-html="object.resumo"></small>
						</a>
					</div>
				</div>
				<div class="col-xs-12" style="margin: 10px 0;">
                    <a v-if="this.tipo == 'proximas_publicacoes'" :href="getUrlId()" type="button" class="btn btn-block btn-primary btn-sm">EDITAR</a>
					<a v-else :href="getUrlId()" type="button" class="btn btn-block btn-primary btn-sm">VEJA!</a>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default{
		props:['object', 'tipo'],
	 	name: 'app',
	 	data(){
	 		return {
	 			edicao: false,
	 			link: '',
	 		}
	 	},
        methods: {
        	getUrlImagemPublicacao(){
        		this.link = '/base-de-conhecimento/publicacoes/img-publicacao/'+this.object.id
        		return 0
               
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
            getUrlId()
            {
                var url = '/base-de-conhecimento/publicacoes/'+this.object.id;
                if( this.tipo == 'proximas_publicacoes' )
                {
                    return url+'/edit';
                }

                return url;
            },            
        },    
        watch:{

            object () {
          		this.link = ''
				var s = this
				setTimeout(function(){ 
                    s.getUrlImagemPublicacao()
                        }, 10);
            }
        
        },
        mounted(){
            
        	// this.link = ''
        	this.getStatusEdicao()
        	this.getUrlImagemPublicacao()



        }



    
	}
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