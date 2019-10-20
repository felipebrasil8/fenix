<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <vc-form :acessos="acessosObj" :can="can" @salvar="store"></vc-form>
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
    import { perfilAcessoBus } from './perfilAcessoBus.js';
 	export default {
        props:['acessos', 'can'],
		data () {
            return {
                acessosObj: {},
                canObj: {},
                mensagemUrl: '',
                mensagem: "",
                errors:[],
                perfil: {
                    nome: '', 
                    acessos: [],
                    todos_dias: true,
                    horario_inicial: '00:00:00',
                    horario_final: '23:59:59',
                    configuracao_de_rede: '',
                }
            }
        },
        methods:{
            store( dataValue )
            {
                if( dataValue )
                {
                    this.limpaResponse()
                    dataValue.nome = dataValue.nome.toUpperCase();
                   
                    window.axios.post( '/configuracao/perfil', dataValue)
                        .then(response=>{
                            this.mensagem = response.data.mensagem;
                            this.mensagemUrl = '/configuracao/perfil/'+response.data.id;
                            this.limpaPerfil();
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
            limpaPerfil()
            {
                perfilAcessoBus.$emit('updateAcesso');
            }
        },
        mounted()
        {               
            this.canObj = JSON.parse(this.can);
        },
        created()
        {
            this.acessosObj = this.acessos;
        }
	};

</script>