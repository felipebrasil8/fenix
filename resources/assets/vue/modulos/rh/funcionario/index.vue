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
                                <label for="nome">E-mail:</label>
                                <input type="text" class="form-control input-sm" v-model="filtro.email" placeholder="E-mail">
                            </div>

                            <div class="form-group col-md-3">
                                <label><strong>Status:</strong></label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>
                                            <input type="radio" name="status" value="true" v-model="filtro.status" checked="checked"> Ativo
                                        </label>
                                        &nbsp;
                                        <label>
                                            <input type="radio" name="status" value="false" v-model="filtro.status" > Inativo
                                        </label>
                                        &nbsp;
                                        <label>
                                            <input type="radio" name="status" value="todos" v-model="filtro.status" > Todos
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row" v-if="canObj.abas">
                            <div class="form-group col-md-3">
                                <label>Cargo:</label>
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <select class="form-control input-sm" v-model="filtro.cargo" style="width: 100%;">
                                            <option value=""></option>                           
                                            <option v-for="item in cargosObj" :value="item.id">{{item.nome}}</option>                           
                                        </select>                                        
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Gestor:</label>                                
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <select class="form-control input-sm" v-model="filtro.gestor" style="width: 100%;">
                                            <option value=""></option>                           
                                            <option v-for="item in gestoresObj" :value="item.id">{{item.nome}}</option>                           
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </slot>                    
                    <div class="pull-right" slot="exportacao" v-if="canObj.exportar">
                        <button type="button" class="btn btn-primary" @click="xlsx()" title="Exportar para Excel">
                            <i class="fa fa-download"></i> &nbsp;EXCEL
                        </button>
                        <button type="button" class="btn btn-primary" @click="csv()" title="Exportar para CSV">
                            <i class="fa fa-download"></i> &nbsp;CSV
                        </button>
                    </div>

                </vc-filtro-pesquisa>        
                


                <vc-datatable :dados="dados" :url="url" :filtro="filtro" :canObj="canObj" :cookiePrefixo="cookiePrefixo" :thead="thead" status="true" :acoes="true">
                                      
                    <template slot="tbody" slot-scope="{ showConfirmeModal, item }">
                        <td style="width:90%; ">
                            <span class="truncate" :title="item.nome">
                                <span :title="item.nome">
                                    {{item.nome}}
                                </span>
                            </span>
                        </td>
                        <td style="width:90%; ">
                            <span class="truncate" >
                                <span :title="item.email">
                                    {{item.email}}
                                </span>
                            </span>
                        </td>
                        <td class="text-center" >
                            <span v-tippy="{ html: '#tooltipContent'+item.id, interactive:true, reactive:true, placement: 'right', arrow: true, theme:'tooltip' }"> 
                                 <i class="fa fa-camera" @click="$modal.show('modal-change-avatar', item.id )" v-if="canObj.avatar == true"></i>
                                 <i class="fa fa-camera" v-else></i>
                            </span>
                            <div :id="'tooltipContent'+item.id" >
                               <img :src="'/rh/funcionario/avatar-grande/'+item.id" style="width: 100px;">
                            </div>
                        </td>
                    </template>
                </vc-datatable>
    

            </div>
        </div> 


        <vc-modal-change-avatar ></vc-modal-change-avatar>
        <!-- Formulario com trigger em angularjs -->
        <div class="row" >
            <form name="exportar" method="post" ref="form">
                <input type="hidden" :value="filtro.nome" name="nome">
                <input type="hidden" :value="filtro.email" name="email">
                <input type="hidden" :value="filtro.cargo" name="cargo">
                <input type="hidden" :value="filtro.gestor" name="gestor">
                <input type="hidden" :value="filtro.status" name="ativo">
            </form>
        </div>
        <div v-if="canObj.cadastrar">
            <a href="/rh/funcionario/create" title="Cadastrar">
                <button type="button" class="btn btn-primary">Cadastrar</button>
            </a>
        </div>
    
    </div>


</template>

<script>
 	export default {
	 	props:['gestores', 'cargos', 'can'],
		data () {
            return {
                //Objeto que será enviado por ajax
                filtro:{
                    nome:'',
                    email: '',
                    status:'true',
                    cargo: '',
                    gestor: '',
                    por_pagina: 15,
                    pagina: 1,
                    sort:'',
                },
                //Objeto que será utilizado no limpar()
                filtroPadrao:{
                    nome:'',
                    email: '',
                    status:'true',
                    cargo: '',
                    gestor: '',
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
                        text: 'Email', 
                        coluna:4, 
                        ordena: true, 
                        class: '',
                        width:''
                    },
                    {
                        text: 'Avatar', 
                        coluna: '', 
                        ordena: false, 
                        class: 'text-center',
                        width:'100px'
                    }
                ],
                cookiePrefixo:'rh_funcionario_',
                url:'/rh/funcionario/',
                dados:{},                
                gestoresObj: {},
                cargosObj: {},
                canObj: {}
	        }
        },
        methods:{
            search( value )
            {
                this.dados = value.funcionarios
            },
            csv(){            
                this.$refs.form.action = "/rh/funcionario/download/csv";  
                this.$refs.form.submit();
            },
            xlsx(){            
                this.$refs.form.action = "/rh/funcionario/download/xlsx";
                this.$refs.form.submit();
            }
        },
        mounted()
        {
            this.canObj = JSON.parse(this.can);
            this.gestoresObj = JSON.parse(this.gestores);
            this.cargosObj = JSON.parse(this.cargos);
        },
	};


</script>

<style>
    .tippy-tooltip.tooltip-theme {
        background-color: rgba(255, 255, 255, 0) !important;
        box-sizing: none;
        border-radius: 0px;
        box-shadow: none;
    }
</style>
