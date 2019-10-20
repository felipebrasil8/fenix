<template>
    <div class="vue-visualizar">
        <div class="row">

        <!-- left column -->
        <div class="col-md-12">

            <!-- general form elements -->
            <div class="box box-default">

                
                <div class="box-body row" style="padding-bottom: 0;">
                    <div class="form-group col-md-3">
                        <label>Nome:</label>
                        <div class="row">
                            <div class="col-md-11 col-xs-10">
                                <input type="text" class="form-control input-sm" name="nome" v-model="perfil.nome" style="text-transform: uppercase;" maxlength="100" required>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                <i class="fa fa-asterisk"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="box-body row" style="padding-bottom: 0;">
               
                
                    <div class="form-group col-md-3">
                        <label>Dias de acesso permitidos:</label>
                        <div class="row">
                            <div class="col-md-11 col-xs-10">
                                {{ perfil.todos_dias == true ? 'Todos os dias':'Dias da semana' }}
                            </div>                                
                        </div>
                    </div>


                    <div class="form-group col-md-3">
                        <label>Hórario de acesso permitido:</label>
                        <div class="row">
                            <div class="col-md-11 col-xs-10">
                                {{ perfil.horario_inicial }} até {{ perfil.horario_final }}
                            </div>                                
                        </div>
                    </div>
                    
                    <div class="form-group col-md-4">
                        <label>Rede permitida:</label>
                        <div class="row">
                            <div class="col-md-11 col-xs-10 truncate" :title="perfil.configuracao_de_rede">
                                {{ perfil.configuracao_de_rede? perfil.configuracao_de_rede:'-'  }}
                            </div>                                
                        </div>
                    </div>

                </div>


                
                <div class="box-body row" style="padding-top: 0;">
                        
                    <div class="form-group col-md-12">
                        
                        <label>Acessos:</label>
                    
                        <div class="form-group">
                            
                            <v-jstree :data="datas" show-checkbox multiple allow-batch whole-row ></v-jstree>

                        </div>

                    </div>

                </div>

                <div class="box-footer">
                    <button class="btn btn-primary" :disabled="isDisabled" @click="store">Salvar</button>
                </div>
              
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
        props:['dados', 'can'],
        data () {
            return {
                datas:[],
                nome:'',
                permission:[],
                validaBotao:false,
                item:{
                    nome:'',
                    permissoes:[],
                },
                mensagem: "",
                url: '',
                errors:[],
                acessosAll:[],
                perfil:{},
                canObj:{},
                acessos:{},
                acesso_perfil:{},
            }
        },
        methods:{
            nArrayPermissoes( permissoes )
            {
                let self = this;
                permissoes.filter( function( item )
                {
                    item.selected = false;
                    item.disabled = true;

                    var countPermissaoFilho = 0;

                    for( var permissao in item.children )
                    {                            
                        item.children[permissao].disabled = true;
                        if ( self.acesso_perfil.includes( item.children[permissao].id ) ){
                            item.children[permissao].selected = true;
                            countPermissaoFilho++;
                        }else{
                            item.children[permissao].selected = false;
                        }
                        
                        // item.children.push(item.children[permissao]);
                    }

                    if( countPermissaoFilho > 0  && countPermissaoFilho == item.children.length )
                    {
                        item.selected = true;

                    }

                });

                return permissoes;
            },
            store(){
                if(this.perfil.nome != '' &&  !this.validaBotao){
                    this.limpaResponse();
                    this.validaBotao = true;
                    this.item.nome = this.perfil.nome
                    this.item.id = this.perfil.id
                    
                    window.axios.post( '/configuracao/perfil', this.item)
                        .then(response=>{
                            this.mensagem = response.data.mensagem;
                            this.url = '/configuracao/perfil/'+response.data.id;
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
                if( this.perfil.nome != '' &&  !this.validaBotao  ) {
                    return false
                }
                return true
            }
        },
        mounted()
        {   

            this.perfil = JSON.parse(this.dados).perfil
            this.canObj = JSON.parse(this.can)
            this.acessos = JSON.parse(this.dados).acessos
            this.acesso_perfil = JSON.parse(this.dados).acesso_perfil

            this.acessosAll = [{
                    "text": "SELECIONAR TODOS",
                    "children":this.acessos,
                    "opened": true,
                    "icon": "fa fa-check icon-state-success",
                    "selected": false,
                }]

            this.datas = this.nArrayPermissoes(this.acessosAll)

        },
    };

</script>
<style>
    .vue-visualizar .tree-anchor{
        cursor: default !important;
    }
</style>