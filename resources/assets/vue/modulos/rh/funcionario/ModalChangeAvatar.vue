<template>
<modal name="modal-change-avatar" transition="pop-out" :width="modalWidth" height="auto" @before-open="beforeOpen" @opened="opened" before-close="beforeClose">

<div class="modal-content" >
    <div class="modal-header">    
        <button type="button" class="close" @click="$modal.hide('modal-change-avatar')">
            <span aria-hidden="true">&times;</span>
        </button>
    
        <h4 class="modal-title" id="modal-title">EDITAR AVATAR
        </h4>
    </div>
    
    <div class="modal-body">
        <form method="post"  data-toggle="validator" role="form" class="formcadastrar">
            <div class="box-body">
                <div class="form-group" >
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-5 col-xs-5" style="border:solid 1px #CCC; height:240px; width:230px; ">
                                <img :src="'/rh/funcionario/avatar-grande/'+id" class="img-responsive" style="height:225px;    padding: 10px;">
                            </div>
                            <div class="col-md-1 col-xs-1 to-arrow-holder">
                                <i class="fa fa-arrow-right"></i>
                            </div>
                            <div class="col-md-5 col-xs-5" v-if="image" style="border:solid 1px #CCC; height:240px; width:230px; ">
                                <img :src="image" class="img-responsive" style="height:225px;    padding: 10px;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
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
            </div><!-- box-body -->
        </form>
    </div><!-- modal-body -->


    <div class="modal-body" v-if="errors.length > 0 || mensagem != ''">   
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
    </div>      
    <div class="modal-footer">
      
        <button type="button" class="btn btn-danger" @click="$modal.hide('modal-change-avatar')" style="margin-right: 10px;">Cancelar</button>
        <button type="button" class="btn btn-primary btn-sim" :disabled="isDisabled" @click="confirma" autofocus>Salvar</button>
      
    </div>
</div>

</modal>

</template>

<script>
 import { filtroBus } from './../../util/filtroDataTable.js';

const MODAL_WIDTH = 600
export default {
    name: 'ModalChangeAvatar',
    data (){
        return {
            modalWidth: MODAL_WIDTH,
            mensagem: "",
            errors:[],
            edit:false,
            id:'',
            image: '',
            validaBotao:false,
                    
        }
    },
   
    methods: {
        beforeOpen (event){
            this.limpaResponse()
            this.id = event.params
        },
        opened()
        {
            // this.$el.querySelector('.btn-sim').focus()
        },
        beforeClose()
        {
            this.titulo = '';
            this.id = '';
            this.image = '';
        },
        limpaResponse(){
          this.errors = [];
          this.mensagem = "";
          this.image = '';

        },
        confirma(){
            if( this.id != '' )
            {
                this.validaBotao = false
                window.axios.post('/rh/funcionario/'+this.id+'/avatar',{image: this.image})
                    .then(response=>{
                        this.mensagem = response.data.mensagem;
                    var s = this   
                    setTimeout(function(){ 
                         window.location.href = "/rh/funcionario";
                        // s.$modal.hide('modal-change-avatar')
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
            this.validaBotao = true
            reader.readAsDataURL(file);
        },
    },
    computed:{
        isDisabled(){
            if( this.validaBotao  ) {
                return false
            }
            return true
        }
    },
    created () {
      this.modalWidth = window.innerWidth < MODAL_WIDTH
        ? MODAL_WIDTH / 2
        : MODAL_WIDTH
    }
};
</script>