<template>
    <div>
         <div class="row">
            <div class="col-md-12">

                <vc-filtro-pesquisa @pesquisar="search" :filtro="filtro" :filtroPadrao="filtroPadrao" :cookiePrefixo="cookiePrefixo" :url="url">
                    <slot>
                        

                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="nome">Cliente:</label>
                                <multiselect 
                                    v-model="filtro.cliente" 
                                    :options="optionsClientes"
                                    :multiple="true" 
                                    :hide-selected="true"
                                    select-label=""
                                    :option-height="25"
                                    :showNoResults="false"
                                    placeholder="Cliente"
                                ></multiselect>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="nome">Projeto:</label>
                                <multiselect 
                                    v-model="filtro.projeto" 
                                    :options="optionsProjeto"
                                    :multiple="true" 
                                    :showNoResults="false"
                                    :showLabels="false"
                                    :taggable="true" 
                                    tag-placeholder="Adicionar o projeto"
                                    @tag="addTag"
                                    :hide-selected="true"
                                    select-label=""
                                    :option-height="25"
                                    placeholder="Projeto"

                                ></multiselect>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="nome">Produto:</label>                                
                                <multiselect 
                                    v-model="filtro.produto" 
                                    :options="optionsProdutos"
                                    :multiple="true" 
                                    :hide-selected="true"
                                    select-label=""
                                    :option-height="25"
                                    :showNoResults="false"
                                    placeholder="Produto"

                                ></multiselect>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="nome">Endereço:</label>
                                <input type="text" class="form-control input-sm" v-model="filtro.endereco" placeholder="ENDEREÇO" style="text-transform: uppercase;height: 38px;">
                            </div>
                        </div>    
                        
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="nome">IP:</label>
                                <input type="text" class="form-control input-sm" v-model="filtro.ip" placeholder="ip" style="text-transform: uppercase;height: 38px;">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="nome">DNS:</label>
                                <input type="text" class="form-control input-sm" v-model="filtro.dns" placeholder="dns" style="text-transform: uppercase;height: 38px;">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="nome">Tipo de servidor:</label>
                                <multiselect 
                                    v-model="filtro.tipo" 
                                    :options="optionsTipos"
                                    :multiple="true" 
                                    :hide-selected="true"
                                    :showNoResults="false"
                                    select-label=""
                                    :option-height="25" 
                                    placeholder="Tipo de servidor"
                                    
                                ></multiselect>
                            </div>
                        </div>                        

                        <div class="row">
                            
                            <div class="form-group col-md-3">
                                <label for="nome">Status do servidor:</label>
                                 <multiselect 
                                    v-model="filtro.status_servidor" 
                                    :options="optionsStatusServidores"
                                    :multiple="true" 
                                    :hide-selected="true"
                                    :showNoResults="false"
                                    select-label=""
                                    :option-height="25" 
                                    placeholder="Status do servidor"
                                    label="nome"
                                    track-by="id"
                                ></multiselect>

                            </div>
                            
                            <div class="form-group col-md-3">
                                <label for="nome">Status do projeto:</label>
                                <multiselect 
                                    v-model="filtro.status_instalacao" 
                                    :options="optionsStatusInstalacao"
                                    :multiple="true" 
                                    :hide-selected="true"
                                    :showNoResults="false"
                                    select-label=""
                                    :option-height="25" 
                                    placeholder="Status do projeto"
                                    
                                ></multiselect>

                            </div>
                            <div class="form-group col-md-3">
                                <label for="nome">Versão:</label>
                                <multiselect 
                                    v-model="filtro.versao" 
                                    :options="optionsVersao"
                                    :multiple="true" 
                                    :hide-selected="true"
                                    :showNoResults="false"
                                    select-label=""
                                    :option-height="25" 
                                    placeholder="versao"
                                    
                                ></multiselect>
                            </div>
                        </div>



                    </slot>
                    <template slot="exportacao">                        
                        <div class="pull-right" v-if="canObj.exportar">
                            <button type="button" class="btn btn-primary" @click="exportar('xlsx')" title="Exportar para Excel">
                                <i class="fa fa-download"></i> &nbsp;EXCEL
                            </button>
                            <button type="button" class="btn btn-primary" @click="exportar('csv')" title="Exportar para CSV">
                                <i class="fa fa-download"></i> &nbsp;CSV
                            </button>
                        </div>
                    </template>
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
                    :acoes="true" >
                    <template slot="tbody" slot-scope="{ showConfirmeModal, item }">
                        <td>
                            <span class="truncate" :title="item.cliente">{{item.cliente}}</span>
                        </td>
                        <td>
                            <span  class="truncate" :title="item.grupo">
                                {{item.grupo}}
                            </span>
                        </td>
                        <td>
                            {{item.id_projeto}}
                        </td>
                        <td>
                            <span  class="truncate" :title="item.status">
                                {{item.status}}
                            </span>
                        </td>
                        <td>
                            {{item.versao}}
                        </td>
                        <td>
                            {{item.tipo}}
                        </td>
                        <td>
                            {{item.ip}}
                        </td>
                        <td>
                            <span  class="truncate" :title="item.dns"> 
                                 {{item.dns}}                            
                            </span>
                        </td>
                        <td>
                            <small class="label" :style="'background-color:'+item.cor+';'">
                               <i class="fa " :class="item.icone" ></i>&nbsp; 
                               {{ item.nome }}
                           </small>
                        </td>
                       
                        
                    </template>
                </vc-datatable>
            </div>
        </div>

        <!-- Formulario com trigger em angularjs -->
        <form name="exportar" method="post" ref="form">
            <input type="hidden" :value="filtro.cliente" name="cliente">
            <input type="hidden" :value="filtro.produto" name="produto">
            <input type="hidden" :value="filtro.projeto" name="projeto">
            <input type="hidden" :value="filtro.status" name="status">
            <input type="hidden" :value="filtro.tipo" name="tipo">
            <input type="hidden" :value="filtro.ip" name="ip">
            <input type="hidden" :value="filtro.dns" name="dns">
            <input type="hidden" :value="filtro.versao" name="versao">
            <input type="hidden" :value="filtro.status_instalacao" name="status_instalacao">
            <input type="hidden" :value="JSON.stringify( filtro.status_servidor )" name="status_servidor">
            <input type="hidden" :value="filtro.endereco" name="endereco">
        </form>
        

    </div>


</template>

<script>
 	export default {
	 	props:['itens', 'produtos', 'status_servidores', 'clientes', 'tipos', 'status_instalacao', 'versao', 'can'],
		data () {
            return {
                nome:'',
                optionsItens: [],
                optionsProdutos: [],
                optionsStatusServidoresSuperior: [],
                optionsStatusServidores: [],
                optionsStatusItens: [],
                optionsStatusItensSuperior: [],
                optionsClientes: [],
                optionsTipos: [],
                optionsProjeto:[],
                optionsStatusInstalacao:[],
                optionsVersao:[],
                projetoResult:[],
                filtro:{
                    cliente:[],
                    produto:[],
                    projeto:[],
                    status:[],
                    tipo:[],
                    ip:'',
                    dns:'',
                    versao:[],
                    status_instalacao:[],
                    status_servidor:[],
                    por_pagina: 15,
                    pagina: 1,
                    sort:''                    
                },
                filtroPadrao:{
                    cliente:'[]',
                    produto:'[]',
                    projeto:'[]',
                    status:'[]',
                    tipo:'[]',
                    ip:'',
                    dns:'',
                    versao:'[]',
                    status_instalacao:'[]',
                    status_servidor:'[]',
                    por_pagina: 15,
                    pagina: 1,
                    sort:'1 asc',
                },
                thead:[
                    {
                        text: 'Cliente', 
                        coluna:1, 
                        ordena: true, 
                        class: '',
                        width:''
                    },
                    {
                        text: 'Produto', 
                        coluna:2, 
                        ordena: true, 
                        class: '',
                        width:'10%'
                    },
                    {
                        text: 'Projeto', 
                        coluna:3, 
                        ordena: true, 
                        class: '',
                        width:'7%'
                    },
                    {
                        text: 'Status do projeto', 
                        coluna:4, 
                        ordena: true, 
                        class: '',
                        width:'15%'
                    },
                    {
                        text: 'Versão', 
                        coluna:5, 
                        ordena: true, 
                        class: '',
                        width:'7%'
                    },
                    {
                        text: 'Tipo', 
                        coluna:6, 
                        ordena: true, 
                        class: '',
                        width:'10%'
                    },
                    {
                        text: 'IP', 
                        coluna:7, 
                        ordena: true, 
                        class: '',
                        width:'11%'
                    },
                    {
                        text: 'DNS', 
                        coluna:8, 
                        ordena: true, 
                        class: '',
                        width:''
                    },
                     {
                        text: 'Status', 
                        coluna:9, 
                        ordena: true, 
                        class: '',
                        width:'5%'
                    },
                ],
                cookiePrefixo:'monitoramento_servidores_',
                url:'/monitoramento/servidores/',
                dados:{
                    data:[]
                    },
               
                filtroAtivo: false, 
                erros:'',
                carregando:false,
                canObj:{},

	        }
        },
        methods:{
            search( value ){
                this.dados = value.servidores
            },
            addTag (newTag) {
                if( !newTag.match(/^[0-9]+$/) )
                {
                    return false;
                }
                this.optionsProjeto.push(newTag)
                this.projetoResult.push(newTag)
                this.filtro.projeto = this.projetoResult;
            },
            exportar(tipo){
                this.$refs.form.action = this.url + "download/"+tipo;
                this.$refs.form.submit();
            },
            testeMetodo(){
                return 'ok'
            }
         
        },
        mounted()
        {

            this.optionsItens = JSON.parse(this.itens)  
            this.optionsProdutos = JSON.parse(this.produtos)
            this.optionsStatusServidores = JSON.parse(this.status_servidores)            
            this.optionsClientes = JSON.parse(this.clientes)
            this.optionsTipos = JSON.parse(this.tipos)
            this.optionsStatusInstalacao = JSON.parse(this.status_instalacao)
            this.optionsVersao = JSON.parse(this.versao)
            this.canObj = JSON.parse(this.can);            
        
        },
	};


</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style>
    .multiselect__tag,.multiselect__option--highlight,.multiselect__option--highlight:after, .multiselect__tag-icon:focus, .multiselect__tag-icon:hover 
    {
        background: #2b8cb8 !important;
    
    }
    .multiselect__tag-icon:after {
        color: #ccc;
    }
    .multiselect__single 
    {
        font-size: 12px !important;
        font-family: "Source Sans Pro", "Helvetica Neue", Helvetica, Arial, sans-serif !important;
        text-transform: uppercase !important ;
        color: #a4a4a4 !important;
    
    }
    .multiselect__tags
    {
        border-radius: 0 !important;
        box-shadow: none !important;
        border-color: #d2d6de !important;
    
    }
    .optionSelect
    {
        opacity: 0.5;
    }

</style>

