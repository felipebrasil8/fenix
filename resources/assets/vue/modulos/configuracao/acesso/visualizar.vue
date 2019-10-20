<template>
    <div class="vue-visualizar">
        <div class="row">

        <!-- left column -->
        <div class="col-md-12">

            <!-- general form elements -->
            <div class="box box-default">

                <div class="box-body row" style="padding-bottom: 0;">
                    <div class="form-group col-md-6">
                        <label>Nome:</label>
                        <div class="row">
                            <div class="col-md-11 col-xs-10 truncate" :title="acesso.nome">
                                {{ acesso.nome }}
                            </div>                                
                        </div>
                    </div>
                </div>
                <div class="box-body row" style="padding-bottom: 0;">
                    <div class="form-group col-md-3">
                        <strong>Data de inclusão:</strong>
                        <p class="text-muted" v-if="acesso.created_at">
                            {{ acesso.created_at }}
                            <span v-if="acesso.usuario_inclusao">
                              ({{ acesso.usuario_inclusao }}) 
                            </span>
                        </p>
                        <p class="text-muted" v-else>-</p>
                    </div>

                    <div class="form-group col-md-3">
                        <strong>Data de alteração:</strong>
                        <p class="text-muted" v-if="acesso.updated_at">
                               {{ acesso.updated_at }} 
                            <span v-if="acesso.usuario_alteracao">
                                ({{ acesso.usuario_alteracao }})
                            </span>
                        </p>
                        <p class="text-muted" v-else>-</p>
                    </div>
                </div>
               
                
                <div class="box-body row" style="padding-top: 0;">
                        
                    <div class="form-group col-md-12">
                        
                        <label>Permissões:</label>
                    
                        <div class="form-group">
                            
                            <v-jstree :data="datas" show-checkbox multiple allow-batch whole-row></v-jstree>

                        </div>

                    </div>

                </div>

                <div class="box-footer">
                    <a class="btn btn-primary" :href="'/configuracao/acesso/'+acesso.id+'/edit'" v-if="canObj.editar">Editar</a>
                    <a class="btn btn-primary" :href="'/configuracao/acesso/'+acesso.id+'/copy'" v-if="canObj.copiar">Copiar</a>
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
        props:['permissoes', 'acesso', 'acessopermissao', 'can'],
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
                permissionAll:[],
                canObj:[]
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
                    var countPaiFilho = 0;

                    if( item.hasOwnProperty("children") )
                    {
                        self.nArrayPermissoes( item.children );

                        for( var filho in item.children )
                        {                            
                            if ( item.children[filho].selected == true ){
                                countPaiFilho++;
                            }
                            if ( item.children[filho].opened == true ){
                                item.opened = true;
                            }
                        }

                        if( item.children.length == countPaiFilho )
                        {
                            item.selected = true;
                        }
                    }
                    if( item.hasOwnProperty("permissoes") && item.permissoes.length > 0 )
                    {
                        item.children = [];
                        for( var permissao in item.permissoes )
                        {                            
                                item.permissoes[permissao].disabled = true;
                            if ( self.acessopermissao.includes( item.permissoes[permissao].id ) ){
                                item.permissoes[permissao].selected = true;
                                countPermissaoFilho++;
                            }else{
                                item.permissoes[permissao].selected = false;
                            }
                            
                            item.children.push(item.permissoes[permissao]);
                        }
                    }

                    if( countPermissaoFilho > 0 )
                    {
                        item.opened = true;
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
            this.nome = this.acesso.nome
            this.canObj = JSON.parse(this.can)


            this.permissionAll = [{
                    "text": "SELECIONAR TODOS",
                    "children":this.permissoes,
                    "opened": true,
                    "icon": "fa fa-check icon-state-success",
                    "selected": false,
                }]

            this.datas = this.nArrayPermissoes(this.permissionAll)

        },
	};

</script>