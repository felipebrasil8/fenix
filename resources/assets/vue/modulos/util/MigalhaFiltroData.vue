<template>
	<section class="content-header" :titulo="titulo" >
			<h1 class="">
				{{titulo}}
				<small v-if="descricao"> {{ descricao }}</small>				
	 			<small v-on:mouseover="comboAparece">
	 				- 
					<span v-if="combo" style="display: inline-block;">
						<select v-model="selected_date" @change="filtroDash()" class="form-control input-sm" >
						  	<option v-for="item in datas" :value="item.value"> {{ item.nome }}</option>
						</select>
					</span>
					<span v-else v-for="item in datas">
						<span  v-if="item.value == selected_date && item.value != 'customizado'" > {{ item.nome }}</span>
						<span  v-else-if="item.value == 'customizado' && selected_date == 'customizado' && customizado == false"> {{ item.nome }} </span>
						<span  v-else-if="item.value == 'customizado' && selected_date == 'customizado'" > {{ customizado }} </span>
					</span>
					<span class="overlay" v-if="carregando" style="margin-left: 5px;">
		            		<i class="fa fa-refresh fa-spin"></i>
		            </span> 
				</small>
			</h1>
          	<ol class="breadcrumb">
				<li v-for="item in migalha">
					<a :href="item.url" class="ng-binding">
						<i :class="'fa fa-'+item.icone" ></i>{{ item.nome }}
					</a>
				</li>
			</ol>
			<vc-intervalo-data-modal  @dates="dates" titulo="Selecione um período" ></vc-intervalo-data-modal>
	</section>

</template>
<script>
	export default {
	 	props:['titulo', 'migalha', 'descricao', 'datas', 'url', 'prefixo', 'inicial', 'esconde', 'timeout'],
		data () {
            return {
                data:[],
                carregando:false,
         		combo:false,
                viewData:false,
               	selected_date:'',
               	selected_date_ate:'',
               	selected_date_de:'',   
                customizado: '',
			}
        },
        watch:{
        	esconde(){
        		this.combo = this.esconde 	
        	}
        },
        methods:{
        	comboAparece(){
			  	this.viewData = true
			  	this.combo = true 	
			  	// this.esconde = true
			    this.$emit('comboAparece')
			},
	       	
			filtroDash(){
            	
            	if ( this.selected_date == 'customizado' ){
            		this.$modal.show('modal-intervalo-data')
            		
            	}else{
	            	this.setFiltoData(this.selected_date);
            
            	}
            
            },
            setFiltoData( filtro_coockie, perquisa = '' ){
            	
            	this.$cookie.set(this.prefixo+'data', ''+filtro_coockie+'', 1);
            	
            	if (filtro_coockie == 'customizado'){
            		this.$cookie.set(this.prefixo+'data_ate', ''+perquisa.ate+'', 1);
            		this.$cookie.set(this.prefixo+'data_de', ''+perquisa.de+'', 1);
            	}else{
            		this.$cookie.set(this.prefixo+'data_ate', '', 1);
            		this.$cookie.set(this.prefixo+'data_de', '', 1);
            	}
				this.getDadosAjax()

            },
            getFiltoData(){
             	
				if(this.$cookie.get(this.prefixo+'data') != undefined)
				{
	                this.selected_date = this.$cookie.get(this.prefixo+'data');

	                if (this.selected_date == 'customizado')
	                {
	            		this.selected_date_ate = this.$cookie.get(this.prefixo+'data_ate');
	            		this.selected_date_de = this.$cookie.get(this.prefixo+'data_de');

	            		if (this.selected_date_ate != undefined && this.selected_date_ate != '' && this.selected_date_de != undefined && this.selected_date_de != '' )
	            		{
	            			this.customizado = this.selected_date_de+' até '+this.selected_date_ate; 
	            		}
	            		else 
	            		{
	            			this.customizado = false
	            		}
					}
				}
				else
				{
	                this.setFiltoData(this.inicial)
	                this.selected_date = this.$cookie.get(this.prefixo+'data');
				}
            },  
            dates( pesquisas )
            {
	            this.setFiltoData('customizado', pesquisas);
			
			    if (pesquisas.de != undefined && pesquisas.de != '' && pesquisas.ate != undefined && pesquisas.ate != '' )
			    {
					this.customizado = pesquisas.de+' até '+pesquisas.ate; 
    			}
    			else
    			{
        			this.customizado = false
        		}
            },
            getDadosAjax(){
            	this.carregando = 'true'
	        	this.getFiltoData()

            	this.item = {
            		'selected_date': this.selected_date,
            		'selected_date_de': this.selected_date_de,
            		'selected_date_ate': this.selected_date_ate,
            	}
            	window.axios.post(this.url, this.item)
				.then(response=>{
					this.$emit('busca-dash', response.data, this.item)
            		this.carregando = false
				    return 0;
				})
				.catch(error => {
				       this.errors = error.response.data.errors;
				       this.validaBotao = false;
				       return 0; 
				});


            },
            montaDash(){
				this.getDadosAjax()
	            var s = this

				setTimeout(function(){ 
					s.montaDash()
				}, this.timeout*1000);
            },
    	
    	},
    	mounted(){
			this.montaDash()
			
			setInterval(function(){
        		location.reload();
        	}, 1800000);
        	
    
			
        },    
    
    };
</script>
<style>
	.v--modal-box.v--modal {
	    background-color: #fff0 !important;
	    box-shadow: none !important;
	}
</style>

