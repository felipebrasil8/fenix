<template>

	<div class="col-lg-3 col-md-6 col-xs-12">
		<!-- <div class="info-box" @click="" :style="(link!='')?'cursor: pointer;':''"> -->
		<div class="info-box" @click="possuiClick ? possuiClickEvent($event):''" :style="possuiClick ?'cursor: pointer;':''">
			<a style="color: #333;" >
				<span class="info-box-icon" :style="'background-color:'+boxColor">
					<i class="fa" :class="'fa-'+icone" :style="'color:'+iconeColor" ></i>
				</span>
				<!-- <div class="info-box-content" :style=" download == 'true' ? 'padding-bottom: 3px;' : '' ">  -->
				<div class="info-box-content" > 
					<span class="info-box-text" >
						<span class="truncate" :title="tooltip" :style="acao && canAcao? 'width:90%': ''">
							{{ titulo }}
						</span>
						<span v-if="acao && canAcao">
							<i  style="font-size: 12px;" @click="acaoEvent($event, acao)" class="pull-right text-muted btn btn-box-tool posicaoDownload firefoxPosicao" :class="'fa '+acaoIcone" ></i> 
						</span>
					</span>

					<span class="info-box-number">
					    <slot name="content" ></slot>
					</span>
				</div>
			</a>
		</div>
	</div>	




</template>

<script>

	export default {
		props:[
		 	'acao',
		 	'acaoIcone',
		 	'canAcao',
			'icone',
		 	'iconeColor' ,
		 	'boxColor',
		 	'titulo',
		 	'tooltip',
		 	'possuiClick',

		 	],
		
		data () {
            return {
                
            }
        },
        methods:{
        	acaoEvent(ev, acao){
				ev.preventDefault()
                this.$emit('acaoEvent', acao)
	      	},
        	possuiClickEvent(ev){
				ev.preventDefault()
                this.$emit('clickEvent', acao)
	       	},
	    }
	};

</script>

<style>
    @-moz-document url-prefix() { 
        .firefoxPosicao {
            padding-top: 26px;
        }
    }
</style>




