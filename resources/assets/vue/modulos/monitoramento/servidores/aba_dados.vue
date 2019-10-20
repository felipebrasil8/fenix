<template>
    <div>        
        <div class="row">
            <div class="col-md-12">
                
                <div class="tab-content">
                    <div class="box-default box" style="margin-bottom: 0px; border-radius: 0px; background:none; border-top: none;">                        
                        <div class="overlay" v-if="carregando">
                            <i class="fa fa-refresh fa-spin"></i>
                        </div>

                        <div class="box-header with-border"><i class="fa fa-user"></i> <h3 class="box-title">Dados do cliente</h3></div>
                        <div class="box-body row    " style="padding-bottom: 0px;">
                            <div class="form-group col-md-6">
                                <label>Nome:</label>                                
                                <p class="text-muted">{{ servidorObj.cliente || '-'}}</p>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Razão social:</label>                                
                                <p class="text-muted">{{ servidorObj.razao_social || '-'}}</p>
                            </div>
                        </div>
                        
                        <div class="box-body row" style="padding-bottom: 0px;">
                            <div class="form-group col-md-6">
                                <label>Endereço:</label>                                
                                <p class="text-muted">{{ servidorObj.endereco || '-'}}</p>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Bairro:</label>
                                <p class="text-muted">{{ servidorObj.bairro || '-'}}</p>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Cidade / Estado:</label>
                                <p class="text-muted">{{ servidorObj.cidade || '-'}} / {{ servidorObj.estado || '-'}}</p>
                            </div>
                        </div>
                        
                        <div class="box-header with-border"><i class="fa fa-server"></i> <h3 class="box-title">Dados do servidor</h3></div>                        
                        <div class="box-body row" style="padding-bottom: 0px;">                            
                                                    
                            <div class="form-group col-md-3">
                                <label>IP:</label>                                
                                <p class="text-muted">{{ servidorObj.ip || '-'}}</p>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Porta API:</label>                                
                                <p class="text-muted">{{ servidorObj.porta_api || '-'}}</p>
                            </div>
                            <div class="form-group col-md-3">
                                <label>DNS:</label>                                
                                <p class="text-muted">{{ servidorObj.dns || '-'}}</p>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Versão:</label>                                
                                <p class="text-muted" >{{ servidorObj.versao || '-'}}</p>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Executa consulta de ping:</label>
                                <p class="text-muted" >{{ servidorObj.executa_ping ? 'Sim' : 'Não' }}</p>
                            </div>

                            <div class="form-group col-md-3">
                                <label>Executa consulta de portas:</label>                                
                                <p class="text-muted" >{{ servidorObj.executa_porta ? 'Sim' : 'Não' }}</p>
                            </div>
                        </div>

                    </div>

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
                servidorObj: {},
                carregando: true,
            }
        },
        methods:
        {
            getDados(  )
            {
                if(this.id != ''){                    
                    
                    let url = '/monitoramento/servidores/dados_ajax/'+this.id

                    window.axios.get( url)
                        .then(response=>{ 
                            this.servidorObj = response.data.servidor;
                            this.carregando = false;
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



