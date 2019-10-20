<template>
    <div>        
        <div class="row">
            <div class="col-md-12">

                <div style="margin-top: 15px;">            
                    <vc-filtro-pesquisa @pesquisar="search" :filtro="filtro" :filtroPadrao="filtroPadrao" :cookiePrefixo="cookiePrefixo" :url="url">
                        <slot>
                            
                            <div class="row">
                                <div class="form-group col-sm-3 col-md-3">
                                    <label >Vinculado de:</label>
                                    <div class="input-group date" style="height: 38px;">
                                        <date-picker v-model="filtro.data_de" name="de" :config="config" id="pesquisa_de" style="height: 38px;" readonly></date-picker>
                                        <label class="input-group-addon" for="pesquisa_de">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-sm-3 col-md-3">
                                    <label>Vinculado até:</label>
                                    <div class="input-group date" style="height: 38px;">
                                        <date-picker v-model="filtro.data_ate" name="ate" :config="config" id="pesquisa_ate" style="height: 38px;" readonly></date-picker>
                                        <label class="input-group-addon" for="pesquisa_ate">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group col-md-3">
                                    <label for="nome">Alertas:</label>
                                    <multiselect 
                                        v-model="filtro.item" 
                                        :options="optionsItens"
                                        :multiple="true" 
                                        :hide-selected="true"
                                        select-label=""
                                        :option-height="25" 
                                        placeholder="Alertas"
                                        :showNoResults="false"
                                        group-values="itens"
                                        group-label="item"
                                        :group-select="true"
                                        track-by="id"
                                        label="nome"
                                        selectGroupLabel="Clique para selecionar todos"
                                        deselectGroupLabel="Clique para desmarcar todos"
                                        :limit="4"
                                        :limitText="limitText"
                                    ></multiselect>
                                </div>

                            </div>
                            <div class="row">

                                <div class="form-group col-md-3">
                                    <label for="nome">Usuário inclusão:</label> 
                                    <multiselect 
                                        v-model="filtro.inclusao" 
                                        :options="optionsUsuarioinclusao"
                                        :multiple="true" 
                                        :hide-selected="true"
                                        select-label=""                                    
                                        :option-height="25" 
                                        :showNoResults="false"
                                        label="nome"
                                        placeholder="Usuário inclusão"
                                        track-by="id"
                                    >
                                        <template slot="noOptions">A lista está vazia.</template>
                                    </multiselect>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="nome">Usuário exclusão:</label> 
                                    <multiselect 
                                        v-model="filtro.alteracao" 
                                        :options="optionsUsuarioAlteracao"
                                        :multiple="true" 
                                        :hide-selected="true"
                                        select-label=""                                    
                                        :option-height="25" 
                                        :showNoResults="false"
                                        label="nome"
                                        placeholder="Usuário alteração"
                                        track-by="id"
                                    >
                                        <template slot="noOptions">A lista está vazia.</template>
                                    </multiselect>
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
                            <span :title="item.nome">
                                {{item.nome}}
                            </span>
                        </td>
                        <td>
                            <span :title="item.numero_chamado">
                                <a :href="'https://crm.novaxtelecom.com.br/suporte/fluxo/visualizar.php?id='+item.numero_chamado" target="_blank"> {{item.numero_chamado}} </a>
                            </span>
                        </td>
                        <td>
                            <span :title="item.usuario_inclusao">
                                {{ item.usuario_inclusao ? item.usuario_inclusao : 'AUTOMÁTICO' }}
                            </span>
                        </td>
                        <td>
                            <span :title="item.created_at">
                                {{item.created_at}}
                            </span>
                        </td>
                        <td>
                            <span :title="item.usuario_alteracao">
                                {{ item.usuario_alteracao ? item.usuario_alteracao : (item.updated_at != '-' ? 'AUTOMÁTICO' : '-') }}
                            </span>
                        </td>
                        <td>
                            <span :title="item.updated_at">
                                {{ item.updated_at || '-' }}
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
                optionsItens: [],
                optionsStatusItens: [],
                filtro:{
                    item: [],
                    status: [],
                    data_de: new Date().getDate()+'/'+(new Date().getMonth()+1)+'/'+new Date().getFullYear(),
                    data_ate: '',
                    alteracao: [],
                    inclusao: [],
                    por_pagina: 15,
                    pagina: 1,
                    sort:''
                },
                filtroPadrao:{
                    item: "[]",
                    status: "[]",
                    data_de: new Date().getDate()+'/'+(new Date().getMonth()+1)+'/'+new Date().getFullYear(),
                    data_ate: '',
                    alteracao: "[]",
                    inclusao: "[]",
                    por_pagina: 15,
                    pagina: 1,
                    sort:'2 desc',
                },
                thead:[
                    {
                        text: 'Item', 
                        coluna:6, 
                        ordena: true, 
                        class: '',
                        width:''
                    },
                    {
                        text: 'Chamado', 
                        coluna:7, 
                        ordena: true, 
                        class: '',
                        width:''
                    },
                    {
                        text: 'Usuário inclusão', 
                        coluna:4, 
                        ordena: true, 
                        class: '',
                        width:''
                    },
                    {
                        text: 'Data inclusão', 
                        coluna:2, 
                        ordena: true, 
                        class: '',
                        width:''
                    },
                    {
                        text: 'Usuário exclusão',
                        coluna:5, 
                        ordena: true, 
                        class: '',
                        width:''
                    },
                    {
                        text: 'Data exclusão', 
                        coluna:3, 
                        ordena: true, 
                        class: '',
                        width:''
                    }
                ],
                cookiePrefixo:'monitoramento_servidores_aba_chamado_vinculado_',
                url:'/monitoramento/servidores/chamado_vinculado_ajax/'+this.id+'/',
                dados:{
                    data:[]
                }, 
                erros:'',
                carregando:false,
                canObj:{},
                optionsUsuarioinclusao:[],
                optionsUsuarioAlteracao:[],
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
                this.dados = value.chamados
            },
            getDados(  )
            {
                if(this.id != ''){                    
                    
                    let url = '/monitoramento/servidores/aba_chamados_vinculados_dados/'+this.id

                    window.axios.post( url)
                        .then(response=>{ 
                            this.optionsItens = response.data.itens;

                            let alertaItens = {item: 'Itens', itens: []};
                            // let alertaServidor = {item: 'Alerta', itens: []};
                            let alerta = [];

                            this.optionsItens.forEach(item=>{
                                // if( item.id === null ){
                                //     alertaServidor.itens.push(item);
                                // }
                                // else
                                // {
                                    alertaItens.itens.push(item);
                                // }
                            })

                            // alerta.push(alertaServidor);
                            alerta.push(alertaItens);

                            this.optionsItens = alerta

                            this.optionsUsuarioinclusao = response.data.usuariosInclusao                           
                            this.optionsUsuarioAlteracao = response.data.usuariosAlteracao                            
                        });
                }                 
            },
            limitText (count) {
                return `e mais ${count} alertas`
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




