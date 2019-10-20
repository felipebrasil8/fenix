<template>
    <modal :name="'imagem-link-modal'+conteudoId" transition="pop-out" :width="modalWidth" height="800" @before-close="beforeClose" :clickToClose="false">  
        <div class="modal-content" >
            <div class="modal-header">    
              <button type="button" class="close" @click="$modal.hide('imagem-link-modal'+conteudoId)" ><span aria-hidden="true">&times;</span></button>
              <h3 class="modal-title" id="modal-title">{{ modal_titulo }}</h3>
            </div>
            <div class="modal-body">
                <div class="box-body">
                    <div class="form-group">
                        <label>Link:</label>
                        <div class="row">
                            <div class="col-md-11 col-xs-11">
                                <input type="text" name="adicional" v-model="adicional" class="form-control">
                            </div>

                            <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                <i class="fa fa-asterisk"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Imagem:</label>
                        <div class="row">
                            <div class="col-md-11 col-xs-11">
                                 <label for="arquivo" class="btn btn-sm btn-primary select-image ng-pristine ng-valid ng-empty ng-touched">
                                Escolher nova imagem...
                            </label>
                                <input type="file" v-on:change="onFileChange" id="arquivo" style="display:none;" class="form-control" accept=".jpeg, .png, .jpg, .gif, .svg">
                            </div>                                

                            <div class="col-md-1 col-xs-1" style="padding-left: 0;">
                                <i class="fa fa-asterisk"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" v-if="image">
                        <label>Preview:</label>
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <img :src="image" class="img-responsive" style="width-height: 150px; max-height:150px;">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-danger" @click="$modal.hide('imagem-link-modal'+conteudoId)" type="button">Cancelar</button> 
                    <button class="btn btn-primary" type="button" @click="upload">Salvar</button>
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
            
            </div><!-- modal-body -->

        </div><!-- modal-content -->
    </modal>
</template>
<script>
    const MODAL_WIDTH = 600;
    export default {
        props:['conteudoId', 'link', 'conteudoUrl'],
        name: 'ImagemLinkModal',
        data (){
            return {
                image: '',
                modalWidth: MODAL_WIDTH,
                modal_titulo: 'Editar Imagem',
                mensagem: "",
                errors:[],
                validaBotao:false,
                adicional: '',
            }
        },      
        methods: {
            onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;

                if (!files.length)                    
                    return;

                //4194304KB = 4MB
                if (files[0].size > 4194304)
                    return;                

                if (files[0].type == "image/jpeg" || files[0].type == "image/png" || files[0].type == "image/jpg" || files[0].type == "image/gif" || files[0].type == "image/svg") {
                    this.createImage(files[0]);
                }                   
                return;
            },
            createImage(file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => {
                    vm.image = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            upload(){
                window.axios.put('/base-de-conhecimento/conteudo/'+this.conteudoId,{image: this.image, adicional: this.adicional})
                .then(response=>{

                    this.mensagem = response.data.mensagem;
                    var url = this.conteudoUrl;
                    if( !(url == 'rascunho' || url == 'edit') )
                    {
                        url = 'edit';
                    }
                    setTimeout(function(){ 
                        window.location.href = "/base-de-conhecimento/publicacoes/"+response.data.publicacao_id+"/"+url;
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
                this.image = false
            }   
        }, 
        created () {     
            this.modalWidth = window.innerWidth < MODAL_WIDTH
            ? MODAL_WIDTH / 2
            : MODAL_WIDTH
        },
        mounted(){

            if( typeof this.link === 'string' ){
                this.adicional = this.link;
            }
            else
            {
                this.adicional = 'http://';
            }
        }, 
    };
</script>