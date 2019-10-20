<template>
	<modal :name="'texto-modal'+conteudoId" transition="pop-out" :width="modalWidth" height="auto" @before-close="beforeClose" :clickToClose="false">
		<div class="modal-content" >
			<div class="modal-header">
				<button type="button" class="close" @click="$modal.hide('texto-modal'+conteudoId)" ><span aria-hidden="true">&times;</span></button>
				<h3 class="modal-title" id="modal-title">{{ modal_titulo }}</h3>
			</div>
			<div class="modal-body">
				<div class="box-body row">
					<vue-editor v-model="content" :editorToolbar="customToolbar"></vue-editor>
				</div> 
				<div class="modal-footer">
					<button class="btn btn-danger" @click="$modal.hide('texto-modal'+conteudoId)" type="button">Cancelar</button> 
					<button class="btn btn-primary" @click="update" :disabled="isDisabled" type="button" >Salvar</button>
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
	const MODAL_WIDTH = 1200
	export default {
		props:['conteudoId', 'conteudo', 'conteudoUrl'],
		name: 'TextoModal',
		data (){
			return {
				alterMethod:"",
				disabled:false,
				modalWidth: MODAL_WIDTH,
				modal_titulo: 'Editar Texto',
				mensagem: "",
				errors:[],
				item:{
					conteudo: '',
				},
				content: '',  
				validaBotao:false,
				customToolbar: [
		              [{ 'font': ['sans serif', 'monospace'] }],
					  [{ 'header': [false, 1, 2, 3, 4, 5, 6, ] }],
					  ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
					  [{'align': ''}, {'align': 'center'}, {'align': 'right'}, {'align': 'justify'}],
					  ['blockquote', 'code-block'],
					  [{ 'list': 'ordered'}, { 'list': 'bullet' }],
					  // [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
					  // [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
					  [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
					  [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
					  ['link', 'video'],
					  // ['link', 'image', 'video'],
					  ['clean'],                                         // remove formatting button
					  // [{ 'direction': 'rtl' }],                         // text direction
		        ]
			}
		},
		methods: {
			update(){
				this.validaBotao = true;
				this.limpaResponse()
				this.item.conteudo = this.content
							 	
				window.axios.put('/base-de-conhecimento/conteudo/'+this.conteudoId, this.item)
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
				this.limpaResponse();  
			},
			limpaResponse(){
				this.errors = [];
				this.mensagem = "";
			},
			get(){
				window.axios.get('/base-de-conhecimento/tags/'+this.publicacao_id)
				.then((response) => {
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
			this.content = this.conteudo
		},     
		created () {
			this.modalWidth = window.innerWidth < MODAL_WIDTH
			? MODAL_WIDTH / 2
			: MODAL_WIDTH
		}
	};

</script>
<style>
	.ql-editor {
	    min-height: 200px;
	    font-size: 16px;
	    overflow: auto !important;
	    height: 400px !important;
	}
</style>