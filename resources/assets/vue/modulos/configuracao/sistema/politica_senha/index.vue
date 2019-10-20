<template>
    <div>
        <div class="row">

        <!-- left column -->
        <div class="col-md-12">

            <!-- general form elements -->
            <div class="box box-default">

                <form role="form" data-toggle="validator" name="form" >

                    <div class="box-body row" style="padding-bottom: 0;">
                    
                        <div class="form-group col-md-4">
                            <label>Tamanho mínimo:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <input type="text" class="form-control input-sm" name="nome" v-model="politicaObj.tamanho_minimo" style="text-transform: uppercase;" maxlength="100" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>
                    
                        <div class="form-group col-md-4">
                            <label>Qtde. mínima de letras:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <input type="text" class="form-control input-sm" name="nome" v-model="politicaObj.qtde_minima_letras" style="text-transform: uppercase;" maxlength="100" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>
                    
                        <div class="form-group col-md-4">
                            <label>Qtde. mínima de números:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <input type="text" class="form-control input-sm" name="nome" v-model="politicaObj.qtde_minima_numeros" style="text-transform: uppercase;" maxlength="100" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>
                    
                    </div>
                    
                    <div class="box-body row" style="padding-bottom: 0;">
                    
                        <div class="form-group col-md-4">
                            <label>Qtde. mínima de caracteres especias:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <input type="text" class="form-control input-sm" name="nome" v-model="politicaObj.qtde_minima_especial" style="text-transform: uppercase;" maxlength="100" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>
                    
                        <div class="form-group col-md-4">
                            <label>Caracteres considerados especiais:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <input type="text" class="form-control input-sm" name="nome" v-model="politicaObj.caractere_especial" style="text-transform: uppercase;" maxlength="100" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>
                    
                        <div class="form-group col-md-4">
                            <label>Obrigar maiúsculo e minúsculo:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-10">
                                    <label>
                                        <input type="radio" name="5" value="true" v-model="politicaObj.maiusculo_minusculo" checked="checked"> Sim
                                    </label>
                                    &nbsp;
                                    <label>
                                        <input type="radio" name="6" value="false" v-model="politicaObj.maiusculo_minusculo" > Não
                                    </label>
                                  
                                </div>
                                 <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>
                    
                    </div>
                    



                    <div class="box-footer">
                        <button class="btn btn-primary" :disabled="isDisabled" @click="store">Salvar</button>
                    </div>
                    
                </form>
            </div>
            <!-- /.box -->            
        </div>
        <!-- /.left colum -->

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
        props:['politica'],
		data () {
            return {
                validaBotao:false,
                mensagem: "",
                errors:[],
                politicaObj:[],
                id:'',
                
            }
        },
        methods:{

            store(){
                if( !this.validaBotao){
                    this.limpaResponse();
                    this.validaBotao = true;

                    window.axios.put( '/configuracao/sistema/politica_senhas/'+this.politicaObj.id , this.politicaObj)
                        .then(response=>{
                            this.mensagem = response.data.mensagem
                            this.politicaObj.id = response.data.id
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
              this.url = "";
            },
        },
        computed:{
            isDisabled(){
                if(   !this.validaBotao  ) {
                    return false
                }
                return true
            }
        },
        mounted()
        {   
            this.politicaObj = this.politica
            
        },
	};


</script>

