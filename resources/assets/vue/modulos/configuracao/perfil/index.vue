<template>
    <div>
        <div class="row">
            <div class="col-md-12">

                <vc-filtro-pesquisa @pesquisar="search" :filtro="filtro" :filtroPadrao="filtroPadrao" :cookiePrefixo="cookiePrefixo" :url="url">
                
                    <slot>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="nome">Nome:</label>                            
                                <input type="text" class="form-control input-sm" v-model="filtro.nome" placeholder="Nome" style="text-transform: uppercase;">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Acessos:</label>
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <select class="form-control input-sm" v-model="filtro.acesso" style="width: 100%;">
                                            <option value=""></option>                           
                                            <option v-for="item in acessosObj" :value="item.id">{{item.nome}}</option>                           
                                        </select>                                        
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Dias da semana para login:</label>
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <select class="form-control input-sm" v-model="filtro.diasLogin" style="width: 100%;">
                                            <option value=""></option>                           
                                            <option value="true">Todos os Dias</option>                           
                                            <option value="false">Dias da Semana</option>                           
                                                                     
                                        </select>                                        
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-md-3">
                                <label><strong>Status:</strong></label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>
                                            <input type="radio" name="5" value="true" v-model="filtro.status" checked="checked"> Ativo
                                        </label>
                                        &nbsp;
                                        <label>
                                            <input type="radio" name="6" value="false" v-model="filtro.status" > Inativo
                                        </label>
                                        &nbsp;
                                        <label>
                                            <input type="radio" name="6" value="todos" v-model="filtro.status" > Todos
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </slot>
                

                </vc-filtro-pesquisa>

        	</div>
    	</div>
        <div class="row">
            <div class="col-md-12">
                <vc-datatable 
                    :dados="dados" 
                    :url="url" 
                    :filtro="filtro" 
                    :canObj="canObj" 
                    :erros="erros" 
                    :carregando="carregando" 
                    :cookiePrefixo="cookiePrefixo" 
                    :thead="thead" 
                    status="true" 
                    :acoes="true" >
                    <template slot="tbody" slot-scope="{ showConfirmeModal, item }">
                        <td style="width:90%; ">
                            <span class="truncate" :title="item.nome">
                                {{item.nome}}
                            </span>
                        </td>
                         <td class="text-center">
                            {{item.usuarios}}
                        </td>
                         <td class="text-center">
                            {{item.acessos}}
                        </td>
                    </template>
                </vc-datatable>
            </div>
        </div>

        <div v-if="canObj.cadastrar">
            <a href="/configuracao/perfil/create" title="Cadastrar">
                <button type="button" class="btn btn-primary">Cadastrar</button>
            </a>
        </div>
    </div>


</template>

<script>
 	export default {
	 	props:['can', 'acessos'],
		data () {
            return {
                nome:'',
                status:'true',
                filtro:{
                    nome:'',
                    acesso:'',
                    diasLogin:'',
                    status:'true',
                    por_pagina: 15,
                    pagina: 1,
                    sort:''
                },
                filtroPadrao:{
                    nome:'',
                    acesso:'',
                    diasLogin:'',
                    status:'true',
                    por_pagina: 15,
                    pagina: 1,
                    sort:'3 asc',
                },
                thead:[
                    {
                        text: 'Nome', 
                        coluna:3, 
                        ordena: true, 
                        class: '',
                        width:''
                    },
                     {
                        text: 'Usuarios vinculadas', 
                        coluna:4, 
                        ordena: true, 
                        class: 'text-center',
                        width:'200px'
                    },
                     {
                        text: 'Acessos vinculadas', 
                        coluna:5, 
                        ordena: true, 
                        class: 'text-center',
                        width:'200px'
                    },
                   
                   
                ],
                cookiePrefixo:'configuracao_perfil_',
                url:'/configuracao/perfil/',
                dados:{
                    data:[]
                    },
               
                filtroAtivo: false, 
                erros:'',
                carregando:false,
                canObj:{},
                acessosObj:{},

	        }
        },
        methods:{
            search( value ){
                this.dados = value.perfis
            },
         
        },
        mounted()
        {
            this.canObj = JSON.parse(this.can)
            this.acessosObj = JSON.parse(this.acessos)
        },
	};


</script>

