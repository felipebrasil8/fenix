<template>
    <modal :name="'categoria-modal'" transition="pop-out" :width="modalWidth" height="auto" @before-close="beforeClose"  @before-open="beforeOpen" :clickToClose="false">
        <div class="modal-content" >
            <div class="modal-header">    
                <button type="button" class="close" @click="$modal.hide('categoria-modal')" ><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="modal-title">{{ modal_titulo }}</h3>
            </div>
            
                <div class="modal-body">
                    <div class="box-body row">
                        <div class="form-group" v-if="hasFilho == false">
                            <label >Pertence a categoria:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-11">
                                    <div class="form-group">  
                                        <select class="form-control input-sm" v-model="selected">
                                            <option value=""></option>
                                            <option 
                                            v-for="option in options"
                                            :value="option.id"
                                            v-if="option.publicacao_categoria_id == null && option.id != dados.id">
                                                {{ option.nome }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label >Nome:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-11">
                                    <input type="text" id="nome" class="form-control input-sm" name="nome" maxlength="100" v-model="item.nome" placeholder="Nome da categoria" data-error="Este campo é obrigatório." style="text-transform: uppercase;" required>
                                        <div class="help-block with-errors"></div>
                                </div>
                                <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label >Descrição:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-11">
                                    <input type="text" id="descricao" class="form-control input-sm" name="descricao" maxlength="100" v-model="item.descricao" placeholder="Descrição da categoria" data-error="Este campo é obrigatório." style="text-transform: uppercase;" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <input type="hidden" name="modal_icone" :value="modal_icone">
                            <label for="nome">Ícone: <div style="display: inline; padding-left: 5px; padding-right: 0;"><i :class="'fa '+icone_view"></i></div></label>
                            <div class="row">
                                <div class="col-md-5 col-xs-11">
                                    <div :class="'input-group '+[permissao_categoria.categoria_ordenar == false ? 'dropup' : '']">
                                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="display: table-row-group;">
                                            <input type="text" class="form-control input-sm" v-model="modal_icone_view" name="modal_icone_view" readonly="" required="required">
                                            <div class="input-group-btn">
                                                <button type="button" class="btn btn-default input-sm">
                                                    <span class="fa fa-caret-down"></span>
                                                </button>
                                            </div>
                                        </a>
                                        <ul class="dropdown-menu" style="height: 150px; overflow: auto; left: auto; right: 0; width: 100%;">
                                            <li role="presentation" v-for="icon in listaIcones" :class="[ icon.icone == modal_icone ? 'active_icone': ''] ">
                                                <a role="menuitem" tabindex="-1" @click="setIcone( icon )"><i :class="'fa '+icon.icone+' margin-r-5'"></i> {{icon.nome}}</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" v-if="permissao_categoria.categoria_ordenar">
                            <label >Ordem:</label>
                            <div class="row">
                                <div class="col-md-5 col-xs-11">
                                    <input type="number" class="form-control input-sm" id="ordem" name="ordem" v-model="item.ordem" placeholder="Ordem" required data-error="Este campo é obrigatório." min="1" max="9999"/>  
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                    <i class="fa fa-asterisk"></i>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" @click="$modal.hide('categoria-modal')" type="button">Cancelar</button> 
                    <button class="btn btn-primary" @click="store" :disabled="isDisabled" type="button" v-if="!edit">Salvar</button>
                    <button class="btn btn-primary" @click="update" :disabled="isDisabled" type="button" v-else>Editar</button>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="callout no-margin-bottom callout-danger" v-for="value in errors" style="margin-top: 15px;">
                                <div style="text-align: left;">{{ value[0] }}</div>
                            </div>
                        </div>
                    </div>   
                    <div class="row" v-if="mensagem">
                        <div class="col-md-12">
                            <div class="callout no-margin-bottom callout-success" style="margin-top: 15px;">
                                <div style="text-align: left;">{{ mensagem }}</div>
                            </div>
                        </div>
                    </div>   
                </div> 
        </div>
    </modal>
</template>

<script>
    const MODAL_WIDTH = 600
    export default {
        props:['categoria', 'categorias', 'icones', 'can_categoria'],
        name: 'CategoriaModal',
        data (){
            return {
                alterMethod:"",
                modalWidth: MODAL_WIDTH,
                modal_titulo: 'Adicionar Categoria',
                mensagem: "",
                errors:[],
                item:{
                    nome: '',
                    descricao: '',
                    icone: 0,
                    ordem: 1,
                },
                edit:false,
                dados:[],
                validaBotao:false,
                selected: '',
                options: [],
                icone_view: '',
                modal_icone_view: '',
                modal_icone: '',
                listaIcones: {},
                isFilho: false,
                hasFilho: false,
                permissao_categoria: {},
                categoriaId: 0,
           }
        },
        methods: {
            store(){
                if(this.item.nome != '' && this.item.descricao != '' && this.modal_icone != '' && this.item.ordem != ''){
                    this.validaBotao = true;
                    this.limpaResponse()
                    this.item.nome = this.item.nome;
                    this.item.descricao = this.item.descricao;
                    this.item.categoria_id = this.selected;
                    this.item.icone = this.modal_icone.substring(3);
                    if( this.permissao_categoria.categoria_ordenar == false  )
                    {
                        this.item.ordem = 0;
                    }
                    window.axios.post( '/base-de-conhecimento/categoria' , this.item)
                        .then(response=>{
                            this.mensagem = response.data.mensagem;

                            setTimeout(function(){ 
                                window.location.reload();
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
                if(this.item.nome != '' && this.item.descricao != '' && this.modal_icone != '' && this.item.ordem != ''){
                    this.validaBotao = true;
                    this.limpaResponse()
                    this.item.nome = this.item.nome;
                    this.item.descricao = this.item.descricao;
                    this.item.categoria_id = this.selected;
                    this.item.icone = this.modal_icone.substring(3);
                    if( this.permissao_categoria.categoria_ordenar == false  )
                    {
                        this.item.ordem = 0;
                    }

                    window.axios.put( '/base-de-conhecimento/categoria/'+this.item.id, this.item)
                        .then(response=>{
                            
                            this.mensagem = response.data.mensagem;
                            
                            setTimeout(function(){ 
                                window.location.reload();
                            }, 1000);
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
                if(event.params == 0)
                {
                    this.categoriaId = 0;
                }
                else
                {
                    this.categoriaId = event.params.id;
                    this.dados = event.params
                }

                if (this.categoriaId != 0){
                    this.edit = true
                    this.variavelNova()
                    this.modal_titulo = 'Editar Categoria';
                }; 
                this.options = JSON.parse(this.categorias)
                if( this.isNumeric(this.dados.publicacao_categoria_id) )
                {
                    this.isFilho = true;
                    this.selected = this.dados.publicacao_categoria_id;
                }
            },
            limpaInputs () {
                  this.item.nome = '';
                  this.item.descricao = '';
                  this.item.ordem = 1;
            },
            limpaResponse(){
                  this.errors = [];
                  this.mensagem = "";
            },
            getUrl(){
                return '/base-de-conhecimento/categoria/'+this.categoriaId;
            },
            variavelNova(){
                this.item.id = this.dados.id;
                this.item.nome = this.dados.nome;
                this.item.descricao = this.dados.descricao;
                this.item.ordem = this.dados.ordem;
                this.modal_icone = 'fa-'+this.dados.icone;
                this.icone_view = 'fa-'+this.dados.icone;

                if( this.dados.filho && Object.keys(this.dados.filho).length > 0 )
                {
                    this.hasFilho = true;
                }
                else
                {
                    this.hasFilho = false;
                    this.selected = '';
                }               

                var self = this;

                this.listaIcones.map(function (icone) {
                    if(icone.icone == self.icone_view)
                    {
                        self.modal_icone_view = icone.nome;
                    }
                });
            },
            isNumeric: function (n) {
                return !isNaN(parseFloat(n)) && isFinite(n);
            },
            setIcone: function ( icone ){
                this.icone_view = icone.icone;
                this.modal_icone_view = icone.nome;
                this.modal_icone = icone.icone;
            }             
        }, 
        computed:{
            isDisabled(){
                if( (this.item.nome != '' && this.item.descricao != '' && this.modal_icone != '' && ( this.item.ordem > 0 && this.item.ordem < 10000 && this.isNumeric(this.item.ordem) ) ) && !this.validaBotao) {
                    return false;
                }
                return true; 
            }
        },
        mounted(){
            this.listaIcones = JSON.parse(this.icones);
            this.permissao_categoria = JSON.parse(this.can_categoria);
            this.limpaInputs()
        },     
        created () {
          this.modalWidth = window.innerWidth < MODAL_WIDTH
            ? MODAL_WIDTH / 2
            : MODAL_WIDTH
        }
    };
</script>
<style>
.active_icone{
    background-color: #e1e3e9;
    color: #333;
}
</style>
