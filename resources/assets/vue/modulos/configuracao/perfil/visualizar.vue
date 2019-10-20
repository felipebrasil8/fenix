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
                                {{ perfil.nome }}
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



                <div class="box-body row" style="padding-bottom: 0;">
                    <div class="form-group col-md-3">
                        <strong>Data de inclusão:</strong>
                        <p class="text-muted" v-if="perfil.created_at">
                            {{ perfil.created_at }}
                            <span v-if="perfil.usuario_inclusao">
                              ({{ perfil.usuario_inclusao }}) 
                            </span>
                        </p>
                        <p class="text-muted" v-else>-</p>
                    </div>

                    <div class="form-group col-md-3">
                        <strong>Data de alteração:</strong>
                        <p class="text-muted" v-if="perfil.updated_at">
                               {{ perfil.updated_at }} 
                            <span v-if="perfil.usuario_alteracao">
                                ({{ perfil.usuario_alteracao }})
                            </span>
                        </p>
                        <p class="text-muted" v-else>-</p>
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
                    <a class="btn btn-primary" :href="'/configuracao/perfil/'+perfil.id+'/edit'" v-if="canObj.editar">Editar</a>
                    <a class="btn btn-primary" :href="'/configuracao/perfil/'+perfil.id+'/copy'" v-if="canObj.copiar">Copiar</a>
                </div>
                    
              
            </div>
            <!-- /.box -->            
        </div>
        <!-- /.left colum -->

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