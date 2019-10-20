<template>
    <div class="vue-visualizar">
        <div class="row">

            <!-- left column -->
            <div class="col-md-12">

                <!-- general form elements -->
                <div class="box box-default">

                    <form role="form" data-toggle="validator" name="form" >

                        <div class="box-body row" style="padding-bottom: 0;">
                            <div class="form-group col-md-3">
                                <label>Nome:</label>
                                <div class="row">
                                    <div class="col-md-11 col-xs-10">
                                        <input type="text" class="form-control input-sm" name="nome" v-model="nome" style="text-transform: uppercase;" maxlength="100" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                                        <i class="fa fa-asterisk"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                                        
                        <div class="box-body row" style="padding-top: 0;">
                                
                            <div class="form-group col-md-12">
                                
                                <label>Permissões:</label>
                            
                                <div class="form-group">
                                    
                                    <v-jstree :data="datas" show-checkbox multiple allow-batch whole-row @item-click="itemClick"></v-jstree>

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
        props:['permissoes', 'acesso', 'acessopermissao'],
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
                permissionAll:[]
            }
        },
        methods:{
            itemClick (node) {  
                this.permission = []
                this.setPermissaoSelecionada( this.datas )
            },
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
            setPermissaoSelecionada( permissoes ){
                let self = this;
              
                permissoes.filter( function( item )
                {
                    if( item.hasOwnProperty("children") )
                    {
                        self.setPermissaoSelecionada( item.children );
                    }
                    if( item.hasOwnProperty("permissoes") && item.permissoes.length > 0 )
                    {
                        for( var permissao in item.permissoes )
                        {
                            if( item.permissoes[permissao].selected == true )
                            {
                                self.permission.push(item.permissoes[permissao].id);
                            }
                        }
                    }
                });
            },
            store(){
                if(this.nome != '' &&  !this.validaBotao){
                    this.limpaResponse();
                    this.validaBotao = true;
                    this.item.nome = this.nome
                    this.item.permissoes = this.acessopermissao
                    this.item.id = this.acesso.id
                    
                    window.axios.post( '/configuracao/acesso', this.item)
                        .then(response=>{
                            this.mensagem = response.data.mensagem;
                            this.url = '/configuracao/acesso/'+response.data.id;
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
                if( this.nome != '' &&  !this.validaBotao  ) {
                    return false
                }
                return true
            }
        },
        mounted()
        {   
            this.nome = this.acesso.nome

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

<style>
    .vue-visualizar .tree-anchor{
        cursor: default !important;
    }
</style>
