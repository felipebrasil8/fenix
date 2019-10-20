<template>
    <modal name="publicacao-imagem-capa-modal" transition="pop-out" :width="modalWidth" height="800" @before-close="beforeClose" :clickToClose="false">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" @click="$modal.hide('publicacao-imagem-capa-modal')" ><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="modal-title">{{ modal_titulo }}</h3>
            </div>
            <div class="modal-body">
                <form method="post"  data-toggle="validator" role="form" class="formcadastrar">
                    <div class="box-body">
                        <div class="form-group">
                            <label>Imagem:</label>
                            <div class="row">
                                <div class="col-md-11 col-xs-11">
                                    <label for="arquivo" class="btn btn-sm btn-primary select-image ng-pristine ng-valid ng-empty ng-touched">
                                        Escolher nova imagem...
                                    </label>
                                    <input type="file" id="arquivo" style="display:none;" v-on:change="onFileChange" class="form-control file" accept=".jpeg, .png, .jpg, .gif, .svg">
                                </div>

                                <div class="col-md-1 col-xs-1" style="padding-left: 0;margin-top: 7px;">
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
                    </div><!-- box-body -->
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" @click="$modal.hide('publicacao-imagem-capa-modal')">Cancelar</button> 
                        <button class="btn btn-primary" type="button" @click="upload" v-bind:disabled="!image">Salvar</button>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="callout no-margin-bottom callout-danger" v-for="value in errors" style="margin-top: 15px;">
                                <div>{{ value }}</div>
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
        props:['publicacao_id'],      
        name: 'PublicacaoImagemCapaModal',
        data (){
            return {
                modalWidth: MODAL_WIDTH,
                modal_titulo: 'Editar capa',
                image: false,
                mensagem: "",
                errors:[],
                validaBotao:true            
            }
        },    
        methods: {
            onFileChange(e) {
                let files = e.target.files;
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
                this.validaBotao = true;
                window.axios.post('/base-de-conhecimento/publicacao/'+this.publicacao_id+'/imagem_capa',{imagem: this.image})
                .then(response=>{                
                    this.mensagem = response.data.mensagem;
                    setTimeout(function(){ 
                       window.location.href = "/base-de-conhecimento/publicacoes/"+response.data.publicacao_id+"/edit";
                    }, 1000);
                    return 0;
                })
                .catch(error => {                    
                       this.errors = error.response.data.errors;
                       this.validaBotao = false;
                       return 0; 
                });
            },
            beforeClose (event) {            
                this.image= false;
            }      
        }, 
    };
</script>