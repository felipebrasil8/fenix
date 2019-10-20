<template>
    <div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-default  collapsed-box">
                   <div class="box-header with-border">
                        <div class="row">
                            <div  class="col-md-4 col-xs-12">
                                <div class="row">
                                    <div  class="col-md-12">
                                        <b>Servidores:</b>
                                    </div>
                                </div>
                                <div class="row">
                                    <div  class="col-md-12">
                                        <small class="label cursor-pointer" style="margin-top:5px;margin-bottom:5px;display:inline-block;font-size:13px;margin-right: 10px;" v-for="x in optionsStatusServidoresSuperior"  :style="'background-color:'+x.cor" @click="setCookieServidores( x )"
                                         :class="optionSelecionado( 'SERVIDOR', x )">
                                           <i class="fa" :class="x.icone" >&nbsp;</i>
                                           {{x.nome}} - {{x.total}} 
                                        </small>
                                    </div> 
                                </div>
                            </div> 
                            <div  class="col-md-4 col-xs-12">
                                <div class="row">
                                    <div  class="col-md-12">
                                        <b>Itens:</b>
                                    </div>
                                </div>
                                <div class="row">
                                    <div  class="col-md-12">
                                         <small class="label cursor-pointer" style="margin-top:5px;margin-bottom:5px;display:inline-block;font-size:13px;margin-right: 10px;" v-for="x in optionsStatusItensSuperior"  :style="'background-color:'+x.cor" @click="setCookieItens( x )" :class="optionSelecionado( 'ITEM', x )">
                                           <i class="fa" :class="x.icone" >&nbsp;</i>
                                           {{x.nome}} - {{x.total}} 
                                        </small>
                                    </div> 
                                </div>
                            </div>

                            <div  class="col-md-2 col-xs-12">
                                <div class="row">
                                    <div  class="col-md-12">
                                        <b>Atalhos:</b>
                                    </div>
                                </div>
                                <div class="row">
                                    <div  class="col-md-12">   
                                        <small class="label cursor-pointer" style="margin-top:5px;margin-bottom:5px;display:inline-block;background-color:#6f7277;font-size:13px;margin-right: 10px;" @click="setCookieAtalho()" :class="optionSelecionado( 'ALARMES' )">
                                            ALARMES
                                        </small>         
                                        <small class="label cursor-pointer" style="margin-top:5px;margin-bottom:5px;display:inline-block;background-color:#6f7277;font-size:13px;margin-right: 10px;" @click="setCookieAtalhoSemAcao()" :class="optionSelecionado( 'ALARMES_ACAO' )">
                                            ALARMES SEM AÇÃO
                                        </small>                    
                                   </div> 
                                </div>
                            </div> 

                             <div  class="col-md-2 col-xs-12">
                                <div class="row">
                                    <div  class="col-md-12">
                                        <b>Atualizar itens automaticamente:</b>
                                    </div>
                                </div>
                                <div class="row">
                                    <div  class="col-md-12">
                                        <label class="switch" style="margin-top:5px;"> 
                                          <input name='atualiza' v-model="atualizaAutomatico" @click="setCookieAtualizaPagina(atualizaAutomatico)" type="checkbox">
                                          <span class="slider round"></span>
                                        </label>
                                        <span class="overlay" v-if="carregando == 'true'" style="margin-left: 5px;">
                                            <i class="fa fa-refresh fa-spin"></i>
                                        </span> 
                                    </div>    
                                </div>
                            </div> 

                        </div>
                     
                    </div>
                <div class="overlay" v-if="carregandoTotais">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <vc-filtro-pesquisa @pesquisar="search" @pesquisabotton="pesquisa" @limpar="limpaVariaveis()" :filtro="filtro" :filtroPadrao="filtroPadrao" :cookiePrefixo="cookiePrefixo" :url="url" @getCookies="getCookies">

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

                                >
                                <template slot="noOptions">A lista está vazia.</template>
                                </multiselect>
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
                                <label for="nome">Parada programada:</label>
                                <multiselect 
                                    v-model="filtro.parada_programada" 
                                    :options="optionsParadaProgramada"
                                    :multiple="true" 
                                    :hide-selected="true"
                                    :showNoResults="false"
                                    select-label=""
                                    :option-height="25" 
                                    placeholder="Status parada pogramada"
                                ></multiselect>
                            </div>
                             <div class="form-group col-md-3">
                                <label for="nome">Alarme persistente:</label>
                                <multiselect 
                                    v-model="filtro.alarme_persistente" 
                                    :options="optionsAlarmesPersistentes"
                                    :multiple="true" 
                                    :hide-selected="true"
                                    :showNoResults="false"
                                    select-label=""
                                    :option-height="25" 
                                    placeholder="Alarme Persistente"
                                ></multiselect>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="nome">Chamado vinculado:</label>
                                <multiselect 
                                    v-model="filtro.chamado_vinculado" 
                                    :options="optionsAlarmesPersistentes"
                                    :multiple="true" 
                                    :hide-selected="true"
                                    :showNoResults="false"
                                    select-label=""
                                    :option-height="25" 
                                    placeholder="Chamado vinculado"
                                ></multiselect>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="nome">Chamado:</label>                            
                                <input type="text" class="form-control input-sm" v-model="filtro.chamado" placeholder="chamado" style="text-transform: uppercase;height: 38px;">
                            </div>
                        </div>
                        <div class="row" v-if="filtroOcultaOpcoesItens">

                            <div class="form-group col-md-3">
                                <label for="nome">Status servidor:</label>
                                <multiselect 
                                    v-model="filtro.status_servidor" 
                                    :options="optionsStatusServidores"
                                    :multiple="true" 
                                    :hide-selected="true"
                                    select-label=""
                                    :option-height="25" 
                                    label="nome"
                                    :showNoResults="false"
                                    track-by="id"
                                    placeholder="Status servidor"                                    


                                ></multiselect>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="nome">Status itens:</label> 
                                <multiselect 
                                    v-model="filtro.status" 
                                    :options="optionsStatusItens"
                                    :multiple="true" 
                                    :hide-selected="true"
                                    select-label=""                                    
                                    :option-height="25" 
                                    :showNoResults="false"
                                    label="nome"
                                    placeholder="Status itens"
                                    track-by="id"
                                >                                    
                                </multiselect>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="nome">Item:</label>
                                <multiselect 
                                    v-model="filtro.item" 
                                    :options="optionsItens"
                                    :multiple="true" 
                                    :hide-selected="true"
                                    select-label=""
                                    :option-height="25" 
                                    placeholder="Item"
                                    :showNoResults="false"
                                    label="nome"
                                    track-by="identificador"
                                ></multiselect>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="nome">Mensagem:</label>                            
                                <input type="text" class="form-control input-sm" v-model="filtro.mensagem" placeholder="mensagem" style="text-transform: uppercase;height: 38px;">
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
                    :acoes="false"
                    :abreTodos="abreTodos"
                    @abreTodosItens="abreTodosItens"
                     >
                    <template slot="tbody" slot-scope="{ showConfirmeModal, item }">
                     
                        <tr class="datatebleVue pointer background_table_itens">
                          <!--   <td>
                                <input type="checkbox">
                            </td> -->
                            <td>
                                <span  class="truncate pointer" :title="item.cliente">                                    
                                    <i class="fa " :class="aberto[item.id]?'fa-minus-square':'fa-plus-square'" :style="item.itens.length == 0 ? 'color:#ccc':'color:#2b8cb8'" style="font-size:12px;" aria-hidden="true" @click="item.itens.length == 0 ? '':abreItens(item.id)"></i>&nbsp;
                                    <a :href="'/monitoramento/servidores/'+item.id" target="_blank" style="color:#333">
                                        {{item.cliente}}
                                    </a>
                                </span>
                            </td>
                            <td @click="item.itens.length == 0 ? '':abreItens(item.id)">
                                <span  class="truncate" :title="item.ultima_coleta" v-html="item.ultima_coleta"></span>
                            </td>
                            <td @click="item.itens.length == 0 ? '':abreItens(item.id)">
                                <small class="label" :style="'background-color:'+item.cor+';'" :class="(item.contador_falhas <= alarmePersistente && item.alarme ) ?'optionSelect':''" :title="item.alarme ? item.contador_falhas+' coletas com falha' : ''">

                                   <i class="fa " :class="item.icone" ></i>&nbsp; 
                                   {{ item.nome }}
                               </small>
                            </td>
                            <td @click="item.itens.length == 0 ? '':abreItens(item.id)">
                                <span  :title="item.dt_status" v-html="ajustaData(item.dt_status)"></span>
                            </td>
                            <td @click="item.itens.length == 0 ? '':abreItens(item.id)">
                                <span  class="truncate" :title="montaMensagem(item)"  v-html="montaMensagem(item)">
                                </span>
                            </td>
                            <td @click="item.itens.length == 0 ? '':abreItens(item.id)">
                                {{item.grupo}}
                            </td>
                            <td @click="item.itens.length == 0 ? '':abreItens(item.id)">
                                {{item.tipo}}
                            </td>
                            <td>
                                <span>
                                    <a :href="'https://crm.novaxtelecom.com.br/operacoes/fluxo/visualizar.php?id='+item.id_projeto" target="_blank">
                                        <small class="label" style="background-color:rgb(243, 156, 18);" :title="item.id_projeto+' - '+item.status" v-if="item.status != 'INSTALADO'" >
                                           <i class="fa fa-exclamation-triangle"  ></i>&nbsp; 
                                            {{item.id_projeto}}
                                        </small>
                                        <small class="label" style="background-color:rgb(0, 166, 90);" :title="item.id_projeto+' - '+item.status" v-else>
                                            {{item.id_projeto}}
                                        </small>
                                    </a>
                                </span>
                            </td>
                            <!-- AÇÕES - DESCOMENTAR QUANDO INSERIR A COLUNA DE AÇÕES -->
                            <td class="pull-right">
                                <span v-if="can.chamado_vinculados_visualizar && item.alarme && item.itens.length > 0 " >
                                    <small v-if="item.has_chamado > 0" class="label" title="Chamado vinculado" @click.prevent="item.alarme ? $modal.show('modal-vincular-chamado', {'alertas': false, 'item': item} ): ''" style="background-color:#00a65a; font-size: 100%; padding: .1em .4em .1em;">
                                       <i class="fa fa-user-times" style="font-size: 11px;"></i> 
                                    </small>
                                    
                                    <small v-else class="label" title="Vincular chamado" @click.prevent="can.chamado_vinculados_cadastrar && item.alarme ? $modal.show('modal-vincular-chamado', {'alertas': false, 'item': item} ) : ''" style="background-color:#b5b7b9; font-size: 100%; padding: .1em .4em .1em;">
                                       <i class="fa fa-user-plus" style="font-size: 11px;"></i> 
                                    </small>
                               
                                </span>

                                &nbsp;


                                <span v-if="can.monitoramento_visualizar">
                                    <small v-if="item.paradas_programadas.length > 0 ? item.paradas_programadas[0].parada_programada : ''" class="label" title="Parada programada no momento" @click.prevent="$modal.show('modal-parada-programada', item)" style="background-color:#e44c42; font-size: 100%; padding: .1em .4em .1em;">
                                       <i class="fa fa-clock-o"></i> 
                                    </small>
                                    
                                    <small v-else-if="item.paradas_programadas.length == 0 " class="label" title="Cadastrar parada programada" @click.prevent="can.monitoramento_cadastrar && $modal.show('modal-parada-programada', item)" style="background-color:#b5b7b9; font-size: 100%; padding: .1em .4em .1em;">
                                       <i class="fa fa-clock-o"></i> 
                                    </small>

                                    <small v-else class="label" title="Parada programada agendada" @click.prevent="$modal.show('modal-parada-programada', item)" style="background-color:#00a65a; font-size: 100%; padding: .1em .4em .1em;">
                                       <i class="fa fa-clock-o"></i> 
                                    </small>                                    
                                </span>

                                &nbsp;
                                
                                <small v-if="can.monitoramento_coleta_manual" class="label" title="Atualizar manualmente" @click.prevent="atualizandoManualmente(item.id) ? '': coletaServidor(item.id)" style="background-color:#b5b7b9; font-size: 100%; padding: .1em .4em .1em;" :class="atualizandoManualmente(item.id) ? 'optionSelect':''">
                                    <i class="fa fa-refresh" :class="atualizandoManualmente(item.id) ? 'fa-pulse':'' "></i>
                                </small>
                               
                                &nbsp;
                                
                                
                            </td>
                        </tr>
                        <tr :style="esconde(item.id)">
                            <td colspan="9" style="padding: 0">
                                <div class="table-responsive">  
                                    <table class="table table-striped table-hover table-sm" style="table-layout:fixed; width: 100%;">
                                        <thead>
                                            <!-- <th width="2%"></th> -->
                                            <th width="40%"></th>
                                            <th width="150px"></th>
                                            <th width="110px"></th>
                                            <th width="110px"></th>
                                            <th width="60%"></th>
                                            <th width="110px"></th>
                                            <th width="120px"></th>
                                            <th width="80px"></th>
                                            <!-- AÇÕES - DESCOMENTAR QUANDO INSERIR A COLUNA DE AÇÕES -->
                                            <th width="115px"></th>
                                        </thead>
                                        <tbody >
                                            <tr v-for="alertas in item.itens" class="datatebleVueInterno">
                                                
                                               <!--  <td>
                                                   <input type="checkbox">
                                               </td> -->
                                                <td>
                                                    <span  class="truncate" :title="alertas.nome" >
                                                        &nbsp;<i class="fa fa-reply fa-rotate-180" aria-hidden="true" style="color:#ccc;"></i>&nbsp; 
                                                        {{alertas.nome}}  
                                                    </span>
                                                </td>
                                                <td>
                                                    <span  class="truncate" :title="alertas.ultima_coleta">
                                                        {{alertas.ultima_coleta}}  
                                                    </span>
                                                </td>   
                                                <td>
                                                    <small class="label" :style="'background-color:'+alertas.cor+';'" :class="(alertas.contador_falhas <= alarmePersistente && alertas.alarme ) ?'optionSelect':''" :title="alertas.alarme ? alertas.contador_falhas+' coletas com falha' : ''">
                                                       <i class="fa " :class="alertas.icone" ></i>&nbsp; 
                                                       {{ alertas.status }}
                                                   </small>
                                               </td>
                                                <td>
                                                    <span  :title="item.dt_status" v-html="ajustaData(alertas.dt_status)"></span>
                                                   
                                                </td>
                                                 <td colspan="4">
                                                    <span  class="truncate" :title="alertas.mensagem" v-html="alertas.mensagem"></span>
                                                </td>
                                                <!-- AÇÕES - DESCOMENTAR QUANDO INSERIR A COLUNA DE AÇÕES -->
                                               <td class="pull-right" style=" padding-right: 0px !important;">
                                                <span class="ajustaPaddingAcao pointer">
                                                    
                                                    <span v-if="can.chamado_vinculados_visualizar && !item.alarme && alertas.alarme" >
                                                        <small v-if="alertas.chamado_vinculado != null" class="label" title="Chamado vinculado" @click.prevent="$modal.show('modal-vincular-chamado', {'alertas': alertas, 'item': item} )" style="background-color:#00a65a; font-size: 100%; padding: .1em .4em .1em;">
                                                           <i class="fa fa-user-times" style="font-size: 11px;"></i> 
                                                        </small>
                                                        
                                                        <small v-else class="label" title="Vincular chamado" @click.prevent="can.chamado_vinculados_cadastrar && $modal.show('modal-vincular-chamado', {'alertas': alertas, 'item': item} )" style="background-color:#b5b7b9; font-size: 100%; padding: .1em .4em .1em;">
                                                           <i class="fa fa-user-plus" style="font-size: 11px;"></i> 
                                                        </small>
                                                    </span>
                                                </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        
                    </template>
                 
                </vc-datatable>
            </div>
        </div>
        <vc-parada-programada-modal :can="can"></vc-parada-programada-modal>
        <vc-vincular-chamado-modal :can="can"></vc-vincular-chamado-modal>
    </div>
</template>
<script>
    import { filtroBus } from './../../util/filtroDataTable.js';
    import carregandoStore from './../../util/carregandoStore.js';
 	
    export default {
	 	props:['itens', 'produtos', 'status_servidores', 'status_itens', 'clientes', 'tipos'],
		data () {
            return {                
                can: [],
                nome:'',
                carregando:false,
		        optionsItens: [],
                optionsProdutos: [],
                optionsStatusServidoresSuperior: [],
                optionsStatusServidores: [],
                optionsStatusItens: [],
                optionsStatusItensSuperior: [],
                optionsClientes: [],
                optionsTipos: [],
                optionsProjeto:[],
                optionsParadaProgramada: [],
                optionsAlarmesPersistentes: [],
                atualizaAutomatico: true,
                projetoResult:[],
                filtroStatusItens:[],
                filtroStatusServidores:[],
                filtroOcultaOpcoesItens:true,
                alarmePersistente: '',
                semCookie:true,
                filtroAberto:true,
                optionSelect: false,
                carregandoTotais: true,
                abreTodos:0,
                filtro:{
                    cliente: [],
                    projeto: [],
                    produto: [], 
                    ip: '',
                    dns: '',
                    status: [],
                    status_servidor: [],
                    tipo: [],
                    item: [],
                    endereco: '',
                    mensagem: '', 
                    parada_programada: [],
                    alarme_persistente: [],
                    chamado_vinculado: [],
                    chamado: '',
                    alarmes_acao: false,
                    alarmes:false,
                    por_pagina: 15,
                    pagina: 1,
                    sort:'',
                },
                filtroPadrao:{
                    cliente: "[]",
                    projeto: "[]",
                    produto: "[]", 
                    ip:'',
                    dns: '',
                    status: "[]",
                    status_servidor: "[]",
                    tipo: "[]",
                    item: "[]",
                    endereco: '',
                    mensagem: '',
                    parada_programada: "[]",
                    alarme_persistente: "[]",
                    chamado_vinculado: "[]",
                    chamado: '',
                    alarmes_acao: false,
                    alarmes: false,
                    por_pagina: 15,
                    pagina: 1,
                    sort:'1 asc',
                },
                thead:[
                    // {
                    //     text: '<input type="checkbox">', 
                    //     coluna:20, 
                    //     ordena:false, 
                    //     class: '',
                    //     width:'2%'
                    // },
                    // {
                    //     text: 'Cliente', 
                    //     coluna:1, 
                    //     ordena:true, 
                    //     class: '',
                    //     width:'40%'
                    // },                    
                    {
                        text: 'Última coleta', 
                        coluna:13, 
                        ordena: true, 
                        class: '',
                        width:'150px'
                    },
                    {
                        text: 'Status', 
                        coluna:12, 
                        ordena: true, 
                        class: '',
                        width:'110px'
                    },
                    {
                        text: 'Duração', 
                        coluna:4, 
                        ordena: true, 
                        class: '',
                        width:'110px'
                    },
                    {
                        text: 'Mensagem', 
                        coluna:21, 
                        ordena:false, 
                        class: '',
                        width:'60%'
                    },
                    {
                        text: 'Produto', 
                        coluna:2, 
                        ordena: true, 
                        class: '',
                        width:'110px'
                    },
                    {
                        text: 'Tipo Servidor', 
                        coluna:3, 
                        ordena: true, 
                        class: '',
                        width:'120px'
                    },
                    {
                        text: 'Projeto', 
                        coluna:11, 
                        ordena: true, 
                        class: '',
                        width:'80px'
                    },
                    // <!-- AÇÕES - DESCOMENTAR QUANDO INSERIR A COLUNA DE AÇÕES -->
                    {
                        text: 'Ações', 
                        coluna:22, 
                        ordena: false, 
                        class: 'text-center',
                        width:'115px'
                    },
                ],
                cookiePrefixo:'monitoramento_itens_',
                url:'/monitoramento/itens/',
                coletandoManualmente:{},
                dados:{
                    data:[]
                    },
                filtroAtivo: false, 
                erros:'',
                carregando:false,
                canObj:{},
                aberto:[],

	        }
        },
        methods:{
            search( value ){
                this.dados = value.servidores
                this.optionsStatusServidoresSuperior = value.statusServidores
                this.optionsStatusItensSuperior = value.statusItens
                this.can = value.can
                this.alarmePersistente = value.alarmePersistente
                this.setAberto(this.dados.data)
                this.coletandoManualmente = {}
                this.carregandoTotais = false
            },
            montaMensagem( x ){
                let s = ''
                
                if(x.mensagem){
                    s = ' - '+x.mensagem
                }
                
                return x.ip+':'+x.porta_api+' - '+x.dns+s

            },
            setAberto( servidores ){
                let a = false 
                
                servidores.forEach( item=>{
                    let s = item.nome
                    a = false
                    
                    if ( this.abreTodos == 0 )
                    {

                        item.itens.forEach( x=>{
                            if (s != x.status ){
                                a = true
                            }
                        })

                        if ( a || this.filtro.mensagem != '' )
                        {
                            this.$set(this.aberto, item.id, true)
                        }
                        else
                        {
                            this.$set(this.aberto, item.id, false)
                        }  
                    

                    }
                    else if ( this.abreTodos == 1 )
                    {
                            this.$set(this.aberto, item.id, false)
                    }
                    else if( this.abreTodos == 2 )
                    {
                            this.$set(this.aberto, item.id, true)
                    }


                })  
            },
            abreItens( id ){
                if(this.aberto[id]){
                    this.$set(this.aberto, id, false)
                }else{
                    this.$set(this.aberto, id, true)
                }
            },
            esconde( id ){
                if(this.aberto[id]){
                    return '';
                }
                return 'display:none';
            },
            ajustaData(data){
                if ( data !== null ){
                    
                    let dataSplit
                    
                    dataSplit = data.split(" ")
                    if(dataSplit.length > 1) {
                        return dataSplit[0]+' '+dataSplit[1] 
                    }
                    return dataSplit[0]
                    
                }
                return data

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
            setCookieAtualizaPagina(valor){
                this.$cookie.set( this.cookiePrefixo+"atualiza_pagina" , !valor , 1)
                if(!valor){
                    this.pesquisaAtualiza()
                }
            },
            getCookieItensTodosAbertos()
            {
                if ( typeof this.$cookie.get( this.cookiePrefixo+"itens_todos_abertos" ) !== "undefined")
                {
                    let v = this.$cookie.get( this.cookiePrefixo+"itens_todos_abertos" )
                    if( v == '0'  )
                    {
                        this.abreTodos = 0 
                    }
                    else if( v =='1' )
                    {
                        this.abreTodos = 1 
                    }
                    else if( v == '2' )
                    {
                        this.abreTodos = 2 
                    }
                }
                else
                {
                    this.$cookie.set( this.cookiePrefixo+"itens_todos_abertos" ,this.abreTodos , 1)
                }
            },

            getCookieAtualizaPagina()
            {
                if ( typeof this.$cookie.get( this.cookiePrefixo+"atualiza_pagina" ) !== "undefined")
                {
                    this.atualizaAutomatico = (this.$cookie.get( this.cookiePrefixo+"atualiza_pagina" ) == 'false')? false:true;
                }
                else
                {
                    this.$cookie.set( this.cookiePrefixo+"atualiza_pagina" , this.atualizaAutomatico , 1)
                }
            },
            getCookieOcultaFiltroItens()
            {
                if ( typeof this.$cookie.get( this.cookiePrefixo+"oculta_filtro_status" ) !== "undefined")
                {
                    this.filtroOcultaOpcoesItens = (this.$cookie.get( this.cookiePrefixo+"oculta_filtro_status" ) == "false")? false:true;
                }
                else
                {
                    this.$cookie.set( this.cookiePrefixo+"oculta_filtro_status" , this.filtroOcultaOpcoesItens , 1)

                }
            },
            getCookieOcultaFiltroAberto()
            {
                if ( typeof this.$cookie.get( this.cookiePrefixo+"filtro_aberto" ) !== "undefined")
                {
                    this.filtroAberto = (this.$cookie.get( this.cookiePrefixo+"filtro_aberto" ) == "false")? false:true;
                }
                else
                {
                    this.$cookie.set( this.cookiePrefixo+"filtro_aberto" , this.filtroAberto , 1)

                }
            },
            atualizaPagina(){
          
                let s = true
                for (var prop in this.coletandoManualmente) {
                    if( this.coletandoManualmente[prop] ){
                        s = false
                    }
                }

                if ( s ){
                    this.pesquisaAtualiza()
                }

                window.setTimeout(() => {
                    if(this.atualizaAutomatico){
                       
                        this.atualizaPagina()
                    }
                }, 20000);
            },
            pesquisaAtualiza(){
                filtroBus.$emit('editTable')

            },
            limpaVariaveis(){
                this.$cookie.remove( this.cookiePrefixo+'oculta_filtro_status')
                this.abreTodos = 0;
                this.filtroOcultaOpcoesItens = true
                this.filtroStatusServidores = []
                this.filtroStatusItens = []
                this.filtro.status_servidor = []
                this.filtro.status = []
                this.$cookie.set( this.cookiePrefixo+"itens_todos_abertos" ,this.abreTodos , 1)

            },
            pesquisa(){
                this.filtroAberto = true
                this.$cookie.set( this.cookiePrefixo+"filtro_aberto" , this.filtroAberto , 1)

            },
            setCookieServidores(tag)
            {
                if (this.filtro.alarmes)
                {
                    this.setCookieAtalho()
                }

                if (this.filtro.alarmes_acao)
                {
                    this.setCookieAtalhoSemAcao()
                }
                
                this.verificaFiltroFechado() 
                let x = this.filtro.status_servidor.findIndex(i => i.id === tag.id)
                if ( x === -1 )
                {
                    this.filtro.status_servidor.push(tag)
                } 
                else
                {
                    
                    this.filtro.status_servidor = this.filtro.status_servidor.filter(item=>{
                        if (item.id != tag.id){
                           return item
                        }
                    }) 
                }               
                this.$cookie.set( this.cookiePrefixo+"status_servidor" , this.filtro.status_servidor , 1)
                filtroBus.$emit('editTable')

            },
            verificaFiltroFechado(){
                this.filtroAberto = false

                for( var prop in this.filtro){
                    if(prop != 'por_pagina' && prop != 'pagina' && prop != 'sort' && prop != 'status_servidor' && prop != 'status'){
                        
                        if ( typeof this.filtro[prop] == "object"){
                       
                            if( this.filtro[prop].toString() != JSON.parse(this.filtroPadrao[prop]).toString() ){
                                this.filtroAberto = true
                            }     

                        }else{
                            if( this.filtro[prop] != this.filtroPadrao[prop] ){
                                this.filtroAberto = true
                            }     
                        }
                        
                    }
                }
                
                this.$cookie.set( this.cookiePrefixo+"filtro_aberto" , this.filtroAberto , 1)
            },
            setCookieItens(tag)
            {
                if (this.filtro.alarmes)
                {
                    this.setCookieAtalho()
                }
                if (this.filtro.alarmes_acao)
                {
                    this.setCookieAtalhoSemAcao()
                }

                this.verificaFiltroFechado() 
                let s = this.filtro.status.findIndex(i => i.id === tag.id) 
                if ( s === -1 )
                {
                    this.filtro.status.push(tag)
                }  
                else
                {
                    
                    this.filtro.status = this.filtro.status.filter(item=>{
                        if (item.id != tag.id){
                           return item
                        }
                    }) 
                }   
                
                this.$cookie.set( this.cookiePrefixo+"status" , this.filtro.status , 1)
                filtroBus.$emit('editTable')

            },
            customStatus ({ icone, nome }) {
              return `<i class="fa '+icone+'"></i> – ${nome}`
            },
            setCookieAtalho()
            {
                if (this.filtro.alarmes_acao)
                {
                    this.setCookieAtalhoSemAcao()
                }

                this.filtro.status = []  
                this.filtro.item = []  
                this.filtro.mensagem = ''  
                this.filtro.status_servidor = []                  
                if ( !this.filtro.alarmes  ) {

                    this.verificaFiltroFechado() 

                    this.optionsStatusItens.forEach(item=>{
                        if( item.alarme ){
                            this.filtro.status.push( item )
                        }
                    })

                    this.filtro.alarmes = true                
                    this.filtroOcultaOpcoesItens = false
                   
                }else{
                
                    this.filtro.alarmes = false                
                    this.filtroOcultaOpcoesItens = true

                    
                }

                this.$cookie.set( this.cookiePrefixo+"status" , this.filtro.status , 1)
                this.$cookie.set( this.cookiePrefixo+"alarmes" , this.filtro.alarmes , 1)
                this.$cookie.set( this.cookiePrefixo+"oculta_filtro_status" , this.filtroOcultaOpcoesItens , 1)
                filtroBus.$emit('editTable')
               
            },

            setCookieAtalhoSemAcao()
            {
                if (this.filtro.alarmes)
                {
                    this.setCookieAtalho()
                }
                
                this.filtro.status = []  
                this.filtro.item = []  
                this.filtro.mensagem = ''  
                this.filtro.status_servidor = []    
                this.filtro.parada_programada = []
                this.filtro.alarme_persistente = []
                this.filtro.chamado_vinculado = []              
                
                if ( !this.filtro.alarmes_acao  ) {

                    this.verificaFiltroFechado() 

                    this.optionsStatusItens.forEach(item=>{
                        if( item.alarme ){
                            this.filtro.status.push( item )
                        }
                    })
                    
                    this.filtro.parada_programada = [ 'NÃO POSSUI', 'AGENDADA' ]
                    this.filtro.alarme_persistente = [ 'SIM' ]
                    this.filtro.chamado_vinculado = [ 'NÃO' ]
                    
                    this.filtro.alarmes_acao = true                
                    this.filtroOcultaOpcoesItens = false
                   
                }else{
                
                    this.filtro.alarmes_acao = false                
                    this.filtroOcultaOpcoesItens = true

                    
                }

                this.$cookie.set( this.cookiePrefixo+"status" , this.filtro.status , 1)
                
                this.$cookie.set( this.cookiePrefixo+"parada_programada" , this.filtro.parada_programada , 1)
                this.$cookie.set( this.cookiePrefixo+"alarme_persistente" , this.filtro.alarme_persistente , 1)
                this.$cookie.set( this.cookiePrefixo+"chamado_vinculado" , this.filtro.chamado_vinculado , 1)
                
                this.$cookie.set( this.cookiePrefixo+"alarmes_acao" , this.filtro.alarmes_acao , 1)
                this.$cookie.set( this.cookiePrefixo+"oculta_filtro_status" , this.filtroOcultaOpcoesItens , 1)
                filtroBus.$emit('editTable')
               
            },
            optionSelecionado( item,  x )
            {
                if ( this.filtro.alarmes == true || this.filtro.alarmes_acao == true || this.filtro.status.length > 0 || this.filtro.status_servidor.length > 0 ){

                    if(item == 'ALARMES')
                    {
                        if ( this.filtro.alarmes ) 
                        {
                            return ''
                        }
                        else 
                        {  
                            return 'optionSelect'
                        }
                    }
                    else if(item == 'ALARMES_ACAO')
                    {
                        if ( this.filtro.alarmes_acao ) 
                        {
                            return ''
                        }
                        else 
                        {  
                            return 'optionSelect'
                        }

                    }
                    else if(item == 'ITEM')
                    {
                        if ( this.filtro.status.findIndex(i => i.id === x.id) === -1  ) 
                        {
                            return 'optionSelect'
                        }
                        else 
                        {  
                            if ( this.filtro.alarmes || this.filtro.alarmes_acao ){

                                return 'optionSelect'
                            
                            }
                            return ''
                        }

                    }
                    else if(item == 'SERVIDOR')
                    {
                        if ( this.filtro.status_servidor.findIndex(i => i.id === x.id) === -1  ) 
                        {
                            return 'optionSelect'
                        }
                        else 
                        {  
                            return ''
                        }

                    }



                }else{

                    return ''

                } 




            },
            getCookies(x){
                this.semCookie = x
            },
            coletaServidor(servidorId){
                
                this.$set(this.coletandoManualmente, servidorId , true)

                window.axios.post( this.url+'coleta-manual' , {'servidorId': servidorId} )
                    .then(response=>{

                        this.$set(this.coletandoManualmente, servidorId , false)
                        
                        let s = true
                        for (var prop in this.coletandoManualmente) {
                            if( this.coletandoManualmente[prop] ){
                                s = false
                            }
                        }

                        if (s){
                            this.pesquisaAtualiza()
                        }
                
                
                    });


            },
            atualizandoManualmente: function (servidorId) {
                return this.coletandoManualmente[servidorId]
            },
            abreTodosItens ( value ) {
                this.abreTodos = value
                this.$cookie.set( this.cookiePrefixo+"itens_todos_abertos" ,this.abreTodos , 1)
                this.pesquisaAtualiza()

            }
        
        },
        computed:{

        },
        mounted()
        {

            this.getCookieItensTodosAbertos()
            this.optionsItens = JSON.parse(this.itens)  
            this.optionsProdutos = JSON.parse(this.produtos)
            this.optionsStatusServidores = JSON.parse(this.status_servidores)
            this.optionsStatusItens = JSON.parse(this.status_itens)
            this.optionsClientes = JSON.parse(this.clientes)
            this.optionsTipos = JSON.parse(this.tipos)
            this.optionsAlarmesPersistentes = ['SIM', 'NÃO']
            this.optionsParadaProgramada = ['NÃO POSSUI', 'AGENDADA', 'NO MOMENTO']
            this.getCookieAtualizaPagina()
            this.getCookieOcultaFiltroItens()
            this.getCookieOcultaFiltroAberto()
            this.atualizaPagina()
        
            this.optionsStatusServidoresSuperior = this.optionsStatusServidores
            this.optionsStatusItensSuperior = this.optionsStatusItens


            if( this.semCookie ){
                this.setCookieAtalhoSemAcao()
            }
            else if( this.filtro.alarmes )
            {
                if(this.filtro.status.length == 0){
                    this.optionsStatusItens.forEach(item=>{
                        if( item.alarme ){
                            this.filtro.status.push( item )
                        }
                    })

                    this.$cookie.set( this.cookiePrefixo+"status" , this.filtro.status , 1)
                }
            }
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
    .background_table_itens{
        background-color: rgba(236, 236, 236, 0.65);
    }
</style>