<template>
	<div>
        <div class="row" v-if="permissao.publicacoes">
            <div class="col-sm-6 col-lg-2 form-group">
                <button class="btn btn-primary btn-md btn-block" @click="setExcel('publicacoes');downloadExcel()">
                    <span class="pull-left">
                        <i class="fa fa-share-alt"></i>
                        &nbsp;PUBLICAÇÕES                        
                    </span>
                </button>
            </div>
        </div>
    
        <div class="row" v-if="permissao.pesquisa">
            <div class="col-sm-6 col-lg-2 form-group">
                <vc-modal-button texto="PESQUISAS" btn-class="btn-primary btn-md btn-block" icone="fa-search" modal="modal-intervalo-data" @eventoclick="setExcel('pesquisa')" texto-botao-direcao="pull-left"></vc-modal-button>
            </div>
        </div>


        <div class="row" v-if="permissao.visualicoes">
            <div class="col-sm-6 col-lg-2 form-group">
                <vc-modal-button texto="VISUALIZAÇÕES" btn-class="btn-primary btn-md btn-block" icone="fa-hand-pointer-o" @eventoclick="setExcel('visualicoes')" modal="modal-intervalo-data" texto-botao-direcao="pull-left"></vc-modal-button>
            </div>
        </div>
    
        <div class="row" v-if="permissao.recomendacoes">
            <div class="col-sm-6 col-lg-2 form-group">
                <vc-modal-button texto="RECOMENDACÕES" btn-class="btn-primary btn-md btn-block" icone="fa-user"  @eventoclick="setExcel('recomendacoes')" modal="modal-intervalo-data" texto-botao-direcao="pull-left"></vc-modal-button>
            </div>
        </div>


        <vc-intervalo-data-modal @dates="downloadExcel" ></vc-intervalo-data-modal>
        
        <form target="_blank" action="/base-de-conhecimento/exportacoes/download" method="post" id="excel">
            <input type="text" name="titulo_excel" :value="titulo_excel"  hidden="hidden">
            <input type="text" name="selected_date_de" :value="selected_date_de" hidden="hidden" >
            <input type="text" name="selected_date_ate" :value="selected_date_ate" hidden="hidden" >
        </form>

	</div>

</template>
<script>
	const MODAL_WIDTH = 600;
    export default {
        props:['can'],
        data (){
            return {
                permissao:[],
                download:'',
                titulo_excel:'',
                selected_date_de:'',
                selected_date_ate:'',
            }
        },
        methods: { 
            downloadExcel(datas){
                              
                if ( this.titulo_excel != 'publicacoes' ) {
                    this.selected_date_de = datas.de
                    this.selected_date_ate = datas.ate
                } 
                
                setTimeout(function(){ 
                    $('#excel').submit();
                }, 100);
                return 0;
            
            },
            setExcel(excel){
                this.titulo_excel = excel
            },
            
        },
        mounted(){
            this.permissao = JSON.parse(this.can)
         
        }
        
    };

	
</script>