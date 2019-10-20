<template>    
    <div class="row">
        <div class="col-md-12">                
            <div class="tab-content">
                <div class="box box-default" style="margin-bottom: 0px !important; margin-top: 15px;">

                    <div class="table-responsive">                        
                        <table class="table table-striped table-hover table-sm" style="table-layout:fixed; width: 100%;">
                            <thead>
                                <tr>
                                    <th width="15%">Nome</th>
                                    <th width="15%">Última coleta</th>
                                    <th width="10%">Status</th>
                                    <th width="10%">Duração</th>
                                    <th width="50%">Mensagem</th>
                                </tr>
                            </thead>
                            <tbody class="striped">
                                <tr v-for="item in itensObj">
                                    <td>{{ item.nome }}</td>
                                    <td>{{ item.ultima_coleta }}</td>
                                    <td>
                                        <small class="label" :style="'background-color:'+item.cor+';'">
                                            <i class="fa " :class="item.icone" ></i>&nbsp; 
                                            {{ item.status }}
                                        </small>
                                    </td>
                                    <td>{{ item.dt_status }}</td>
                                    <td class="truncate" :title="item.mensagem">{{ item.mensagem }}</td>
                                </tr>                                
                            </tbody>
                        </table>
                    </div>
                    <div id='rodape-da-tabela' v-if="itensObj.length > 0" >
                        <div class='total col-md-4'>
                            Total: ( {{itensObj.length}} )
                        </div>
                    </div> 
                    <div id='rodape-da-tabela' v-else >
                        <div class='total col-md-4'>
                            Nenhuma informação encontrada!!! 
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
               itensObj: {}
            }
        },
        methods:
        {
            getDados(  )
            {
                if(this.id != ''){
                    
                    let url = '/monitoramento/servidores/itens_monitorados_ajax/'+this.id

                    window.axios.get( url)
                    .then(response=>{

                        this.itensObj = response.data.itens
                        
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
.datatebleVue > td
{
    padding-bottom: 3px !important;
}

.datatebleVueInterno > td
{
    padding-top: 3px !important;
    padding-right: 0.3rem !important;
    padding-bottom: 0px !important;
}

/* par */
.striped:nth-child(even){
    background:#fff;
}

/* Ímpar */
.striped:nth-child(odd){
    background:#f9f9f9;
}
</style>