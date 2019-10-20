<template> 
   
    <div class="box box-default " :class="filtroAberto == true ? '':'collapsed-box'">
        <form>         
            <div class="box-header with-border cursor-pointer" data-widget="collapse" @click="filtroOpen" >
              
                <i class="fa fa-search cursor-pointer fa-minus" style="display: none;"> </i>                        
                <i class="fa fa-search cursor-pointer"> </i>    
                <h3 class="box-title cursor-pointer" data-widget="collapse">Filtrar resultado</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa " :class="filtroAberto == true ? 'fa-chevron-up':'fa-chevron-down'"></i>
                    </button>
                </div>
            </div>
           
            <div class="box-body " :class="filtroAberto == true ? 'apareceFiltro':'escondeFiltro'" >
                <slot></slot>
            </div>

            <div class="box-footer " :class="filtroAberto == true ? 'apareceFiltro':'escondeFiltro'">

                <div class="pull-left">
                    <button type="submit" class="btn btn-primary" @click="pesquisar" >Pesquisar</button>
                    <button type="button" class="btn btn-default" @click="limpar" >Limpar</button>                            
                </div>
                <slot name="exportacao"></slot>
            </div>
        </form>
    </div>
</template>
<script>
    import { filtroBus } from './filtroDataTable.js';
    import carregandoStore from './carregandoStore.js';

    export default{
        props:['source', 'filtro', 'filtroPadrao', 'cookiePrefixo', 'url', 'semCookie'],
        data () {
            return {
                pages:[],
                filtroAberto:'',
                carregandoStore
            }
        },
        methods: {
            pesquisar (ev) {
                this.$cookie.set( this.cookiePrefixo+'filtro_aberto', true , 1 )
                this.filtro.pagina = this.filtroPadrao.pagina 
                this.setCookies()
                this.search()
                this.$emit('pesquisabotton')

            },
            limpar (ev) {
                this.$cookie.set( this.cookiePrefixo+'filtro_aberto', false , 1 )
                this.setValorPadrao()
                this.setCookies()
                this.search()
                this.$emit('limpar')
            },
            filtroOpen() {
                this.filtroAberto = !this.filtroAberto
            },
            setCookies(){
                for( var prop in this.filtro){
                    let valor = this.filtro[prop] == null ? '' : this.filtro[prop]
                    this.$cookie.set( this.cookiePrefixo+prop, valor , 1)
                }
            },
            verificaCoockies(){

                for( var prop in this.filtro){
                    
                    if ( typeof this.$cookie.get( this.cookiePrefixo+prop ) !== "undefined" && this.$cookie.get( this.cookiePrefixo+prop ) != this.filtroPadrao[prop] ){
                        if(prop != 'por_pagina' && prop != 'pagina' && prop != 'sort')
                        {
                            if ( this.getCookieFiltroAberto() ){
                            this.filtroAberto = true
                            }
                        }
                        this.getCookies()
                        this.$emit('getCookies', false)
                        return true;
                    }
                }
                this.limpar()
            },
            setValorPadrao(){
                for( var prop in this.filtro){
                    let s = typeof this.filtro[prop] 

                    if (s == "object")
                    {
                        if( new Date(this.filtroPadrao[prop]) != "Invalid Date" ){
                            this.filtro[prop] = this.filtroPadrao[prop]     
                        }
                        else
                        {
                            this.filtro[prop] = JSON.parse(this.filtroPadrao[prop])     
                        }
                    }
                    else if(s == "boolean")
                    {
                        this.filtro[prop] = this.filtroPadrao[prop]=="true" ? true:false
                    }
                    else
                    {
                        this.filtro[prop] = this.filtroPadrao[prop]     
                    }   
                
                }
            },
            getCookies(){
                for( var prop in this.filtro){
                    let s = typeof this.filtro[prop] 
                    
                    if (s == "boolean")
                    {
                        this.filtro[prop] = this.$cookie.get( this.cookiePrefixo+prop )=="true" ? true:false
                    }
                    else if (s == "object")
                    {
                        if( new Date(this.$cookie.get( this.cookiePrefixo+prop )) != "Invalid Date" )
                        {
                            this.filtro[prop] = this.$cookie.get( this.cookiePrefixo+prop )
                        }
                        else
                        {
                            this.filtro[prop] = JSON.parse(this.$cookie.get( this.cookiePrefixo+prop ))
                        }
                    }
                    else
                    {
                        this.filtro[prop] = this.$cookie.get( this.cookiePrefixo+prop )
                    }

                }
                this.search()
            },
            search()
            {   
                this.carregandoStore.carregando = true
                window.axios.post( this.url + 'search?page='+this.filtro.pagina ,this.filtro)
                    .then(response=>{
                        this.carregandoStore.carregando = false
                        this.$emit('pesquisar', response.data)
                    });
            },
            getCookieFiltroAberto(){
                return ( typeof this.$cookie.get( this.cookiePrefixo+'filtro_aberto' ) !== "undefined" && 
                            this.$cookie.get( this.cookiePrefixo+'filtro_aberto' ) == 'true' ) ? true : false
            }
        },  
        mounted()
        {
            this.verificaCoockies()
            filtroBus.$on('editTable', event => {
                this.search();
            });
        },
       
    };


</script>
<style>

    .escondeFiltro{
       display: none; 
    }
    .apareceFiltro{
       displ
       ay: block; 
    }

</style>