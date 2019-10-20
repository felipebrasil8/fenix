<template>
    <div>        
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <form role="form" data-toggle="validator" name="form">

                        <div class="box-header with-border">
                            <i class="fa fa-user"></i>
                            <h3 class="box-title">Dados do cliente</h3>
                        </div>
                        <div class="box-body row">
                            <div class="form-group col-md-3">
                                <label for="nome">Cliente:</label>
                                <p class="text-muted">{{servidorObj.cliente}}</p>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="nome">Projeto:</label>
                                <p class="text-muted">{{servidorObj.id_projeto}}</p>
                            </div>
                        </div>

                        <div class="box-header with-border">
                            <i class="fa fa-server"></i>
                            <h3 class="box-title">Dados do servidor</h3>
                        </div>
                        <div class="box-body row" style="padding-bottom: 0;">
                            
                            <div class="form-group col-md-3">
                                <label for="nome">Produto:</label>
                                <p class="text-muted">{{servidorObj.grupo}}</p>
                            </div>

                            <div class="form-group col-md-9">
                                <label for="nome">Tipo:</label>
                                <p class="text-muted">{{servidorObj.tipo}}</p>
                            </div>


                            <div class="form-group col-md-3">
                                <label for="nome">Porta API:</label>
                                <div class="row">
                                    <div class="col-md-11 col-xs-10">
                                        <input type="text" class="form-control input-sm" id="porta_api" data-error="Este campo é obrigatório." style="text-transform: uppercase;" v-model="servidorObj.porta_api" required maxlength="100">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                        <i class="fa fa-asterisk"></i>
                                    </div>
                                </div>
                            </div>  
                            
                            <div class="form-group col-md-3">
                                <label for="nome">Executa consulta de ping:</label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>
                                            <input type="radio" v-model="servidorObj.executa_ping" name="ping" value="true" checked="checked"> Ativo
                                        </label>
                                        &nbsp;
                                        <label>
                                            <input type="radio" v-model="servidorObj.executa_ping" name="ping" value="false"> Inativo
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group col-md-3">
                                <label for="nome">Executa consulta de portas:</label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>
                                            <input type="radio" v-model="servidorObj.executa_porta" name="porta" value="true" checked="checked"> Ativo
                                        </label>
                                        &nbsp;
                                        <label>
                                            <input type="radio" v-model="servidorObj.executa_porta" name="porta" value="false"> Inativo
                                        </label>
                                    </div>
                                </div>
                            </div>
                        
                        </div>
                        <div class="box-footer">
                            <button type="button" class="btn btn-primary" @click="salvar" :disabled="isDisabled">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="" v-if="errors || mensagem">   
            <div class="row">
                <div class="col-md-12">
                    <div class="callout no-margin-bottom callout-danger" v-for="value in errors" style="margin-top: 15px;">
                        <div>{{ value[0] }}</div>
                    </div>
                </div>
            </div>

            <div class="row" v-if="mensagem">
                <div class="col-md-12">
                    <div class="callout no-margin-bottom callout-success" style="margin-top: 15px;">
                        <div>{{ mensagem }}
                            <span v-if="url" style="padding-left:5px;">
                                <a :href="url"><i class="fa fa-external-link" aria-hidden="true"></i></a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</template>

<script>
    export default {
        props:['servidor', 'can'],
        data () {
            return {
                canObj: {},
                servidorObj: {},
                mensagem: "",
                errors:[],
                validaBotao: false,
                url:'',

            }
        },
        methods:
        {
            salvar(  )
            {
                
                if(this.servidorObj.porta_api != '' && !this.validaBotao){
                    this.limpaResponse();
                    this.validaBotao = true;
                    
                    window.axios.put( '/monitoramento/servidores/'+this.servidorObj.id, this.servidorObj)
                        .then(response=>{
                            this.mensagem = response.data.mensagem;
                            this.url = '/monitoramento/servidores/'+response.data.id;
                            this.validaBotao = false;
                        })
                        .catch(error => {
                            this.errors = error.response.data.errors;
                            this.validaBotao = false;
                            return 0; 
                        });
                } 
                 
            },
            limpaResponse(){
                this.errors = [];
                this.mensagem = "";
            },
        },
        computed:{
            isDisabled(){
                if( this.servidorObj.porta_api != '' &&  !this.validaBotao  ) {
                    return false
                }
                return true
            }
        },
        mounted()
        {
            this.servidorObj = JSON.parse(this.servidor);            
        },
    };


</script>