<template>
    <div>
        <vc-migalha-filtro-data 
            titulo="Monitoramento do serviço"
            :migalha="migalha" 
            :datas="datas"
            @busca-dash="search"
            url="/monitoramento/servico/servico_ajax"
            prefixo="monitoramento_servico_"
            inicial="hoje"
            :esconde="fechaCombo"
            @comboAparece="comboAparece"
            :timeout="timeout"
        >
        </vc-migalha-filtro-data>
        <section class="content" v-on:mouseover="comboEsconde">
            <div class="row">
                 
                <vc-box-info-mini-dash
                    icone="server" 
                    iconeColor="#fff"
                    boxColor="#00a65a" 
                    titulo="SERVIDORES MONITORADOS" 
                    tooltip="SERVIDORES MONITORADOS NO PERÍODO" 
                >
                    <template slot="content">
                        <span style="font-size:38px" :title="servidores+' SERVIDORES MONITORADOS NO PERÍODO'">
                            {{servidores}}     
                        </span>
                    </template>
                </vc-box-info-mini-dash>

                <vc-box-info-mini-dash
                    icone="retweet" 
                    iconeColor="#fff"
                    boxColor="#0073b7" 
                    titulo="QTDE. TOTAL DE COLETAS" 
                    tooltip="QUANTIDADE TOTAL DE COLETAS NO PERÍODO" 
                >
                    <template slot="content">
                        <span style="font-size:38px" :title="coletas_totais+' COLETAS REALIZADAS NO PERÍODO'">
                            {{coletas_totais}}     
                        </span>
                    </template>
                </vc-box-info-mini-dash>

                <vc-box-info-mini-dash
                    icone="exclamation-triangle" 
                    iconeColor="#fff"
                    boxColor="#f39c12" 
                    titulo="QTDE. DE COLETAS COM FALHAS" 
                    tooltip="QUANTIDADE DE COLETAS COM FALHAS NO PERÍODO" 
                >
                    <template slot="content">
                        <span style="font-size:38px" :title="coletas_falhas+' COLETAS COM FALHAS NO PERÍODO'">
                            {{coletas_falhas}}
                            <span style="font-size: 24px">
                                ({{coletas_falhas_percent}}%)
                            </span>
                        </span>
                    </template>
                </vc-box-info-mini-dash>

                <vc-box-info-mini-dash
                    icone="clock-o" 
                    iconeColor="#fff"
                    boxColor="#0073b7" 
                    titulo="TEMPO MÉDIO DE CADA COLETA" 
                    tooltip="TEMPO MÉDIO DE CADA COLETA NO PERÍODO"
                >
                    <template slot="content">
                        <span style="font-size:38px" :title="duracao_media_coleta + ' SEGUNDOS'">
                            {{duracao_media_coleta}}
                            <span style="font-size: 24px">
                                segundos
                            </span>
                        </span>
                    </template>
                </vc-box-info-mini-dash>
            

                <vc-box-info-mini-dash
                    icone="clock-o" 
                    iconeColor="#fff"
                    boxColor="#ed7d31" 
                    titulo="TEMPO MÉDIO DE EXECUÇÃO DO SERVIÇO" 
                    tooltip="TEMPO MÉDIO DE EXECUÇÃO DO SERVIÇO NO PERÍODO"
                >
                    <template slot="content">
                        <span style="font-size:38px" :title="duracao_media_servico + ' SEGUNDOS'">
                            {{duracao_media_servico}}
                            <span style="font-size: 24px">
                                segundos
                            </span>
                        </span>
                    </template>
                </vc-box-info-mini-dash>

                <vc-box-info-mini-dash
                    icone="clock-o" 
                    iconeColor="#fff"
                    boxColor="rgb(94, 42, 113)" 
                    titulo="ÚLTIMA EXECUÇÃO DO SERVIÇO" 
                    tooltip="DURAÇÃO DA ÚLTIMA EXECUÇÃO DO SERVIÇO" 
                >
                    <template slot="content">
                        <span style="font-size:38px" :title="duracao_ultima  + ' SEGUNDOS'">
                            {{duracao_ultima}}
                            <span style="font-size: 24px">
                                segundos
                            </span>
                        </span>
                    </template>
                </vc-box-info-mini-dash>


                 <vc-box-info-mini-dash
                    icone="user" 
                    iconeColor="#363636"
                    boxColor="rgba(0,0,0,0.2)" 
                    titulo="ÚLTIMA ALTERAÇÃO DO SERVIÇO" 
                    tooltip="ÚLTIMA ALTERAÇÃO DO SERVIÇO" 
                >
                    <template slot="content">
                        <span style="font-size:18px" :title="'ÚLTIMA ALTERAÇÃO EM ' + data + ', REALIZADA POR ' + usuario">
                            {{data}}<br>
                            {{usuario}}
                        </span>
                    </template>
                </vc-box-info-mini-dash>

           <!--      <vc-box-info-mini-dash

                    acao="dowload"
                    acaoIcone="fa-download"
                    :canAcao="true" 
                    @acaoEvent=""
                
                    icone="android" 
                    iconeColor="#363636"
                    boxColor="rgba(0,0,0,0.2)" 
                    titulo="STATUS DO SERVIÇO DASDASD ASDSADASD ASDDAD ASD ASDASD" 
                    tooltip="STATUS DO SERVIÇO" 
                 
                    possuiClick="true"
                    @clickEvent=""
                >
                    
                    <template slot="content">
                        <span >
                            huahusahu     
                        </span>
                    </template>

                </vc-box-info-mini-dash> -->

                <vc-box-info-mini-dash
                    icone="android" 
                    iconeColor="#363636"
                    boxColor="rgba(0,0,0,0.2)" 
                    titulo="STATUS DO SERVIÇO" 
                    tooltip="STATUS DO SERVIÇO" 
                >
                    
                    <template slot="content">
                       <span style="font-size:30px;color:#333; width:65%">
                            <span>
                                {{ status ? 'ATIVADO':'DESATIVADO' }}
                            </span>
                        </span>
                                            
                        <span class="pull-right" style="padding:5px 0px" >
                            <label class="switch" style="margin-top: 5px;">
                                <input name="atualiza" v-model="status" type="checkbox"> 
                                <span class="slider round" @click="alteraStatus(status)"></span>
                            </label>
                        </span>
                    </template>

                </vc-box-info-mini-dash>

            </div>            
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Parâmetros configurados
                        </h3>
                    </div>
                    <div class="box-body row" style="padding-bottom: 0;">
                        <div class="col-md-6" v-for="item in parametros">
                            <label>
                                <a :href="'/configuracao/sistema/parametro/'+item.id+'/edit'" target="_blank" style="color: #333">
                                    {{item.descricao}}
                                </a>
                            </label>
                            <p class="text-muted">{{item.valor}}</p>
                        </div>
                    </div>
                </div>            
        </section>

    </div>

</template>

<script>

    export default {

        props:['parametros', 'migalha', 'timeout'],

        data () {
            return {
                dados:'',
                pages:[],
                item:[],
                datas: [
	                {value: 'hoje', nome:'HOJE'},
	                {value: 'customizado', nome:'CUSTOMIZADO'}
	            ],
                duracao_ultima: '',
                servidores: '',
                status: '',
                usuario: '',
                data: '',
                coletas_falhas: '',
                coletas_falhas_percent: '',
                duracao_media_servico: '',
                duracao_media_coleta: '',
                coletas_totais:'',
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
            statusMonitoramento(statusEnvio){
                
                window.axios.get( '/monitoramento/servico/status/'+statusEnvio)
                    .then(response=>{
                        this.monitoramentoStatus = statusEnvio
                    })
                    .catch(error => {
                
                    });

            },
            search(value){

                this.coletas_falhas = value.info_servico.coletas_falhas
                this.coletas_falhas_percent = value.info_servico.coletas_falhas_percent                
                this.servidores = value.info_servico.servidores
                this.data = value.info_servico.data == null ? '-' : value.info_servico.data
                this.usuario = value.info_servico.usuario == null ? '-' : value.info_servico.usuario
                this.duracao_media_servico = value.info_servico.duracao_media_servico
                this.duracao_media_coleta = value.info_servico.duracao_media_coleta
                this.duracao_ultima = value.info_servico.duracao_ultima                
                this.coletas_totais = value.info_servico.coletas_totais
                
                this.status = value.info_servico.status

            },
            alteraStatus(value){
                this.status = !value                
                this.statusMonitoramento(!value)
            }
        },
        
    };

</script>
<style>
    .ajusta_data_servico{
        font-size: 30px !important;
    }
</style>

