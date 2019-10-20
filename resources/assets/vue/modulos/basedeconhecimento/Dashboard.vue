 <template>
 	<div>
		<vc-migalha-filtro-data 
		 :titulo="titulo"
		 :migalha="migalha" 		  
		 :datas="datas"
		  @busca-dash="search"
		 url="/base-de-conhecimento/dashboard_ajax"
		 prefixo="dashboard_base_"
		 inicial="mes_atual"
         :timeout="timeout"
		 :esconde="fechaCombo"
         @comboAparece="comboAparece"
		>

		</vc-migalha-filtro-data>
        <section class="content" v-on:mouseover="comboEsconde">
			<div class="row">
		 		
		 		<vc-box-info-mini-dash
                    icone="share-alt" 
                    iconeColor="#fff"
                    boxColor="#ed7d31" 
                    titulo="Total de publicações" 
                    tooltip="" 
                >
                    <template slot="content">
                        <span style="font-size:38px" :title="content.total_publicacoes+' publicações'">
                            {{content.total_publicacoes}}     
                        </span>
                    </template>
                </vc-box-info-mini-dash>

                <vc-box-info-mini-dash
                    icone="user-circle" 
                    iconeColor="#fff"
                    boxColor="#5e2a71" 
                    titulo="Total de colaborações" 
                    tooltip="" 
                >
                    <template slot="content">
                        <span style="font-size:38px" :title="content.total_colaboracoes+' colaboradores'">
                            {{content.total_colaboracoes}}     
                        </span>
                    </template>
                </vc-box-info-mini-dash>

	 	
				 <vc-box-info-mini-dash
                    icone="hand-pointer-o" 
                    iconeColor="#fff"
                    boxColor="#00a65a" 
                    titulo="Qtde. de acessos no período" 
                    tooltip="" 
                >
                    <template slot="content">
                        <span style="font-size:38px" :title="content.total_acesso_periodo+' acessos no período'">
                            {{content.total_acesso_periodo}}     
                        </span>
                    </template>
                </vc-box-info-mini-dash>

 				

		 		<vc-box-info-mini 
		 			download="false" 
		 			:canDownload="content.exportar_dashboard"
					icone="search" 
		 			iconeColor="#fff"
		 			boxColor="#0073b7" 
		 			titulo="Qtde. de pesquisas no período" 
		 			:content="content.total_pesquisa_periodo" 
		 			@downloadExcel="downloadExcel"
		 			:alert="(content.tratar_pesquisa == true)? content.total_pesquisa_sem_resposta_periodo :'false'"
		 			referencia=""
		 			:modal="(content.tratar_pesquisa == true)?'busca-sem-resultado-modal':''"
				>
		 		</vc-box-info-mini>	

				<vc-box-info-mini-dash
                    icone="plus" 
                    iconeColor="#fff"
                    boxColor="#00a65a" 
                    titulo="Publicações novas no período" 
                    tooltip="" 

                    acao="Novas"
                    acaoIcone="fa-download"
                    :canAcao="content.exportar_dashboard" 
                    @acaoEvent="downloadExcel"
                
                >
                    <template slot="content">
                        <span style="font-size:38px" :title="content.total_publicacoes_novas_periodo+' publicações novas no período'">
                            {{content.total_publicacoes_novas_periodo}}     
                        </span>
                    </template>
                </vc-box-info-mini-dash>
				

				<vc-box-info-mini-dash
                    icone="arrow-up" 
                    iconeColor="#fff"
                    boxColor="#0073b7" 
                    titulo="Atualizações no período" 
                    tooltip="" 

                    acao="Atualizacao"
                    acaoIcone="fa-download"
                    :canAcao="content.exportar_dashboard" 
                    @acaoEvent="downloadExcel"
                
                >
                    <template slot="content">
                        <span style="font-size:38px" :title="content.total_publicacoes_atualizadas_periodo">
                            {{content.total_publicacoes_atualizadas_periodo}}     
                        </span>
                    </template>
                </vc-box-info-mini-dash>


				
				<vc-box-info-mini-dash
                    icone="street-view" 
                    iconeColor="#fff"
                    boxColor="#5e2a71" 
                    titulo="Usuários que acessou no período" 
                    tooltip="" 
                >
                    <template slot="content">
                        <span style="font-size:38px" :title="content.total_usuarios_acesso_periodo">
                            {{content.total_usuarios_acesso_periodo}}     
                        </span>
                    </template>
                </vc-box-info-mini-dash>

		 		<vc-box-info-mini 
		 			download="false" 
		 			:canDownload="content.exportar_dashboard"
					icone="envelope" 
		 			iconeColor="#fff"
		 			boxColor="#ed7d31" 
		 			titulo="Mensagens recebidas no período" 
		 			:content="content.total_mensagens_recebidas" 
		 			
		 			:alert="(content.tratar_mensagem == true)? content.total_mensagens_recebidas_nao_respondidas :'false'"
		 			:link="(content.tratar_mensagem == true)?'/base-de-conhecimento/mensagens':''"
		 			
		 			referencia=""
		 			modal=""
				>
		 		</vc-box-info-mini>	

			</div>

			<div class="row">
				<vc-box-info-medio 
		 			download="true" 
		 			:canDownload="content.exportar_dashboard"
					titulo="Pesquisas mais realizadas no período" 
		 			avatar="false"
		 			@downloadExcel="downloadExcel"
		 			:content="content.pesquisas_mais_realizadas_periodo" 
		 			referencia="PesquisasMaisRealizadas"

		 		>
		 		</vc-box-info-medio>
		 		<vc-box-info-medio 
		 			download="true"
		 			:canDownload="content.exportar_dashboard" 
					titulo="Pesquisas por departamento no período" 
		 			avatar="false"
		 			@downloadExcel="downloadExcel"
		 			:content="content.pesquisas_por_departamento_periodo" 
		 			referencia="PesquisaDepartamento"

		 		>
		 		</vc-box-info-medio>
		 		<vc-box-info-medio 
		 			download="true" 
		 			:canDownload="content.exportar_dashboard"
					titulo="Pesquisas por usuário no período" 
		 			avatar="true"
		 			@downloadExcel="downloadExcel"
		 			:content="content.pesquisas_por_usuario_periodo" 
		 			referencia="PesquisaUsuario"

		 		>
		 		</vc-box-info-medio>
		 		<vc-box-info-medio 
		 			download="true" 
		 			:canDownload="content.exportar_dashboard"
					titulo="Pódio geral de colaboração" 
		 			avatar="true"
		 			@downloadExcel="downloadExcel"
		 			:content="content.podio_colaborador" 
		 			referencia="PodioColaborador"

		 		>
		 		</vc-box-info-medio>
			</div>

			<div class="row">
				
		 		<vc-box-grafico-barra 
		 			download="true" 
		 			:canDownload="content.exportar_dashboard"
					titulo="Total de publicações por categoria" 
		 			:content="content.publicacoes_categoria" 
		 			@downloadExcel="downloadExcel"
		 			responsivo="false"
		 			referencia="PublicacoesCategoria"

		 		></vc-box-grafico-barra>
	 			
				

				<vc-box-info-large 
		 			download="true" 
		 			:canDownload="content.exportar_dashboard"
					titulo="Publicações mais acessadas no período" 
		 			:content="content.publicacoes_acessadas_periodo" 
		 			@downloadExcel="downloadExcel"
		 			responsivo="true"
					referencia="PublicacoesAcessadasPeriodo"
		 		>
		 		</vc-box-info-large>
			</div>
		</section>
		<vc-buscas-sem-resultado-modal @atualiza="atualiza" :content="content.buscas_sem_resultado"></vc-buscas-sem-resultado-modal>
		
		<form target="_blank" action="/base-de-conhecimento/dashboardDownload" method="post" id="excel">
        	<input type="text" name="titulo_excel" :value="titulo_excel"  hidden="hidden">
        	<input type="text" name="selected_date"  :value="selected_date" hidden="hidden" >
        	<input type="text" name="selected_date_de" :value="selected_date_de" hidden="hidden" >
        	<input type="text" name="selected_date_ate" :value="selected_date_ate" hidden="hidden" >
        </form>

	</div>
</template>


<script>
 	export default {
	 	props:['migalha', 'timeout'],

		data () {
            return {
                combo:false,
                titulo: 'Dashboard de base de conhecimento',
                viewData:false,
               	selected_date:'',
               	selected_date_ate:'',
               	selected_date_de:'',
	        	datas: [
	                {value: 'hoje', nome:'HOJE'},
	                {value: 'ultimo_dia', nome:'ÚLTIMO DIA'},
	                {value: 'semana_atual', nome:'SEMANA ATUAL'},
	                {value: 'ultima_semana', nome:'ÚLTIMA SEMANA'},
	                {value: 'mes_atual', nome:'MÊS ATUAL'},
	                {value: 'ultimo_mes', nome:'ÚLTIMO MÊS'},
	                {value: 'ultimos_trinta_dias', nome:'ÚLTIMOS 30 DIAS'},
	                {value: 'ano_atual', nome:'ANO ATUAL'},
	                {value: 'ultimo_ano', nome:'ÚLTIMO ANO'},
	                {value: 'customizado', nome:'CUSTOMIZADO'}
	            ],
	            customizado: '',
	            content:[],
	            carregando:'false',
	            titulo_excel:'',
	            fechaCombo:true,
	        }
        },
        methods:{
        	comboEsconde()
            {
                this.fechaCombo = false
            },
            comboAparece()
            {
                this.fechaCombo = true
            },
          	search( value, item ){
                this.content = value

                this.selected_date = item.selected_date 
               	this.selected_date_ate = item.selected_date_ate
               	this.selected_date_de = item.selected_date_de
            },
            downloadExcel(excel){
            	this.titulo_excel = excel
            	setTimeout(function(){ 
            		$('#excel').submit();
                }, 100);
                return 0;

            },
            atualiza(){
            	this.getDadosAjax();
            },
            eventoMensagem(){

            }
        },
    
        mounted(){

        },
	};


</script>

