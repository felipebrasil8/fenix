<template>
    <modal name="modal-intervalo-data" transition="pop-out" :width="modalWidth" height="500px" @before-open="beforeOpen" :clickToClose="false">
        <div class="modal-content" >
            <div class="modal-header">    
              <button type="button" class="close" @click="$modal.hide('modal-intervalo-data')" ><span aria-hidden="true">&times;</span></button>
              <h3 class="modal-title" id="modal-title">{{ modal_titulo }}</h3>
            </div>
            <div class="modal-body">                
                <form target="_blank" action="/base-de-conhecimento/publicacoes/download/xlsx/pesquisa" method="post">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="box-body">
                                <div class="form-group">
                                    <label >De:</label>
                                    <div class="row">                                       
                                        <div class="input-group date">
                                            <date-picker v-model="pesquisa.de" name="de" :config="config" id="pesquisa_de" readonly></date-picker>
                                            <label class="input-group-addon" for="pesquisa_de">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>                              
                        </div>
                        <div class="col-sm-6">
                            <div class="box-body">
                                <div class="form-group">
                                    <label >Até:</label>

                                    <div class="row">                                       
                                        <div class="input-group date">
                                            <date-picker v-model="pesquisa.ate" name="ate" :config="config" id="pesquisa_ate" readonly></date-picker>
                                            <label class="input-group-addon" for="pesquisa_ate">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </label>
                                        </div>        
                                    </div>
                                </div>
                            </div>                              
                        </div>           
                    </div><!-- .row -->

                    <div class="modal-footer">
                        <button class="btn btn-danger" @click="$modal.hide('modal-intervalo-data')" type="button">Cancelar</button> 
                        <button class="btn btn-primary" :disabled="isDisabled" @click="dates($event);$modal.hide('modal-intervalo-data')" type="submit" >Filtrar</button>
                    </div>
                </form>                
            </div><!-- .modal-body -->
        </div><!-- .modal-content -->
    </modal>
</template>

<script>
    import 'eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css';    
    const MODAL_WIDTH = 600
    export default {
        props:['de', 'ate', 'titulo'],
        name: 'ModalIntervaloData',
        data (){
            return {                
                pesquisa: {
                    de: '',
                    ate: ''
                },                
                modalWidth: MODAL_WIDTH,
                modal_titulo: 'Exportar Pesquisa',                
                validaBotao:false, 
                hoje: '',
                mes_ate:'',
                mes_de:'',

                // Configuração do campo de data
                config: {
                    format: 'DD/MM/YYYY',
                    useCurrent: false,                    
                    showClose: true,
                    locale: 'pt-br',
                    ignoreReadonly: true
                }                
            }
        },
        methods: {            
          
            beforeOpen (event){

                this.hoje = new Date();
                this.mes_ate = this.hoje.getMonth() < 9 ?  '0'+eval(this.hoje.getMonth()+1) : eval(this.hoje.getMonth()+1) 
                this.mes_de = this.hoje.getMonth() < 9 ?  '0'+eval(this.hoje.getMonth()) : eval(this.hoje.getMonth())

                this.pesquisa.ate = ''+this.hoje.getDate()+'/'+ this.mes_ate+'/'+this.hoje.getFullYear()
                this.pesquisa.de = ''+this.hoje.getDate()+'/'+this.mes_de+'/'+this.hoje.getFullYear()
                
            },  
            
            dates (ev) {
                ev.preventDefault()
                this.$emit('dates', this.pesquisa)
            },          
           
        }, 
        computed:{
            isDisabled(){
                if(this.pesquisa.de == null || this.pesquisa.ate == null){
                    return true; 
                }
                 return false; 
            }
        },
        mounted(){
            if(this.titulo){
                this.modal_titulo = this.titulo 
            }
        },
        created () {
          this.modalWidth = window.innerWidth < MODAL_WIDTH
            ? MODAL_WIDTH / 2
            : MODAL_WIDTH
        }
    }; 
</script>
