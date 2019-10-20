<template>
    
   
    <div class="row">

        <div class="col-md-3">
            <div class="box box-widget widget-user-2">                
                <div class="widget-user-header bg-green" :style="'background-color:'+ servidor.cor + '!important' ">                    
                    <h3 class="widget-user-username" style="margin-left:0">{{ servidor.cliente }}</h3>
                    </br>
                    <h5 class="widget-user-desc" style="margin-left:0">Status: <i :class="'fa '+servidor.icone"></i>&nbsp; {{ servidor.nome  }} </h5>
                    <h5 class="widget-user-desc" style="margin-left:0">Tipo: {{ servidor.tipo  }} </h5>
                    <h5 class="widget-user-desc" style="margin-left:0">Projeto: <a :href="'https://crm.novaxtelecom.com.br/operacoes/fluxo/visualizar.php?id='+servidor.id_projeto" target="_blank" style="color: inherit">{{ servidor.id_projeto  }}</a></h5>
                    <h5 class="widget-user-desc" style="margin-left:0">Plano: {{ servidor.plano + ' ' + servidor.grupo + ' ' + servidor.plano_tipo }} </h5>
                    <h5 class="widget-user-desc" style="margin-left:0">Status do projeto: {{ servidor.status  }} </h5>
                </div>
            </div>
                            
            <div v-if="this.can.servidor_editar">
                
                <a :href="'/monitoramento/servidores/'+servidor.id+'/edit'" class="btn btn-primary pull-left"><i class="fa fa-edit margin-r-5"></i>Editar</a>                
            </div>
        </div>

        <div class="col-md-9">

            <div class="nav-tabs-custom tab-success vue-tabs">
                <div class="nav-tabs-navigation">
                    <div class="nav-tabs-wrapper">
                        <ul role="tablist" class="nav nav-tabs">
                            
                            <li aria-selected="true" aria-controls="p-Dados pessoais" role="tab" class="tab pointer"  v-for="( x, key ) in abas" :class="key == aba ? 'active': '' " >
                                <a :href="'/monitoramento/servidores/'+servidor.id+'/'+key" class="active_tab tabs__link">
                                    <span class="title title_center">
                                        <i class="fa  margin-r-5" :class="x.icone" >&nbsp;</i>
                                        {{ x.nome }}
                                    </span>
                                </a>
                            </li>
                            
                        </ul>
                    </div>
                </div>
                <div>
                    
                    <div class="tab-content"  v-if="this.aba == 'dados'">
                    
                        <vc-aba-dados :id="servidor.id"></vc-aba-dados>

                    </div>
                    <div class="tab-content"  v-if="this.aba == 'configuracoes'">
                    
                        <vc-aba-configuracao :id="servidor.id"></vc-aba-configuracao>

                    </div>
                    <div class="tab-content"  v-if="this.aba == 'alertas'">
                    
                       <vc-aba-alerta :id="servidor.id" ></vc-aba-alerta>

                    </div>                    
                    <div class="tab-content"  v-if="this.aba == 'itens_monitorados'">
                    
                        <vc-aba-itens-monitorados :id="servidor.id"></vc-aba-itens-monitorados>

                    </div>
                    <div class="tab-content"  v-if="this.aba == 'chamados_vinculados'">
                    
                        <vc-aba-chamados-vinculados :id="servidor.id" ></vc-aba-chamados-vinculados>
                        
                    </div>
                    <div class="tab-content"  v-if="this.aba == 'paradas_programadas'">
                    
                        <vc-aba-parada-programada :id="servidor.id"></vc-aba-parada-programada>

                    </div>
                </div>
            </div>

 
        </div>
    </div>


</template>

<script>
    export default {
        props:['servidor', 'can', 'abas', 'aba'],
        data () {
            return {
     
            }
        },
        mounted()
        {

            // console.log( this.abas )
          
        },
    };


</script>

<style>
    .nav-tabs>li>a {
        color: #444 !important;
    }

    .nav-tabs>li.active>a, 
    .nav-tabs>li.active>a:focus, 
    .nav-tabs>li.active>a:active {
        color: #444;
        border-top-width: 3px;
        border-top-color: #6f7277;
        border-radius: 5px;
    }

</style>
<style scoped>
    .timeline-body{
        background-color: #f0f0f0;
    }
</style>
