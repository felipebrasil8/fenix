(function(){

	'use strict';	

	// dashboard
	angular.module('app').controller('dashboardTicketCtrl', ['$scope', '$timeout', 'DashboardFactory', '$cookies', '$uibModal', function($scope, $timeout, DashboardFactory, $cookies, $uibModal)

	{
		var migalhas = _migalhas;
        var departamentos = _departamentos;
        var visualizar_todos = _visualizar_todos;
        var tempoPing = _timeout;

		var init = function()
		{	
			$scope.grafico = '';
			$scope.migalhas = migalhas;
			
			/**
			 * Váriaveis de filtro de departamento e data
			 */
            $scope.departamentos = departamentos;
            $scope.visualizar_todos = visualizar_todos;
            $scope.departamento = {};
            $scope.datas = [
                {value: 'hoje', nome:'HOJE'},
                {value: 'mes_atual', nome:'MÊS ATUAL'},
                {value: 'ultimos_trinta_dias', nome:'ÚLTIMOS 30 DIAS'},
                {value: 'ano_atual', nome:'ANO ATUAL'},
                {value: 'ultimo_ano', nome:'ÚLTIMO ANO'},
                {value: 'customizado', nome:'CUSTOMIZADO'}
            ];
            $scope.data = {};
            $scope.viewDepartamento = true;
            $scope.viewData = true;
            $scope.carregando = false;
            $scope.viewTela = false;

            verificaCookiesDepartamento();
            verificaCookiesData();

            montaDashboard();
            setTimeout(function(){
	        	pingaDash();
            }, 1000);

		};

		//funcao criada para atualizar a pagina e corrigir provisóriamente, erro de  tela travada do dash na tv.
        //ira atualizar a pagina por (x) minutos
        setInterval(function(){
        	location.reload();
        }, 60000*20);

		var pingaDash = function(){
			montaDashboard();
            setTimeout(function(){
				pingaDash();
            }, tempoPing*1000);
		}

		var viewDataCustomizado = function ( data )
		{
			$scope.stringData = '';

			if( data.value == 'customizado' )
			{
				if( typeof $cookies.get('dashboard_ticket_data_de') != "undefined" && typeof $cookies.get('dashboard_ticket_data_ate') != "undefined" )
				{
					$scope.stringData = '' + $cookies.get('dashboard_ticket_data_de') + ' até ' + $cookies.get('dashboard_ticket_data_ate');
				}
				else
				{
					$scope.stringData = $scope.data.nome;
				}
				
				return true;
			}

			$scope.stringData = $scope.data.nome;
			return false;
		}

		var montaDashboard = function()
		{
			if( typeof $cookies.get('dashboard_ticket_departamento') != "undefined" && typeof $cookies.get('dashboard_ticket_data') != "undefined" )
			{
				var enviar = true;

				var filter = {
					id: $cookies.get('dashboard_ticket_departamento'),
					data: $cookies.get('dashboard_ticket_data'),
				}

				if( viewDataCustomizado( $scope.data ) )
				{
					if( typeof $cookies.get('dashboard_ticket_data_de') != "undefined" && typeof $cookies.get('dashboard_ticket_data_ate') != "undefined" )
					{
						filter.data_de = $cookies.get('dashboard_ticket_data_de');
						$scope.data.data_de = $cookies.get('dashboard_ticket_data_de');
						filter.data_ate = $cookies.get('dashboard_ticket_data_ate');
						$scope.data.data_ate = $cookies.get('dashboard_ticket_data_ate');
					}
					else
					{
						enviar = false;
					}
				}

				if( enviar )
				{
					$scope.carregando = true;
					$scope.viewTela = true;

			        montaGraficos( filter );

					$timeout( function(){
			            $scope.carregando = false;
			        }, 2000 );
					
				}
			}
		}		

		var montaGraficos = function( filter )
		{
			DashboardFactory.getDashboardTicket( filter )
			.then(function mySuccess(response)
	        {
	        	$scope.ticketsNovos             = response.data.ticketsNovos;
				$scope.totalDeTicketsAbertos    = response.data.totalDeTicketsAbertos;
				$scope.ticketsFechadosNoPeriodo = response.data.ticketsFechadosNoPeriodo;
				$scope.avaliacao                = response.data.avaliacao;
				$scope.avaliacaoQtde            = (response.data.avaliacaoQtde == 0 ? '' : response.data.avaliacaoQtde);

				if (response.data.avaliacaoQtde == 0)
					$scope.avaliacaoQtdeTexto = '';
				else if (response.data.avaliacaoQtde == 1)
					$scope.avaliacaoQtdeTexto = 'AVALIAÇÃO';
				else
					$scope.avaliacaoQtdeTexto = 'AVALIAÇÕES';

				$scope.tempoMedioDeAtendimento  = response.data.tempoMedioDeAtendimento;

				$scope.responsaveis = response.data.responsaveis;

				$scope.ticketsAbertosPorDepartamento = response.data.ticketsAbertosPorDepartamento;
				google.charts.load("current", {packages:['corechart', 'bar']});
				google.charts.setOnLoadCallback(drawChart_ticketsPorResponsavelAbertosCtrl(response));
				google.charts.setOnLoadCallback(drawChart_ticketsPorResponsavelFechadosCtrl(response));
				google.charts.setOnLoadCallback(drawChart_ticketsPorStatusCtrl(response));
				google.charts.setOnLoadCallback(drawChart_ticketsAbertosPorPrioridadeCtrl(response));
				google.charts.setOnLoadCallback(drawChart_ticketsPorCategoria(response));

	        });
		}

		$scope.hoverInDepartamento= function()
        {
            $scope.viewDepartamento = false;
        }

        $scope.hoverOutDepartamento= function()
        {
            $scope.viewDepartamento = true;
        }

        $scope.hoverInData= function()
        {
            $scope.viewData = false;
        }

        $scope.hoverOutData= function()
        {
            $scope.viewData = true;
        }

        var setSelect = function( response, lista )
        {
            var indexObj = 0;
            var encontrado = false;

            angular.forEach(response, function(value) {
                if( value.selected == 'selected' )
                {
                    indexObj = lista.indexOf( value );
                    encontrado = true;
                }
            });

            if( encontrado )
            {
            	return indexObj;
            }

            return -1;
        }

        var verificaCookiesDepartamento = function( )
        {
            if( $scope.departamentos != '' )
            {
            	// Se não tive cookie definido
                if(typeof $cookies.get('dashboard_ticket_departamento') == "undefined")
                {
                	var index = setSelect(departamentos, $scope.departamentos);
                	if( index == -1 )
                	{
                		$scope.departamento.nome = "SELECIONE UM DEPARTAMENTO";
                	}
                	else
                	{
                		$scope.departamento = $scope.departamentos[ setSelect(departamentos, $scope.departamentos) ];
                    	$cookies.put('dashboard_ticket_departamento', $scope.departamento.id);
                	}
                }
                else
                {
                    var indexObj = 0;

                    angular.forEach($scope.departamentos, function(value) {
                        if( value.id == $cookies.get('dashboard_ticket_departamento') )
                        {
                            indexObj = $scope.departamentos.indexOf( value )
                        }
                    });

                    $scope.departamento = $scope.departamentos[ indexObj ];
                }
            }
        }

        var verificaCookiesData = function( )
        {
        	// Se não tive cookie definido
            if(typeof $cookies.get('dashboard_ticket_data') == "undefined")
            {
                $scope.data = {value: 'mes_atual', nome:'MÊS ATUAL'};
                $cookies.put('dashboard_ticket_data', $scope.data.value);
            }
            else
            {
                var indexObj = 0;

                angular.forEach($scope.datas, function(value) {
                    if( value.value == $cookies.get('dashboard_ticket_data') )
                    {
                        indexObj = $scope.datas.indexOf( value )
                    }
                });

                $scope.data = $scope.datas[ indexObj ];
            }

           viewDataCustomizado( $scope.data );
        }

        $scope.setDepartamentoCookie = function( departamento )
        {
            // Define os parametros da pesquisa nos cookies.
            $cookies.put('dashboard_ticket_departamento', departamento.id);
            $scope.viewDepartamento = true;
            montaDashboard();
        }

        $scope.setDataCookie = function( data )
        {
        	// Define os parametros da pesquisa nos cookies.
            $cookies.put('dashboard_ticket_data', data.value);
        	$scope.viewData = true;

        	if( data.value == 'customizado' )
        	{
        		$scope.modalPeriodo();
        	}
        	else
        	{
            	montaDashboard();
        	}

        	$scope.stringData = $scope.data.nome;
        }

        $scope.modalPeriodo = function(){
            var modalDataCusmotizado = $uibModal.open({
                animation: true,
                ariaLabelledBy: 'modal-title-bottom',
                ariaDescribedBy: 'modal-body-bottom',
                size: 'md',
                scope: $scope,
                templateUrl: 'periodoModal.html',
                resolve: {
                    
                },
                controller: function ($scope, $filter)
                {                	
                	var cookies = function ()
                	{
                		// Se tem cookies
	                	if(typeof $cookies.get('dashboard_ticket_data_de') != "undefined" && typeof $cookies.get('dashboard_ticket_data_ate') != "undefined" )
			            {
			                $scope.data_de = $cookies.get('dashboard_ticket_data_de');
			                $scope.data_ate = $cookies.get('dashboard_ticket_data_ate');
			            }
			            else
			            {
			            	$scope.data_de = $filter('date')(new Date(), 'dd/MM/yyyy');
	                    	$scope.data_ate = $filter('date')(new Date(), 'dd/MM/yyyy');
			            }
                	}

                	cookies();
                			            		            
                    $scope.modalFecharCustomizado = function ()
                    {
                    	$scope.$parent.stringData = $scope.data.nome;
                        modalDataCusmotizado.close();
                    }

                    $scope.modalData = function (){
                        $cookies.put('dashboard_ticket_data_de', $scope.data_de);
                        $cookies.put('dashboard_ticket_data_ate', $scope.data_ate);
                        $scope.$parent.stringData = $cookies.get('dashboard_ticket_data_de') + ' até ' + $cookies.get('dashboard_ticket_data_ate');
                        montaDashboard();
                        modalDataCusmotizado.close();
                    }
                }
            });
        }


        $scope.porResponsavel = function(){            
        	document.exportar.action = "dashboard/download/responsavel";
            document.exportar.submit();
		}
		$scope.porStatus = function(){            
        	document.exportar.action = "dashboard/download/status";
            document.exportar.submit();
		}
		$scope.porPrioridade = function(){            
        	document.exportar.action = "dashboard/download/prioridade";
            document.exportar.submit();
		}
		$scope.porCategoria = function(){            
        	document.exportar.action = "dashboard/download/categoria";
            document.exportar.submit();
		}
		$scope.porAvaliacao = function(){            
        	document.exportar.action = "dashboard/download/avaliacao";
            document.exportar.submit();
		}

		//ticketsPorResponsavelAbertosCtrl
		var drawChart_ticketsPorResponsavelAbertosCtrl = function(response) {
			
			var cores_responsaveisAbertos = [];
			response.data.responsaveisAbertos.forEach(function (x) {
				cores_responsaveisAbertos.push(x.cor);
			});

			var data_ticketsPorResponsavelAbertosCtrl = new google.visualization.DataTable();
			data_ticketsPorResponsavelAbertosCtrl.addColumn('string', 'nome');
			data_ticketsPorResponsavelAbertosCtrl.addColumn('number', 'total');					

			response.data.responsaveisAbertos.forEach(function (row) {
				data_ticketsPorResponsavelAbertosCtrl.addRow([
					row.nome,
					row.total
					]);
			});

			var options_ticketsPorResponsavelAbertosCtrl = {
				pieHole: 0.5,
				pieSliceTextStyle: {
					color: '#333333',
					bold: true,
					fontSize: 18,
				},						
				legend: 'none',
				chartArea:{width:"100%",height:"100%"},						
				colors: cores_responsaveisAbertos,
				pieSliceText: 'value'
			};

			var x = document.getElementById("idNoDataAbertos");
			var theDiv = document.getElementById("idTicketsPorResponsavelAbertos");
			x.style.display = "none";
			theDiv.style.display = "none";

			if (data_ticketsPorResponsavelAbertosCtrl.getNumberOfRows() == 0) {

				x.style.display = "block";

			}else{

				theDiv.style.display = "block";
				var chart_ticketsPorResponsavelAbertosCtrl = new google.visualization.PieChart(document.getElementById('idTicketsPorResponsavelAbertos'));
				chart_ticketsPorResponsavelAbertosCtrl.draw(data_ticketsPorResponsavelAbertosCtrl, options_ticketsPorResponsavelAbertosCtrl);
			}
		}

		//_ticketsPorResponsavelFechadosCtrl
		function drawChart_ticketsPorResponsavelFechadosCtrl(response) {

			var cores_responsaveisFechados = [];
			response.data.responsaveisFechados.forEach(function (x) {
				cores_responsaveisFechados.push(x.cor);
			});
			var data_ticketsPorResponsavelFechadosCtrl = new google.visualization.DataTable();

			data_ticketsPorResponsavelFechadosCtrl.addColumn('string', 'nome');
			data_ticketsPorResponsavelFechadosCtrl.addColumn('number', 'total');

			response.data.responsaveisFechados.forEach(function (row) {
				data_ticketsPorResponsavelFechadosCtrl.addRow([
					row.nome,
					row.total
					]);
			});

			var options_ticketsPorResponsavelFechadosCtrl = {
				pieHole: 0.5,
				pieSliceTextStyle: {
					color: '#333333',
					bold: true,
					fontSize: 18,
				},
				legend: 'none',
				chartArea:{width:"100%",height:"100%"},
				colors: cores_responsaveisFechados,
				pieSliceText: 'value'			
			};
			
			var x = document.getElementById("idNoDataFechados");
			var theDiv = document.getElementById("idTicketsPorResponsavelFechados");					
			x.style.display = "none";
			theDiv.style.display = "none";

			if (data_ticketsPorResponsavelFechadosCtrl.getNumberOfRows() == 0) {

				x.style.display = "block";

			}else{

				theDiv.style.display = "block";
				var chart_ticketsPorResponsavelFechadosCtrl = new google.visualization.PieChart(document.getElementById('idTicketsPorResponsavelFechados'));
				chart_ticketsPorResponsavelFechadosCtrl.draw(data_ticketsPorResponsavelFechadosCtrl, options_ticketsPorResponsavelFechadosCtrl);
			}

		}

		//Tickets por status abertos no período
		function drawChart_ticketsPorStatusCtrl(response) {

			var cores_status = [];					
			response.data.status.forEach(function (x) {
				cores_status.push(x.cor);
			});

			var data_ticketsPorStatusCtrl = new google.visualization.DataTable();

			data_ticketsPorStatusCtrl.addColumn('string', 'nome');
			data_ticketsPorStatusCtrl.addColumn('number', 'total');

			response.data.status.forEach(function (row) {
				data_ticketsPorStatusCtrl.addRow([
					row.nome,
					row.total
					]);
			});

			var options_ticketsPorStatusCtrl = {
				pieHole: 0.5,
				pieSliceTextStyle: {
					color: '#333333',
					bold: true,
					fontSize: 18,
				},
				legend: 'none',
				chartArea:{width:"100%",height:"100%"},
				colors: cores_status,
				pieSliceText: 'value'			
			};

			var x = document.getElementById("idNoDataStatus");
			var theDiv = document.getElementById("ticketsPorStatusCtrl");										
			x.style.display = "none";
			theDiv.style.display = "none";

			if (data_ticketsPorStatusCtrl.getNumberOfRows() == 0) {

				x.style.display = "block";

			}else{

				var i = 0;
				response.data.status.forEach(function (row) {				    		
					if( row.total == 0){
						i++;
					}      						
				});
				var tamanho = response.data.status.length;
				
				if( i == tamanho ){
					x.style.display = "block";
				}else{

					theDiv.style.display = "block";
					$scope.status = response.data.status;
					var chart_ticketsPorStatusCtrl = new google.visualization.PieChart(document.getElementById('ticketsPorStatusCtrl'));
					chart_ticketsPorStatusCtrl.draw(data_ticketsPorStatusCtrl, options_ticketsPorStatusCtrl);				    	
				}
				
				

			}
		}

		//Tickets abertos por prioridade
		function drawChart_ticketsAbertosPorPrioridadeCtrl(response) {

			var cores_prioridades = [];
			$scope.prioridades = response.data.prioridades;
			response.data.prioridades.forEach(function (x) {
				cores_prioridades.push(x.cor);
			});

			var data_ticketsAbertosPorPrioridadeCtrl = new google.visualization.DataTable();

			data_ticketsAbertosPorPrioridadeCtrl.addColumn('string', 'nome');
			data_ticketsAbertosPorPrioridadeCtrl.addColumn('number', 'total');

			response.data.prioridades.forEach(function (row) {						
				data_ticketsAbertosPorPrioridadeCtrl.addRow([
					row.nome,
					row.total
					]);
			});

			var options_ticketsAbertosPorPrioridadeCtrl = {
				pieHole: 0.5,
				pieSliceTextStyle: {
					color: '#333333',
					bold: true,
					fontSize: 18,
				},
				legend: 'none',
				chartArea:{width:"100%",height:"100%"},
				colors: cores_prioridades,
				pieSliceText: 'value'			
			};

			var x = document.getElementById("idNoDataPrioridades");
			var theDiv = document.getElementById("ticketsAbertosPorPrioridadeCtrl");					
			x.style.display = "none";
			theDiv.style.display = "none";

			if (data_ticketsAbertosPorPrioridadeCtrl.getNumberOfRows() == 0) {
				x.style.display = "block";
			}else{
				theDiv.style.display = "block";
				var chart_ticketsAbertosPorPrioridadeCtrl = new google.visualization.PieChart(document.getElementById('ticketsAbertosPorPrioridadeCtrl'));
				chart_ticketsAbertosPorPrioridadeCtrl.draw(data_ticketsAbertosPorPrioridadeCtrl, options_ticketsAbertosPorPrioridadeCtrl);				    	
			}
		}

		function drawChart_ticketsPorCategoria(response)
		{
			var data_ticketsPorCategoria = new google.visualization.DataTable();

			data_ticketsPorCategoria.addColumn('string', 'Categoria');
			data_ticketsPorCategoria.addColumn('number', 'Qtd. de tickets');
			data_ticketsPorCategoria.addColumn({type: 'string', role: 'annotation'});

			response.data.ticketsPorCategoria.forEach(function (row) {						
				data_ticketsPorCategoria.addRow([
					'',
					row.total,
					''+row.nome+': '+row.total,
					]);
			});

			var options_ticketsPorCategoria = {
				chartArea:{width:"90%",height:"90%"},
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
			};

			var chart_ticketsPorCategoria = new google.visualization.BarChart(document.getElementById("columnchart_values"));
			chart_ticketsPorCategoria.draw(data_ticketsPorCategoria, options_ticketsPorCategoria);	
		}
		
		//inicializa controller cadastrarAcessoCtrl
		init();

	}]);
	
})();
