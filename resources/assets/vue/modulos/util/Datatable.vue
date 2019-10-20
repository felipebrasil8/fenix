<template> 

    <div class="box box-default">
        <div class="table-responsive">  
            <table class="table table-striped table-hover" style="table-layout:fixed; min-width: 1000px;">
                <thead>
                    <tr>
                        
                        <th v-for="item in thead" :class="item.ordena?'pointer '+item.class : item.class" :width="item.width" @click="item.ordena?ordenacao($event, item.coluna ):''"> 
                            <span  class="naoSelecionavel" v-if="item.ordena" >
                                {{ item.text }}
<!--                            <span v-if="ordenacaoIcone( item.coluna ) == false" class='fa fa-sort'></span>
                                <span v-else-if="ordenacaoIcone( item.coluna ) == 'asc'" class='fa fa-caret-up'></span>
                                <span v-else class='fa fa-caret-down' ></span> -->
                          

                                <span style="vertical-align: super; display: inline-grid;">
                                    <i class="fa fa-caret-up " style="height:7px;" :class="ordenacaoIcone( item.coluna ) == 'asc'?'sortSelectd':'sortDisablede' "></i>
                                    <i class="fa fa-caret-down " style="height:7px;" :class="ordenacaoIcone( item.coluna ) == 'desc'?'sortSelectd':'sortDisablede'" ></i>
                                </span>
                               
                                
   

                            </span>
                            
                            <span :class="item.class" :width="item.width" v-else>
                                {{ item.text }}
                            </span>
                        </th>
                       
                        <th class="text-center pointer" width="120px" v-if="status" @click="ordenacao($event,'2')">
                            <span class="naoSelecionavel" >
                                Status
                                <!-- <span v-if="ordenacaoIcone('2') == false" class='fa fa-sort'></span>
                                <span v-else-if="ordenacaoIcone('2') == 'asc'" class='fa fa-caret-up'></span>
                                <span v-else class='fa fa-caret-down'></span> -->
                                
                                <span style="vertical-align: super; display: inline-grid;">
                                    <i class="fa fa-caret-up " style="height:7px;" :class="ordenacaoIcone('2') == 'asc'?'sortSelectd':'sortDisablede' "></i>
                                    <i class="fa fa-caret-down" style="height:7px;" :class="ordenacaoIcone('2') == 'desc'?'sortSelectd':'sortDisablede'" ></i>
                                </span>
                               

                            </span>
                        </th>   
                        <th class="text-center naoSelecionavel"  width="150px" v-if="acoes">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody>
                   <tr v-for="item in dados.data" class="datatebleVue">
                       
                        <slot name="tbody" :showConfirmeModal="showConfirmeModal" :item="item"></slot>
                       
                        <td class="text-center"  v-if="status">
                            <div v-if="canObj.status">
                                <a class="pointer" :title="item.ativo?'Ativo, clique para desativar.' : 'Inativo, clique para ativar.'" @click="showConfirmeModal('Confirma a alteração de status de '+item.nome, item.id )">
                                    <i class="fa " :class="item.ativo?'fa-check-square-o':'fa-square-o'"></i>
                                </a>
                            </div>
                            <div v-else>
                                <i class="fa " :class="item.ativo?'fa-check-square-o':'fa-square-o'"></i>
                            </div>
                        </td>
                        <td class="text-center" v-if="acoes">
                            <a :href="url+item.id+'/edit'" title="Editar" v-if="canObj.editar"><i class="fa fa-pencil-square-o"></i></a>
                            &nbsp;
                            <a :href="url+item.id+'/copy'" title="Copiar" v-if="canObj.copiar"><i class="fa fa-files-o"></i></a>
                            &nbsp;
                            <a :href="url+item.id" title="Visualizar"><i class="fa fa-file-text-o"></i></a>
                        </td>  
                    </tr>
                  
                  
                </tbody>
                
            </table>
        </div>
        <div id='rodape-da-tabela' v-if="dados.data && dados.data.length > 0" >
            <div class='total col-md-4'>
                Total: ({{ dados.from }} - {{ dados.to }} de {{ dados.total }})
            </div>

            <div class='lista-de-paginas col-md-4 text-center'>
                <nav>
                    <ul class="paginateDatatebleVue" style="margin: 6px 0;">
                        <li :class="{ disable: dados.current_page == 1 }">
                            <!-- <a href="#" @click="nextPrev($event, dados.current_page-1)"  aria-label="Previous"> -->
                            <!-- <span aria-hidden="true">&laquo</span> -->
                            <a href="#" @click="nextPrev($event, dados.current_page-1)"  aria-label="Previous">

                            <i class="fa fa-angle-double-left" aria-hidden="true"></i>
                           </a>
                        </li>
                   
                        <li  v-for="page in pages" track-by="$index" :class="{ active: dados.current_page == page }">
                            <span class="desabled" v-if="page == '...'" >{{page}}</span>
                            <span class="pointer" v-else @click="navigate($event, page)">{{page}}</span>
                        </li>
                        
                        <li :class="{ disable: dados.current_page == dados.last_page }">
                          <a href="#" @click="nextPrev($event, dados.current_page+1)" >
                          <!-- <a href="#" @click="nextPrev($event, dados.current_page+1)" aria-label="Next"> -->
                            <!-- <span aria-hidden="true">&raquo</span> -->
                            <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                           </a>
                        </li>
                      
                    </ul>
                </nav>
            </div>
           
            <div class='limite-de-items-por-pagina col-md-4 text-right'>
                <label>
                    Registro por página
                </label>
                <select v-model="dados.per_page" @change="setPerPage">
                    <option value='15'>15</option>
                    <option value='30'>30</option>
                    <option value='50'>50</option>
                    <option value='100'>100</option>
                </select>
            </div>
        </div> 
        <div id='rodape-da-tabela' v-else >
            <div class='total col-md-4'>
                 Nenhuma informação encontrada!!! 
            </div>
        </div> 

        <div class="overlay" v-if="carregandoStore.carregando">
            <i class="fa fa-refresh fa-spin"></i>
        </div>

        <vc-modal-confirme @confirma="setStatus" :erros="erros"></vc-modal-confirme>
    </div>

</template>
<script>
    import { filtroBus } from './filtroDataTable.js';
    import carregandoStore from './carregandoStore.js';

    export default{

        props:['dados', 'url', 'filtro', 'canObj', 'cookiePrefixo', 'thead', 'status', 'acoes'],

        data () {
            return {
                pages:[],
                configuracaoPadrao:{
                    por_pagina:15,
                    page:1
                },
                configuracao:{
                    por_pagina:15,
                    page:1  
                },
                erros:'',
                carregandoStore
            }
        },
        watch:{
            dados(){
                this.pages = this.generatePagesArray( this.dados.current_page, this.dados.total, this.dados.per_page, 5 )
                
            }
        },
        methods: {
          
            nextPrev (ev, page) {
                
                if ( page == 0 || page == this.dados.last_page+1){
                    return;
                }

                this.navigate(ev, page)
            },

            generatePagesArray: function(currentPage, collectionLength, rowsPerPage, paginationRange)
            {
                var pages = [];
                var totalPages = Math.ceil(collectionLength / rowsPerPage);
                var halfWay = Math.ceil(paginationRange / 2);
                var position;

                if (currentPage <= halfWay) {
                    position = 'start';
                } else if (totalPages - halfWay < currentPage) {
                    position = 'end';
                } else {
                    position = 'middle';
                }

                var ellipsesNeeded = paginationRange < totalPages;
                var i = 1;
                while (i <= totalPages && i <= paginationRange) {
                    var pageNumber = this.calculatePageNumber(i, currentPage, paginationRange, totalPages);
                    var openingEllipsesNeeded = (i === 2 && (position === 'middle' || position === 'end'));
                    var closingEllipsesNeeded = (i === paginationRange - 1 && (position === 'middle' || position === 'start'));
                    if (ellipsesNeeded && (openingEllipsesNeeded || closingEllipsesNeeded)) {
                        pages.push('...');
                    } else {
                        pages.push(pageNumber);
                    }
                    i ++;
                }
                return pages;
            },

            calculatePageNumber: function(i, currentPage, paginationRange, totalPages)
            {
                var halfWay = Math.ceil(paginationRange/2);
                if (i === paginationRange) {
                    return totalPages;
                } else if (i === 1) {
                    return i;
                } else if (paginationRange < totalPages) {
                    if (totalPages - halfWay < currentPage) {
                    return totalPages - paginationRange + i;
                } else if (halfWay < currentPage) {
                    return currentPage - halfWay + i;
                } else {
                    return i;
                }
                } else {
                    return i;
                }
            },
            setPerPage(){
                this.filtro.por_pagina = this.dados.per_page 
                this.filtro.pagina = 1
                this.$cookie.set( this.cookiePrefixo+'por_pagina' , this.dados.per_page , 1)
                this.$cookie.set( this.cookiePrefixo+'pagina' , 1 , 1)
                filtroBus.$emit('editTable');
            },
            navigate (ev, page) {
                this.filtro.pagina = page 
                this.$cookie.set( this.cookiePrefixo+'pagina' , page , 1 )
                filtroBus.$emit('editTable');
            },
            showConfirmeModal(titulo, id){
                this.$modal.show('modal-confirm', {titulo: titulo, id: id})
            },
            ordenacao(ev, collun){
                ev.preventDefault()
                
                if ( ev.shiftKey == false)
                {
                    if( this.filtro.sort.indexOf(collun + ' asc') != -1 || this.filtro.sort.indexOf(collun + ' desc') != -1 )
                    {
                        var ordens = this.filtro.sort.split(',');

                        for (var i = 0; i < ordens.length; i++)
                        {
                            var ordem = ordens[i].split(' ');

                            if( ordem[0] == collun )
                            {
                                if( ordem[1] == 'asc' )
                                {
                                    this.sort = collun+' '+'desc';
                                }
                                else
                                {
                                    this.sort = collun+' '+'asc';
                                }
                            }
                        }
                    }
                    else
                    {
                        this.sort = collun+' '+'asc';
                    }

                }
                else
                {
                    if( this.filtro.sort.indexOf(collun + ' asc') != -1 || this.filtro.sort.indexOf(collun + ' desc') != -1 )
                    {
                        var ordens = this.filtro.sort.split(',');

                        for (var i = 0; i < ordens.length; i++)
                        {
                            if( ordens[i].indexOf(collun + ' asc') != -1)
                            {
                                ordens[i] = collun+' '+'desc';
                            }
                            else if( ordens[i].indexOf(collun + ' desc') != -1 )
                            {
                                ordens[i] = collun+' '+'asc';
                            }
                        }
                        this.sort = ordens.join(',');
                    }
                    else
                    {
                        this.sort = this.sort+','+collun+' '+'asc'
                    }                    
                }

                this.filtro.sort = this.sort
                this.$cookie.set( this.cookiePrefixo+'sort' , this.filtro.sort , 1)
                filtroBus.$emit('editTable');
            },
            ordenacaoIcone( collun )
            {
                if( this.filtro.sort.indexOf(collun) != -1 )
                {
                    var ordens = this.filtro.sort.split(',');

                    for (var i = 0; i < ordens.length; i++)
                    {
                        var ordem = ordens[i].split(' ');

                        if( ordem[0] == collun )
                        {
                            return ordem[1];
                        }
                    }
                }

                return false;
            },
            setStatus(id)
            {
                if( id != '' )
                {
                    window.axios.delete( this.url+id )
                    .then(response=>{                        
                        for (var x = 0; x < this.dados.data.length; x++){
                            if (this.dados.data[x].id == id){
                                this.dados.data[x].ativo = !this.dados.data[x].ativo;
                                this.id = ''
                                this.$modal.hide('modal-confirm')
                                return true;
                            }
                        }
                    })
                    .catch(error => {
                        this.erros = error.response.data.errors;
                        return 0;
                    });
                }
            },
        },
        mounted(){
            this.sort = this.filtro.sort

        }
        
        

    };

</script>

<style>
.datatebleVue > td
{
    padding-bottom: 3px !important;
}

.paginateDatatebleVue
{
    display: inline-block;
    padding-left: 0;
}

.paginateDatatebleVue > li
{
    display: inline;
}

.paginateDatatebleVue>li>a, 
.paginateDatatebleVue>li>span 
{
    position: relative;
    float: left;
    padding: 4px 6px;
    line-height: 1.42857143;
    border-radius: 4px;
}

.paginateDatatebleVue>.active>a, 
.paginateDatatebleVue>.active>a:focus, 
.paginateDatatebleVue>.active>a:hover, 
.paginateDatatebleVue>.active>span, 
.paginateDatatebleVue>.active>span:focus, 
.paginateDatatebleVue>.active>span:hover 
{
    z-index: 3;
    background-color: #F9F9F9;
    border: 1px solid #979797;
}

.paginateDatatebleVue>li>.desabled,
.paginateDatatebleVue>li>.desabled:hover 
{
    color: #898989 !important;
    
} 

.paginateDatatebleVue>li>a>i
{
    color: #333;

}

.paginateDatatebleVue>li>a:hover, 
.paginateDatatebleVue>li>a>i:hover, 
.paginateDatatebleVue>li>span:hover 
{
    color: #367fa9;
}
    
.sortSelectd
{
    color: #3c8dbc;
    text-shadow: 0px 0px 3px #65b7e7;
}

.sortDisablede
{
    color: #000;
    
}

</style>