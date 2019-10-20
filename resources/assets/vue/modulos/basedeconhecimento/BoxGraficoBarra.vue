
<template>

	<div class="col-md-6 col-xs-12" :class="responsivo == 'false' ? 'hidden-md hidden-sm hidden-xs' : '' ">	
		<div class="box" style="height: 352px;">
	        <div class="box-header">
		        <h3 class="box-title">{{ titulo }}</h3>
		       	<i v-if="download == 'true' && canDownload == true" style="font-size: 12px;" @click="downloadExcel($event, referencia)" class="fa fa-download pull-right text-muted btn btn-box-tool posicaoDownload" ></i>
	        </div>
	        <hr style="margin: 0px;">
	        <div class="box-body">
	      		<div id="columnchart_values" ></div>
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
	   			chartData: [],
			    chartOptions: {
			    	height: 270,
		        	chartArea:{width:"90%",height:"100%"},
					bar: {groupWidth: "30"},
					legend: { position: "none" },
					colors: ['#0073b7'],
					annotations: {
						//alwaysOutside: true,
						textStyle: {
							fontSize: 12,
							bold: true,
							color: '#333'
						}
					},
					vAxis: {
						minValue: 0,
						viewWindow: { min: 0},
						textStyle: {
							fontSize: 12,
							bold: true,
							color: '#444'
						},
					},
					hAxis: {
						gridlines: {count: 0},
						minValue: 0,
						viewWindow: { min: 0},
					},
			    }

            }
        },
        watch:{

            content() {
                var x = [['categoria', 'quantidade', { role: 'annotation' }]]
            	
            	for (let [key, value] of Object.entries(this.content)){
                	 x.push(['', value.valor, value.nome+': '+value.valor  ])
                }
                
                this.chartData = x
                	
                this.montaGrafico(); 
                
            }
   
		
        
        },
        methods:{
 	     	downloadExcel(ev, excel){
        		
        		ev.preventDefault()
                this.$emit('downloadExcel', excel)
	
         	},
         	drawChart() {
				  var data = google.visualization.arrayToDataTable(this.chartData);

				  // Optional; add a title and set the width and height of the chart
				  var options = this.chartOptions;

				  // Display the chart inside the <div> element with id="piechart"
				  var chart = new google.visualization.BarChart(document.getElementById('columnchart_values'));
				  chart.draw(data, options);
			},
			montaGrafico(){
	     		google.charts.load('current', {'packages':['corechart']});
				google.charts.setOnLoadCallback(this.drawChart);
					
			}
         	

        },
        mounted(){
        	this.montaGrafico()

        }
	};

</script>
 