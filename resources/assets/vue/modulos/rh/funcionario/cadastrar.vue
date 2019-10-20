<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <vc-form :gestores="gestores" :cargos="cargos" :can="can" :funcionario="funcionario" @salvar="store"></vc-form>
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
                            <span v-if="mensagemUrl" style="padding-left:5px;">
                                <a :href="mensagemUrl"><i class="fa fa-external-link" aria-hidden="true"></i></a>
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
        props:['gestores', 'cargos', 'can'],
        data () {
            return {
                gestoresObj: {},
                cargosObj: {},
                canObj: {},
                mensagemUrl: '',
                mensagem: "",
                errors:[],
                funcionario: {
                    nome: '', 
                    nome_completo: '', 
                    email: '', 
                    dt_nascimento: '', 
                    celular_pessoal: '', 
                    celular_corporativo: '', 
                    telefone_comercial: '', 
                    ramal: '', 
                    cargo_id: '', 
                    gestor_id: ''
                },
            }
        },
        methods:
        {
            store( dataValue )
            {
                if( dataValue )
                {
                    this.limpaResponse()
                    dataValue.nome = dataValue.nome.toUpperCase();
                    dataValue.nome_completo = dataValue.nome_completo.toUpperCase();
                   
                    window.axios.post( '/rh/funcionario', dataValue)
                        .then(response=>{
                            this.mensagem = response.data.mensagem;
                            this.mensagemUrl = '/rh/funcionario/'+response.data.id;
                            this.limpaFuncionario();
                            return 0;
                        }).catch(error => {
                            this.errors = error.response.data.errors;
                            return 0; 
                        });
                } 
            },
            limpaResponse(){
                this.errors = [];
                this.mensagem = "";
            },
            limpaFuncionario()
            {
                this.funcionario = {
                    nome: '', 
                    nome_completo: '', 
                    email: '', 
                    dt_nascimento: '', 
                    celular_pessoal: '', 
                    celular_corporativo: '', 
                    telefone_comercial: '', 
                    ramal: '', 
                    cargo_id: '', 
                    gestor_id: ''
                };
            }
        },
        mounted()
        {
            this.canObj = JSON.parse(this.can);
            this.gestoresObj = JSON.parse(this.gestores);
            this.cargosObj = JSON.parse(this.cargos);
        },
    };


</script>

