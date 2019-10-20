<template>
    <div>        
        <div class="row">
            <div class="col-md-12">
                
                <div class="tab-content"  v-if="servidorObj">
                    <div class="box-default box" style="margin-bottom: 0px; border-radius: 0px; background:none; border-top: none;">          
                        <div class="overlay" v-if="carregando">
                            <i class="fa fa-refresh fa-spin"></i>
                        </div>

                        <!-- Serviço -->
                        <div class="box-header with-border"><i class="fa fa-address-card"></i> <h3 class="box-title">Produto</h3></div>                        
                        <div v-if="servidorObj.servico">
                            <div class="box-body" style="padding-bottom: 0px;">                                
                                    
                                <div class="form-group col-md-4" v-if="servidorObj.servico.produto">
                                    <label>Produto:</label>                                
                                    <p class="text-muted">{{ servidorObj.servico.produto }}</p>
                                </div>

                                <div class="form-group col-md-4" v-if="servidorObj.servico.redundancia">
                                    <label>Possui redundância:</label>
                                    <p class="text-muted">{{ servidorObj.servico.redundancia == 'SIM' ? 'Sim' : 'Não' }}</p>
                                </div>

                                <div class="form-group col-md-4" v-if="servidorObj.servico.projeto">
                                    <label>Projeto:</label>
                                    <p class="text-muted">{{ servidorObj.servico.projeto }}</p>
                                </div>

                                <div class="form-group col-md-4" v-if="servidorObj.servico.numero_projeto">
                                    <label>Número do projeto:</label>
                                    <p class="text-muted">{{ servidorObj.servico.numero_projeto }}</p>
                                </div>

                                <div class="form-group col-md-4" v-if="servidorObj.servico.pendencia_financeira">
                                    <label>Pendência financeira:</label>
                                    <p class="text-muted">{{ servidorObj.servico.pendencia_financeira == 'true' ? 'Sim' : 'Não'   }}</p>
                                </div>
                            </div>
                        </div>
                        <!-- Serviço -->  
                            
                        <!-- Licencas -->
                        <div class="box-header with-border"><i class="fa fa-list"></i> <h3 class="box-title">Licenças</h3></div>

                        <div class="box-body" style="padding-bottom: 0px;">                           

                            <div class="form-group col-md-4" v-if="servidorObj.servico.licencas.novax_nxt3000">
                                <label class="truncate" title="Quantidade de posições contratadas">Quantidade de posições contratadas:</label>
                                <p class="text-muted">{{ servidorObj.servico.licencas.novax_nxt3000 }}</p>
                            </div>

                            <div class="form-group col-md-4" v-if="servidorObj.servico.licencas.posicao_novax">
                                <label class="truncate" title="Quantidade de posições para interligação de NXT3000 (Adicional)">Quantidade de posições para interligação de NXT3000 (Adicional):</label>
                                <p class="text-muted">{{ servidorObj.servico.licencas.posicao_novax }}</p>
                            </div>

                            <div class="form-group col-md-4" v-if="servidorObj.servico.licencas.posicao_virtual">
                                <label class="truncate" title="Quantidade de posições virtuais para desvios (Adicional)">Quantidade de posições virtuais para desvios (Adicional):</label>
                                <p class="text-muted">{{ servidorObj.servico.licencas.posicao_virtual }}</p>
                            </div>

                            <div class="form-group col-md-4" v-if="servidorObj.servico.licencas.ramais_remotos">
                                <label class="truncate" title="Licenças de posições remotas">Licenças de posições remotas:</label>
                                <p class="text-muted">{{ servidorObj.servico.licencas.ramais_remotos }}</p>
                            </div>
                            
                            <div class="form-group col-md-4" v-if="servidorObj.servico.licencas.novax_discador">
                                <label class="truncate" title="Licenças de posições NXT-DISCADOR">Licenças de posições NXT-DISCADOR:</label>
                                <p class="text-muted">{{ servidorObj.servico.licencas.novax_discador }}</p>
                            </div>

                            <div class="form-group col-md-4" v-if="servidorObj.servico.licencas.tronco_analogico">
                                <label class="truncate" title="Quantidade de troncos analógicos">Quantidade de troncos analógicos:</label>
                                <p class="text-muted">{{ servidorObj.servico.licencas.tronco_analogico }}</p>
                            </div>

                            <div class="form-group col-md-4" v-if="servidorObj.servico.licencas.tronco_e1">
                                <label class="truncate" title="Quantidade de troncos E1">Quantidade de troncos E1:</label>
                                <p class="text-muted">{{ servidorObj.servico.licencas.tronco_e1 }}</p>
                            </div>

                            <div class="form-group col-md-4" v-if="servidorObj.servico.licencas.tronco_gsm">
                                <label class="truncate" title="Quantidade de troncos GSM">Quantidade de troncos GSM:</label>
                                <p class="text-muted">{{ servidorObj.servico.licencas.tronco_gsm }}</p>
                            </div>

                            <div class="form-group col-md-4" v-if="servidorObj.servico.licencas.tronco_voip">
                                <label class="truncate" title="Quantidade de troncos VOIP">Quantidade de troncos VOIP:</label>
                                <p class="text-muted">{{ servidorObj.servico.licencas.tronco_voip }}</p>
                            </div>

                            <div class="form-group col-md-4" v-if="servidorObj.servico.licencas.CANAIS_SIMULTANEOS">
                                <label class="truncate" title="Canais simultâneos">Canais simultâneos:</label>
                                <p class="text-muted">{{ servidorObj.servico.licencas.CANAIS_SIMULTANEOS }}</p>
                            </div>
                            
                        </div>                               
                        <!-- /Licencas -->

                        <div class="box-header with-border"><i class="fa fa-server"></i> <h3 class="box-title">Servidor</h3></div>

                        <div class="box-body" style="padding-bottom: 0px;">

                            <div class="form-group col-md-4" v-if="servicoServidor">
                                <label>Servidor:</label>
                                <p class="text-muted">{{ servicoServidor }}</p>
                            </div>

                            <div class="form-group col-md-4" v-if="servicoNumeroAtivo">
                                <label>Número ativo:</label>
                                <p class="text-muted">{{ servicoNumeroAtivo }}</p>
                            </div>

                            <div class="form-group col-md-4" v-if="servicoNumeroSerie">
                                <label>Número de série:</label>
                                <p class="text-muted">{{ servicoNumeroSerie }}</p>
                            </div>

                            <div class="form-group col-md-4" v-if="servicoVersao">
                                <label>Versão:</label>
                                <p class="text-muted">{{ servicoVersao }}</p>
                            </div>

                            <div class="form-group col-md-4" v-if="servidorObj.portas">
                                <label>Portas:</label>                                    
                                <p class="text-muted" v-for="x in servidorObj.portas">{{ x }}</p>                                    
                            </div>

                            <div class="form-group col-md-4" v-if="servicoCpu">
                                <label>CPU:</label>
                                <p class="text-muted">{{ servicoCpu }}</p>                                    
                            </div>

                            <div class="form-group col-md-4" v-if="servicoMemoria">
                                <label>Memória total:</label>
                                <p class="text-muted">{{ servicoMemoria }}</p>                                    
                            </div>

                        </div>                      
                        <!-- ./Servico -->

                        <!-- Discos -->
                        <div v-for="( index, key ) in servidorObj" v-if="key == 'discos' && servidorObj.discos">
                            <div class="box-header with-border"><i class="fa fa-database"></i> <h3 class="box-title">Discos</h3></div>
                            <div class="box-body" style="padding-bottom: 0px;">                                    
                                <div class="form-group col-md-4" v-for="( key, x ) in index">
                                    <label>{{ x }}:</label>                                
                                    <p class="text-muted">{{ key }}</p>
                                </div>                                    
                            </div>
                        </div>
                        <!-- ./Discos -->

                    </div>

                </div>
                <div v-else>
                    <h5 class="box-title text-center">Este servidor não possui configuração.</h5>
                </div>

            </div>

        </div>
        
    </div>

</template>

<script>
    export default {
        props:['servidor', 'id'],
        data () {
            return {
                servidorObj: {
                    servico: {
                        licencas: {}
                    },
                },
                servicoServidor: null,
                servicoNumeroAtivo: null,
                servicoNumeroSerie: null,
                servicoVersao: null,
                servicoCpu: null,
                servicoMemoria: null,
                carregando: true,
            }
        },
        methods:
        {
            getDados(  )
            {
                if(this.id != ''){
                    
                    let url = '/monitoramento/servidores/configuracao_ajax/'+this.id

                    window.axios.get( url)
                    .then(response=>{
                        
                        this.servidorObj = JSON.parse( response.data.servidor.configuracao )

                        if( this.servidorObj.servico.sevidor ){
                            this.servicoServidor = this.servidorObj.servico.sevidor    
                        }                        

                        if( this.servidorObj.servico.numero_ativo ){
                            this.servicoNumeroAtivo = this.servidorObj.servico.numero_ativo
                        }

                        if( this.servidorObj.servico.numero_serie ){
                            this.servicoNumeroSerie = this.servidorObj.servico.numero_serie
                        }

                        if( this.servidorObj.servico.versao ){
                            this.servicoVersao = this.servidorObj.servico.versao
                        }

                        if( this.servidorObj.cpu ){
                            this.servicoCpu = this.servidorObj.cpu
                        }

                        if( this.servidorObj.memoria ){
                            this.servicoMemoria = this.servidorObj.memoria.total
                        }

                        this.carregando = false
                        
                    })
                    .catch(error => {                        
                        return 0; 
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

<style>
.nav-tabs>li {
    float: left;
    margin-bottom: -3px;
}
</style>
