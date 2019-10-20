<template>
    <div class="box box-default">
        <form role="form" data-toggle="validator" name="form">

            <div class="box-header with-border">
                <i class="fa fa-user"></i>
                <h3 class="box-title">Dados pessoais</h3>
            </div>

            <div class="box-body row" style="padding-bottom: 0;">

                <div class="form-group col-md-3">
                    <label for="nome">Nome:</label>
                    <div class="row">
                        <div class="col-md-11 col-xs-10">
                            <input type="text" class="form-control input-sm" id="nome" data-error="Este campo é obrigatório." style="text-transform: uppercase;" v-model="funcionario.nome" required maxlength="100">
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                            <i class="fa fa-asterisk"></i>
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-3">
                    <label for="nome_completo">Nome Completo:</label>
                    <div class="row">
                        <div class="col-md-11 col-xs-10">
                            <input type="text" class="form-control input-sm" id="nome_completo" data-error="Este campo é obrigatório." style="text-transform: uppercase;" v-model="funcionario.nome_completo" required maxlength="100">
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                            <i class="fa fa-asterisk"></i>
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-3">
                    <label for="email">E-mail:</label>
                    <div class="row">
                        <div class="col-md-11 col-xs-10">
                            <div class="input-group">
                                <input type="text" class="form-control input-sm" id="email" data-error="Este campo é obrigatório." style="text-transform: lowercase;" v-model="funcionario.email" required maxlength="50">
                                <div class="input-group-addon">
                                    <i class="fa fa-envelope-o"></i>
                                </div>
                            </div>
                            <div class="help-block with-errors"></div> 
                        </div>
                        <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                            <i class="fa fa-asterisk"></i>
                        </div>
                    </div>
                </div>
                        
                <div class="form-group col-md-3">
                    <label for="email">Data de nascimento:</label>
                    <div class="row">
                        <div class="col-md-11 col-xs-10">
                            <div class="input-group date">
                                <date-picker v-model="funcionario.dt_nascimento" name="dt_nascimento" :config="config" id="dt_nascimento" readonly required data-error="Este campo é obrigatório."></date-picker>
                                <label class="input-group-addon" for="dt_nascimento">
                                    <span class="fa fa-calendar"></span>
                                </label>
                            </div>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                            <i class="fa fa-asterisk"></i>
                        </div>
                    </div>
                </div>
            </div>
                    
            <div class="box-header with-border">
                <i class="fa fa-book"></i>
                <h3 class="box-title">Contatos</h3>
            </div>
            <div class="box-body row" style="padding-bottom: 0;">

                <div class="form-group col-md-3">
                    <label for="nome">Celular pessoal:</label>
                    <div class="row">
                        <div class="col-md-11 col-xs-10">
                            <div class="input-group">
                                <the-mask mask="(##) #####-####" class="form-control input-sm" id="cel_pessoal" data-error="Este campo é obrigatório." v-model="funcionario.celular_pessoal" required></the-mask>
                                <div class="input-group-addon">
                                    <i class="fa fa-mobile"></i>
                                </div>
                            </div>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                            <i class="fa fa-asterisk"></i>
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-3">
                    <label for="nome">Celular corporativo:</label>
                    <div class="row">
                        <div class="col-md-11 col-xs-10">
                            <div class="input-group">
                                <the-mask mask="(##) #####-####" class="form-control input-sm" id="cel_corporativo" v-model="funcionario.celular_corporativo"></the-mask>
                                <div class="input-group-addon">
                                    <i class="fa fa-mobile"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-3">
                    <label for="nome">Telefone comercial:</label>
                    <div class="row">
                        <div class="col-md-11 col-xs-10">
                            <div class="input-group">
                                <the-mask mask="(##) ####-####" class="form-control input-sm" id="tel_comercial" data-error="Este campo é obrigatório." v-model="funcionario.telefone_comercial" required></the-mask>
                                <div class="input-group-addon">
                                    <i class="fa fa-phone"></i>
                                </div>
                            </div>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                            <i class="fa fa-asterisk"></i>
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-3">
                    <label for="nome">Ramal:</label>
                    <div class="row">
                        <div class="col-md-11 col-xs-10">
                            <div class="input-group">
                                <the-mask mask="####" class="form-control input-sm" id="ramal" data-error="Este campo é obrigatório." v-model="funcionario.ramal" required></the-mask>
                                <div class="input-group-addon">
                                    <i class="fa fa-phone"></i>
                                </div>
                            </div>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="col-md-1 col-xs-2" style="padding-left: 0;">
                            <i class="fa fa-asterisk"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box-header with-border">
                <i class="fa fa-id-card-o"></i>
                <h3 class="box-title">Dados do funcionário</h3>
            </div>
            <div class="box-body row" style="padding-bottom: 0;">
                <div class="form-group col-md-3">
                    <label>Cargo:</label>
                    <div class="row">
                        <div class="col-md-11 col-xs-10">            
                            <select class="form-control input-sm" v-model="funcionario.cargo_id" >
                                <option value=""></option>                         
                                <option v-for="item in cargosObj" :value="item.id">{{item.nome}}</option>
                            </select>
                            <div class="help-block with-errors" ></div>
                        </div> 
                    </div>
                </div>

                <div class="form-group col-md-3">
                    <label>Gestor:</label>
                    <div class="row">
                        <div class="col-md-11 col-xs-10">            
                            <select class="form-control input-sm" v-model="funcionario.gestor_id">
                                <option value=""></option>                           
                                <option v-for="item in gestoresObj" :value="item.id">{{item.nome}}</option>
                            </select>
                            <div class="help-block with-errors" ></div>
                        </div> 
                    </div>
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
    export default {
        props:['gestores', 'cargos', 'can', 'funcionario'],
        data () {
            return {
                // Configuração do campo de data
                config: {
                    format: 'DD/MM/YYYY',
                    useCurrent: false,                    
                    showClose: true,
                    locale: 'pt-br',
                    ignoreReadonly: true
                },
                gestoresObj: {},
                cargosObj: {},
                canObj: {}
            }
        },
        methods:
        {
            salvar()
            {
                this.$emit('salvar', this.funcionario)
            }
        },
        computed:{
            isDisabled(){
                if( this.funcionario.nome != '' && 
                    this.funcionario.nome_completo != '' &&
                    this.funcionario.email != '' &&
                    this.funcionario.dt_nascimento != '' &&
                    (this.funcionario.celular_pessoal && this.funcionario.celular_pessoal != '' && this.funcionario.celular_pessoal.length == 11) &&
                    (this.funcionario.telefone_comercial && this.funcionario.telefone_comercial != '' && this.funcionario.telefone_comercial.length == 10) &&
                    (this.funcionario.ramal && this.funcionario.ramal != '' && this.funcionario.ramal.length == 4) ) {
                    return false;
                }
                return true; 
            }
        },
        mounted()
        {
            this.canObj = JSON.parse(this.can);
            this.gestoresObj = JSON.parse(this.gestores);
            this.cargosObj = JSON.parse(this.cargos);
        },
    };
</script>

<style>
    .input-group.date>input.form-control{
        height: 30px;
        padding: 5px 10px;
        font-size: 12px;
        line-height: 1.5;
    }
</style>
