<template>
    <modal name="busca-sem-resultado-modal" transition="pop-out" :width="modalWidth" height="auto">

        <div class="modal-content" >
            <div class="modal-header">    
                <button type="button" class="close" @click="$modal.hide('busca-sem-resultado-modal')">
                    <span aria-hidden="true">&times;</span>
                </button>
            
                <h4 class="modal-title" id="modal-title">{{tituloModal}}</h4>
            </div>

            <div class="modal-body" style="height: 460px; overflow-y: scroll;">
          
                <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>Data e hora</th>
                    <th>Usuário</th>
                    <th>Pesquisa</th>
                    <th>  <div style=" text-align: center;">Quatidade</div></th>
                    <th>  <div style=" text-align: center;">Tratado</div></th>
                  </tr>
                  </thead>
                  <tbody>
                  
                    <tr v-for="item in content">
                        <td>{{item.min}}</td>
                        <td>{{item.nome}}</td>
                        <td>
                            <div class="truncate" style="width:150px; white-space: nowrap !important">
                                <span  data-toggle="tooltip" :title="item.busca">{{item.busca}}</span>
                            </div>
                        </td>
                        <td>
                            <div style=" text-align: center;">
                                {{item.count}}
                            </div>
                        </td>
                        <td>
                            <div style=" text-align: center;cursor: pointer;">
                                <i class="fa fa-square-o" style="color: #3c8dbc;" @click="$modal.show('modal-confirm'+item.id+item.busca)"></i>
                            </div>
                        </td>
                        <vc-modal-confirm :modalId="item.id+item.busca" ajax="/base-de-conhecimento/publicacoes/setBuscaTratada" :tituloModal="'Deseja tratar a busca '+item.busca+' do usuario '+item.nome+'?'" @confirma="confirma" evento='true' :content="item" ></vc-modal-confirm>
                        <!-- <vc-modal-confirm :modalId="item.id" :tituloModal="msgModalConfirm+item.nome+'?'" :ajax="getUrlConteudo()"></vc-modal-confirm> -->
                    </tr>
                  
                  </tbody>
                </table>
              </div>


            </div>
            <div class="modal-body" v-if="errors.length > 0 || mensagem != ''">   
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
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" @click="$modal.hide('busca-sem-resultado-modal')" style="margin-right: 10px;">FECHAR</button>
              
            </div>
        </div>

    </modal>

</template>

<script>

function atualizaPagina(pagina) {
    window.location.href = pagina;
}

const MODAL_WIDTH = 800

export default {
    props:['content'],
    name: 'BuscaSemResultadoModal',
    data (){
        return {
            modalWidth: MODAL_WIDTH,
            mensagem: "",
            tituloModal:"Buscas sem resultado",
            dados_response:[],
            errors:[],
            edit:false,            
        }
    },
      
    methods: {
        confirma(){
            this.$emit('atualiza')
        }
        
    }, 
    mounted(){
    },     
    created () {
      this.modalWidth = window.innerWidth < MODAL_WIDTH
        ? MODAL_WIDTH / 2
        : MODAL_WIDTH
    }
};
</script>

