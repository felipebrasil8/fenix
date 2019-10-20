<template>
    <div>        
        <div class="row">
            <div class="col-md-12">

                <div style="margin-top: 15px;">            
                    <vc-filtro-pesquisa @pesquisar="search" :filtro="filtro" :filtroPadrao="filtroPadrao" :cookiePrefixo="cookiePrefixo" :url="url">
                        <slot>
                            <div class="row">
                                <div class="form-group col-sm-3 col-md-3">
                                    <label >Data início:</label>
                                    <div class="input-group date" style="height: 38px;">
                                        <date-picker v-model="filtro.data_de" name="de" :config="config" id="pesquisa_de" style="height: 38px;" readonly></date-picker>
                                        <label class="input-group-addon" for="pesquisa_de">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-sm-3 col-md-3">
                                    <label>Data fim:</label>
                                    <div class="input-group date" style="height: 38px;">
                                        <date-picker v-model="filtro.data_ate" name="ate" :config="config" id="pesquisa_ate" style="height: 38px;" readonly></date-picker>
                                        <label class="input-group-addon" for="pesquisa_ate">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="nome">Usuário:</label> 
                                    <multiselect 
                                        v-model="filtro.usuario" 
                                        :options="optionsUsuarios"
                                        :multiple="true" 
                                        :hide-selected="true"
                                        select-label=""                                    
                                        :option-height="25" 
                                        :showNoResults="false"
                                        label="nome"
                                        placeholder="Usuário"
                                        track-by="id"
                                    >
                                        <template slot="noOptions">A lista está vazia.</template>
                                    </multiselect>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-9 col-md-9">
                                    <label >Observação:</label>
                                    <input type="text" class="form-control input-sm" v-model="filtro.observacao" placeholder="OBSERVAÇÃO" style="text-transform: uppercase;height: 38px;">
                                </div>
                            </div>
                        </slot>
                    </vc-filtro-pesquisa>
                </div>
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
                    :status="false" 
                    :acoes="false" 
                    style="margin-bottom: 0px !important;">
                    <template slot="tbody" slot-scope="{ showConfirmeModal, item }">
                        <td>
                            <span :title="item.dt_inicio">
                                {{item.dt_inicio}}
                            </span>
                        </td>
                        <td>
                            <span :title="item.dt_fim">
                                {{item.dt_fim}}
                            </span>
                        </td>
                        <td>
                            <span :title="item.duracao">
                                {{item.duracao}}
                            </span>
                        </td>
                        <td>
                            <span :title="item.usuario_inclusao_nome">
                                {{item.usuario_inclusao_nome}}
                            </span>
                        </td>
                        <td>
                             <span :title="item.observacao" class="truncate">
                                {{item.observacao}}
                            </span>
                        </td>                            
                    </template>
                </vc-datatable>
            </div>
        </div> 
        
    </div>

</template>

<script>
    import 'eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css';   
    export default {
        props:['servidor', 'id'],
        data () {
            return {                
                servidorObj: {},
                optionsUsuarios: [],
                filtro:{
                    usuario: [],
                    observacao: '',
                    data_de: new Date().getDate()+'/'+(new Date().getMonth()+1)+'/'+new Date().getFullYear(),
                    data_ate: '',
                    por_pagina: 15,
                    pagina: 1,
                    sort:''
                },
                filtroPadrao:{
                    usuario: "[]",
                    observacao: '',
                    data_de: new Date().getDate()+'/'+(new Date().getMonth()+1)+'/'+new Date().getFullYear(),
                    data_ate: '',
                    por_pagina: 15,
                    pagina: 1,
                    sort:'2 desc',
                },
                thead:[
                    {
                        text: 'Data início', 
                        coluna:2, 
                        ordena: true, 
                        class: '',
                        width:''
                    },
                    {
                        text: 'Data fim', 
                        coluna:3, 
                        ordena: true, 
                        class: '',
                        width:''
                    },
                    {
                        text: 'Duração', 
                        coluna:4, 
                        ordena: true, 
                        class: '',
                        width:''
                    },
                    {
                        text: 'Usuário', 
                        coluna:5, 
                        ordena: true, 
                        class: '',
                        width:''
                    },
                    {
                        text: 'Observação', 
                        coluna:7, 
                        ordena: true, 
                        class: '',
                        width:'400px'
                    }
                ],
                cookiePrefixo:'monitoramento_servidores_aba_parada_',
                url:'/monitoramento/servidores/parada_ajax/'+this.id+'/',
                dados:{
                    data:[]
                }, 
                erros:'',
                carregando:false,
                canObj:{},
                // Configuração do campo de data
                config: {
                    format: 'DD/MM/YYYY',
                    useCurrent: false,                    
                    showClose: true,
                    locale: 'pt-br',
                    ignoreReadonly: true,
                    showClear: true
                } 
            }
        },
        methods:
        {
            search( value )
            {
                this.dados = value.servidor_parada
            },
            getDados(  )
            {
                if(this.id != ''){                    
                    
                    let url = '/monitoramento/servidores/aba_parada_dados/'+this.id

                    window.axios.post( url)
                        .then(response=>{ 
                            this.optionsUsuarios = response.data.usuarios;                            
                        });
                }                 
            },        
        },
        computed:{
            
        },
        mounted()
        {
            this.getDados()
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