<template>
    <modal name="tags-modal" transition="pop-out" :width="modalWidth" height="auto" @before-close="beforeClose"  @before-open="beforeOpen" :clickToClose="false">
        <div class="modal-content" >
            <div class="modal-header">    
                <button type="button" class="close" @click="$modal.hide('tags-modal')" ><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="modal-title">{{ modal_titulo }}</h3>
            </div>
            <div class="modal-body">
                <div class="box-body row">
                    <div class="form-group">
                        <label >Tags:</label>
                        <div class="row">
                            <div class="col-md-11 col-xs-11">
                                <input type="text" 
                                    id="tag" 
                                    class="form-control input-sm"  
                                    name="tag"  
                                    v-model="item.tag" 
                                    placeholder="Tags da publicação" 
                                    style="text-transform: uppercase;" 
                                    >
                            </div>
                            <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                <i class="fa fa-asterisk"></i>
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="modal-footer">
                    <button class="btn btn-danger" @click="$modal.hide('tags-modal')" type="button">Cancelar</button> 
                    <button class="btn btn-primary" @click="update" autofocus="" :disabled="isDisabled" type="button" >Salvar</button>
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
            </div>
        </div>
    </modal>
</template>
<script>
    const MODAL_WIDTH = 600
    export default {
        props:['publicacao_id', 'request'],
        name: 'TagsModal',
        data (){
            return {
                alterMethod:"",
                disabled:false,
                modalWidth: MODAL_WIDTH,
                modal_titulo: 'Editar Tags',
                mensagem: "",
                errors:[],
                item:{
                    tag: '',
                },
                validaBotao:false,
            }
        },
        methods: {
            update(){
                this.validaBotao = true;
                this.limpaResponse()
                this.item.tag = this.item.tag.toUpperCase();
                this.item.publicacao_id = this.publicacao_id;
                 var url = this.request;
                if( !(url == 'rascunho' || url == 'edit') )
                {
                    url = 'edit';
                }
                this.item.rascunho = (url == 'rascunho' ? true : false);
                window.axios.post('/base-de-conhecimento/tags', this.item)
                .then(response=>{
                    this.mensagem = response.data.mensagem;
                    setTimeout(function(){ 
                      window.location.href = "/base-de-conhecimento/publicacoes/"+response.data.id+"/"+url;
                    }, 1000, url);
                    return 0;
                 })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.validaBotao = false;
                    return 0; 
                });
            },
            beforeClose (event) {
                this.limpaInputs();  
                this.limpaResponse();  

            },
            beforeOpen (event){
                this.limpaInputs()  
                this.limpaResponse()  
                if( this.request == 'rascunho' )
                {
                    this.getRascunho();
                }
                else
                {
                    this.get();
                }
            },
            limpaInputs () {
                this.item.tag = '';
             
            },
            limpaResponse(){
                this.errors = [];
                this.mensagem = "";
            },
            get(){
                window.axios.get('/base-de-conhecimento/tags/'+this.publicacao_id).then((response) =>
                {
                    this.item.tag = response.data.tags
                });
            },
             getRascunho(){
                window.axios.get('/base-de-conhecimento/tags/'+this.publicacao_id+'/rascunho').then((response) =>
                {
                    this.item.tag = response.data.tags
                });
            },
        }, 
        computed:{
            isDisabled(){
                if(!this.validaBotao){
                    return false;
                }
                return true; 
            }
        },
        mounted(){
            this.limpaInputs()
        },     
        created () {
            this.modalWidth = window.innerWidth < MODAL_WIDTH
            ? MODAL_WIDTH / 2
            : MODAL_WIDTH
        }
    };
</script>