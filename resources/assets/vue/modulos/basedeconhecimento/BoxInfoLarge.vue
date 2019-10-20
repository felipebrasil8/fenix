
<template>

	<div class="col-md-6 col-xs-12" :class="responsivo == 'false' ? 'hidden-md hidden-sm hidden-xs' : '' ">	
		<div class="box" style="height: 352px;">
	        <div class="box-header">
		        <h3 class="box-title">{{ titulo }}</h3>
				<i v-if="download == 'true' && canDownload == true" style="font-size: 12px;" @click="downloadExcel($event, referencia)" class="fa fa-download pull-right text-muted btn btn-box-tool posicaoDownload" ></i>
	        </div>
	        <hr style="margin: 0px;">
	        <div class="box-body">
	          	<table class="table table-striped">
	            	<tbody>
		                <tr style="height: 40px;"  v-for="item in content">
		                  	<td style="line-height: 20px">
								<span style="padding: 10px;"> {{ item.nome }} </span>
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
	 	props:['download', 'canDownload', 'titulo', 'content', 'responsivo', 'referencia' ],

		data () {
            return {
                pages:[],
            }
        },
        methods:{
 	     	downloadExcel(ev, excel){
        		
        		ev.preventDefault()
                this.$emit('downloadExcel', excel)
	
         	}

        }
	};

</script>
