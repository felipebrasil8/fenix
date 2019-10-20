<template>
    <div class="box box-default">
        <form role="form" data-toggle="validator" name="form">

            <div class="box-body row" style="padding-bottom: 0;">

                <div class="form-group col-md-3">
                    <label for="nome">Nome:</label>
                    <div class="row">
                        <div class="col-md-11 col-xs-10">
                            <input type="text" class="form-control input-sm" id="nome" data-error="Este campo é obrigatório." style="text-transform: uppercase;" v-model="perfil.nome" required maxlength="100">
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                            <i class="fa fa-asterisk"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-body row">


                <div class="form-group col-md-3">
                    <label for="nome">Dias de acesso permitidos:</label>
                    <div class="row">
                        <div class="col-md-11 col-xs-10">
                            <select v-model="perfil.todos_dias" class="form-control input-sm">
                                <option value="false">Dias da semana</option>
                                <option value="true">Todos os dias</option>
                            </select>
                        </div>
                        <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                            <i class="fa fa-asterisk"></i>
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-4">
                    <div class="row">
                        <label class="col-md-12">Hórario de acesso permitido:</label>
                        <div class="col-md-5">
                            <div class="input-group" id="pesquisa_de">
                                <date-picker v-model="perfil.horario_inicial" id="pesquisa_de" name="horario_inicial" :config="config1" @dp-change="setMaxDate" class="input-sm" readonly></date-picker>
                                <label class="input-group-addon" for="pesquisa_de">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2 text-center"><label style="vertical-align: sub;">até</label></div>
                        <div class="col-md-5">
                            <div class="input-group date" id="">
                                <date-picker v-model="perfil.horario_final" name="horario_final" :config="config2"  @dp-change="setMinDate" class="input-sm" readonly></date-picker>
                                <label class="input-group-addon" for="pesquisa_ate">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-3">
                    <label for="nome">Rede permitida:</label>
                    <input type="text" class="form-control input-sm" id="configuracao_de_rede" style="text-transform: uppercase;" v-model="perfil.configuracao_de_rede">
                </div>
            </div>

            <div class="box-body row" style="padding-bottom: 0;">

                <div class="form-group col-md-12">
                    <label for="nome_completo">Acessos: <i class="fa fa-asterisk" style="padding-left: 20px;"></i></label>
                    <v-jstree :data="datas" show-checkbox multiple allow-batch whole-row @item-click="itemClick"></v-jstree>
                </div>

            </div>
            <div class="box-footer">
                <button type="button" class="btn btn-primary" @click="salvar" :disabled="isDisabled">Salvar</button>
            </div>
        </form>
    </div>

</template>

<script>
    import 'eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css';    
    import { perfilAcessoBus } from './perfilAcessoBus.js';
    export default {
        props:['acessos', 'can', 'perfilParam'],
        data () {
            return {
                acessosObj: {},
                canObj: {},
                acessosAll: {},
                datas:[],
                config1: {
                    format: 'HH:mm:ss',
                    useCurrent: false,                    
                    showClose: true,
                    locale: 'pt-br',
                    ignoreReadonly: true,
                    minDate: new Date( new Date().getFullYear()+'-'+(new Date().getMonth()+1)+'-'+new Date().getDate()+' 00:00:00' ),
                    
                },
                config2: {
                    format: 'HH:mm:ss',
                    useCurrent: false,                    
                    showClose: true,
                    locale: 'pt-br',
                    ignoreReadonly: true,
                    maxDate: new Date( new Date().getFullYear()+'-'+(new Date().getMonth()+1)+'-'+new Date().getDate()+' 23:59:59' )
                },
                perfil: {},
                acessoSelecionados:[],
            }
        },
        methods:
        {
            setMaxDate(){
                if (this.perfil.horario_inicial >= this.perfil.horario_final){
                    this.perfil.horario_inicial = new Date( new Date().getFullYear()+'-'+(new Date().getMonth()+1)+'-'+new Date().getDate()+' 00:00:00' )

                }
            },
            setMinDate(){
                
                if (this.perfil.horario_final <= this.perfil.horario_inicial ){
                    this.perfil.horario_final = new Date( new Date().getFullYear()+'-'+(new Date().getMonth()+1)+'-'+new Date().getDate()+' 23:59:59' )
                }
            },
            salvar()
            {
                this.$emit('salvar', this.perfil)
            },
            itemClick (node) {  
                this.perfil.acessos = []
                this.setAcessoSelecionado( this.datas )
            },
            setAcessoSelecionado( acessos ){
                this.acessoSelecionados = []
                
                for( var filho in acessos[0].children )
                {
                    if( acessos[0].children[filho].selected == true )
                    {
                        this.acessoSelecionados.push(acessos[0].children[filho].id);
                    }
                }

                this.perfil.acessos = this.acessoSelecionados;
            },
            initAcessos()
            {
                this.perfil = {
                    nome: '', 
                    acessos: [],
                    todos_dias: true,
                    horario_inicial: '00:00:00',
                    horario_final: '23:59:59',
                    configuracao_de_rede: '',
                };

                this.acessosAll = [{
                    "text": "SELECIONAR TODOS",
                    "children": this.acessosObj,
                    "opened": true,
                    "icon": "fa fa-check icon-state-success",
                    "selected": false,
                }]

                this.datas = this.acessosAll
            },
        },
        computed:{
            isDisabled(){
                if( this.perfil.nome != '' && this.acessoSelecionados.length > 0 ) {
                    return false;
                }
                return true; 
            }
        },
        mounted()
        {
            this.canObj = JSON.parse(this.can);
            this.acessosObj = JSON.parse(this.acessos);

            perfilAcessoBus.$on('updateAcesso', event => {
                this.acessosObj = JSON.parse(this.acessos);
                this.acessoSelecionados = [];
                this.initAcessos();
            });

            this.initAcessos();

            if(this.perfilParam)
            {
                this.acessoSelecionados = this.perfilParam.acessos
                this.perfil = this.perfilParam;

                for( var acesso in this.acessosObj )
                {
                    if ( this.perfil.acessos.includes( this.acessosObj[acesso].id ) ){
                        this.acessosObj[acesso].selected = true;
                    }
                }
            }
        },
        created()
        {
            //console.log(this.perfilParam);
        },
    };
</script>
