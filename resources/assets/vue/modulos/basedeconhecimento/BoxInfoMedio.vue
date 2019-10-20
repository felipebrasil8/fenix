<template>

	<div class="col-lg-3 col-md-6 col-xs-12">	
		<div class="box" style="height: 290px;">
	        <div class="box-header">
		       <div class="col-md-12" style="padding:0px;">
			        <div class="col-md-10" style="padding:0px;" data-toggle="tooltip" data-placement="top" :title="titulo">
			        	
			        <!-- <h3 class="box-title"> -->
			        	<span class="info-box-text" style="font-size:18px; line-height:1;text-transform:none">
			        		{{ titulo }}
			        	</span>
			        <!-- </h3> -->
			        </div>
			        <div class="col-md-2" style="padding:0px;">
		    			<i v-if="download == 'true' && canDownload == true" style="font-size: 12px;" @click="downloadExcel($event, referencia)" class="fa fa-download pull-right text-muted btn btn-box-tool posicaoDownload firefoxPosicao" ></i>
				    </div>
		       	
		       </div>
	        </div>
	        <hr style="margin: 0px;">
	        <div class="box-body">
	
	          	<table class="table table-striped " style="table-layout:fixed;">
	            	<tbody>
		                <tr style="height: 45px !important;"  v-for="item in content" >
               
		                  	<td style="width:90%; " :style="avatar == 'true'? 'padding-top: 5px;padding-bottom: 0px;' :'vertical-align: bottom;'">
							
								<img v-if="avatar == 'true'" class="img-circle img-bordered-sm" style="width: 35px !important; height: 35px !important;" :src="getUrlImagemAvatarPequeno()+item.avatar">
		                  		
		                  		<span style="padding: 10px;" v-if="avatar == 'true'"> 
		                  			{{ item.nome }} 
								</span>
								
								<span class="truncate" style="white-space: nowrap !important" v-else>
		                  		<!-- <span  data-toggle="tooltip" :title="item.nome"  v-else> -->
		                  			{{ item.nome }} 
		                  		</span>
		                  	
		                  	</td>

		                    <td style="float: right;font-size: 17px;"><b> {{ item.valor }} </b></td>
		                </tr>
		            </tbody>
	      		</table>
	
	        </div>
	    </div>
	</div>

</template>

<script>

	export default {
	 	props:['download', 'canDownload', 'titulo', 'avatar' ,'content', 'referencia' ],
		data () {
            return {
                pages:[],
            }
        },
        methods:{
            getUrlImagemAvatarPequeno(){
                return '/rh/funcionario/avatar-pequeno/';
            },
        
 	     	downloadExcel(ev, excel){
        		
        		ev.preventDefault()
                this.$emit('downloadExcel', excel)
	
         	}

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


