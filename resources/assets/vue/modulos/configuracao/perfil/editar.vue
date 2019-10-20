<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <vc-form :acessos="acessos" :can="can" :perfil-param="perfilObj" @salvar="update"></vc-form>
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
        props:['acessos', 'can', 'perfil', 'acessoPerfil'],
        data () {
            return {
                acessosObj: {},
                perfilObj: {},
                canObj: {},
                mensagemUrl: '',
                mensagem: "",
                errors:[],
            }
        },
        methods:{
            update( dataValue )
            {
                if( dataValue )
                {
                    this.limpaResponse()
                    dataValue.nome = dataValue.nome.toUpperCase();
                   
                    window.axios.put( '/configuracao/perfil/'+dataValue.id, dataValue)
                        .then(response=>{
                            this.mensagem = response.data.mensagem;
                            this.mensagemUrl = '/configuracao/perfil/'+response.data.id;
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
        },
        mounted()
        {               
            this.canObj = JSON.parse(this.can);
        },
        created()
        {
            this.perfilObj = JSON.parse(this.perfil);
            this.perfilObj.acessos = [];
            this.perfilObj.acessos = JSON.parse(this.acessoPerfil);
        }
    };
</script>

