<template>
    <modal name="publicacao-modal" transition="pop-out" :width="modalWidth" height="auto" @before-close="beforeClose"  @before-open="beforeOpen" :clickToClose="false">
        <div class="modal-content" >
            <div class="modal-header">    
                <button type="button" class="close" @click="$modal.hide('publicacao-modal')" ><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="modal-title">{{ modal_titulo }}</h3>
            </div>
            <div class="modal-body">
                <form method="post"  data-toggle="validator" role="form" class="formcadastrar">
                    <div class="box-body row">
                        <div class="form-group">
                            <label >Título:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-11">
                                    <input type="text" id="titulo" class="form-control input-sm" name="titulo"  maxlength="100" v-model="item.titulo" placeholder="Título da publicação" data-error="Este campo é obrigatório." style="text-transform: uppercase;" required>
                                        <div class="help-block with-errors"></div>
                                </div>
                                <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label >Categoria:</label>
                            <div class="row">

                                <div class="col-md-11 col-xs-11">

                                    <div class="form-group">  
                                        <select class="form-control input-sm" v-model="selected">

                                            <option 

                                            v-for="option in options"
                                            :disabled="option.disabled == 'true'" 
                                            :class="option.publicacao_categoria_id == null ? 'pai':'' " 
                                            :value="option.id">
                                                <span v-if="option.publicacao_categoria_id != null"> - </span>{{ option.nome }}
                                            </option>

                                        </select>
                                    </div>

                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label >Resumo:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-11">
                                    <textarea class="form-control input-sm" id="resumo" name="resumo" v-model="item.resumo" placeholder="Resumo da publicação" required data-error="Este campo é obrigatório." style="resize: none;" rows="3"></textarea>  
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label >Conteúdos relacionados:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-11">
                                    <input type="number" class="form-control input-sm" id="lista_relacionados" name="lista_relacionados" v-model="item.lista_relacionados" placeholder="Quantidade de conteúdos relacionados" required data-error="Este campo é obrigatório." min="0" max="99"/>  
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" @click="$modal.hide('publicacao-modal')" type="button">Cancelar</button> 
                        <button class="btn btn-primary" @click="store" :disabled="isDisabled" type="button" v-if="!edit">Salvar</button>
                        <button class="btn btn-primary" @click="update" :disabled="isDisabled" type="button" v-else>Editar</button>
                    </div> 
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
                                <div>{{ mensagem }}</div>
                            </div>
                        </div>
                    </div>   
                </form>
            </div>
        </div>
    </modal>
</template>

<script>
    const MODAL_WIDTH = 600
    export default {
        props:['categoria_id', 'publicacao_id', 'publicacao', 'categorias'],
        name: 'PublicacaoModal',
        data (){
            return {
                alterMethod:"",
                modalWidth: MODAL_WIDTH,
                modal_titulo: 'Adicionar Publicação',
                mensagem: "",
                errors:[],
                item:{
                    titulo: '',
                    resumo: '',
                    lista_relacionados: 5,
                },
                edit:false,
                dados:[],
                validaBotao:false,
                selected: '',
                options: []
           }
        },
        computed:{
            defineMethod: function(){
                if(this.method.toLowerCase() == "post" || this.method.toLowerCase() == "get"){
                    return this.method.toLowerCase();
                }

                if(this.method.toLowerCase() == "put" ){
                    this.alterMethod = "put";
                }
                if(this.method.toLowerCase() == "delete" ){
                    this.alterMethod = "delete";
                }
                return "post";
            },
        },
        methods: {
            store(){
                if(this.item.titulo != '' && this.item.resumo != '' && this.item.lista_relacionados != ''){
                   this.validaBotao = true;
                   this.limpaResponse()
                   this.item.titulo = this.item.titulo.toUpperCase();
                   this.item.categoria_id = this.selected;
                    window.axios.post( '/base-de-conhecimento/publicacoes' , this.item)
                        .then(response=>{

                            this.mensagem = response.data.mensagem;

                            setTimeout(function(){ 
                                window.location.href = "/base-de-conhecimento/publicacoes/"+response.data.id;
                            }, 1000);
                            return 0;
                        })
                        .catch(error => {
                            this.errors = error.response.data.errors;
                            this.validaBotao = false;
                            return 0; 
                        });
                } 
            },
            update(){
                if(this.item.titulo != '' && this.item.resumo != '' && this.item.lista_relacionados != ''){
                    this.validaBotao = true;
                    this.limpaResponse()
                    this.item.titulo = this.item.titulo.toUpperCase();
                    this.item.categoria_id = this.selected;
                    window.axios.put( '/base-de-conhecimento/publicacoes/'+this.item.id, this.item)
                        .then(response=>{
                            
                            this.mensagem = response.data.mensagem;
                            
                            setTimeout(function(){ 
                              window.location.href = "/base-de-conhecimento/publicacoes/"+response.data.id;
                            }, 1000);
                            return 0;
                        })
                        .catch(error => {
                            this.errors = error.response.data.errors;
                            this.validaBotao = false;
                            return 0; 
                        });
                } 
                      
            },
            beforeClose (event) {
                this.limpaInputs();  
                this.limpaResponse();  

            },
            beforeOpen (event){
                if (this.publicacao_id != 0){
                    this.edit = true
                    this.variavelNova()
                }; 
                var categorias = JSON.parse(this.categorias);

                for( var key in categorias )
                {
                    this.options.push(categorias[key]);
                    for( var keyFilho in categorias[key].filho )
                    {
                        this.options.push(categorias[key].filho[keyFilho]);
                    }
                }
                this.selected = this.categoria_id
            },
            limpaInputs () {
                  this.item.titulo = '';
                  this.item.resumo = '';
                  this.item.lista_relacionados = 5;
            },
            limpaResponse(){
                  this.errors = [];
                  this.mensagem = "";
            },
            getUrl(){
                return '/base-de-conhecimento/publicacao/'+this.publicacao_id;
            },
            variavelNova(){
                this.dados = JSON.parse(this.publicacao);
                this.item.id = this.dados.id;
                this.item.titulo = this.dados.titulo;
                this.item.resumo = this.dados.resumo;
                this.item.lista_relacionados = this.dados.lista_relacionados;
            },
            isNumeric: function (n) {
                return !isNaN(parseFloat(n)) && isFinite(n);
            },              
        }, 
        computed:{
            isDisabled(){
                if( (this.item.titulo != '' && this.item.resumo != '' && ( this.item.lista_relacionados >= 0 && this.item.lista_relacionados < 100 && this.isNumeric(this.item.lista_relacionados) ) ) && !this.validaBotao) {
                    return false;
                }
                return true; 
            }
        },
        mounted(){
            this.limpaInputs()
            if (this.publicacao_id != 0){
                this.edit = true;
                this.modal_titulo = 'Editar Publicação';
            };
            
        },     
        created () {
          this.modalWidth = window.innerWidth < MODAL_WIDTH
            ? MODAL_WIDTH / 2
            : MODAL_WIDTH
        }
    };
</script>
<style>
    .pai{
        background-color: #ccc;
    }
</style>
