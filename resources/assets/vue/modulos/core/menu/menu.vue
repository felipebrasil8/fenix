<template>
    <div>
        <div id="buscaMenuEsconde" >
            <div style="margin: 10px 10px;">
                <multiselect 
                    v-model="buscaMenu" 
                    :options="optionsMenu"
                    :multiple="false" 
                    :hide-selected="true"
                    placeholder="Pesquisar..."
                    selectLabel=""
                    label="value"
                    :show-labels="false"
                    class="menu"
                    :searchable="true" 
                    :loading="isLoading" 
                    :internal-search="false"
                    :clear-on-select="false" 
                    :options-limit="100" 
                    :limit="3" 
                    :limit-text="limitText" 
                    :max-height="600" 
                    :show-no-results="false"
                    @search-change="menuPesquisa"
                    @select="redirecionaBusca"
                >
                    <template slot="option" slot-scope="props">
                        <div class="tamanho_option" data-toggle="tooltip" data-placement="right" :data-original-title="props.option.d2">
                            <img class="option__image" v-if="props.option.img" :src="'/configuracao/usuario/avatar-pequeno/'+props.option.img" data-toggle="tooltip" data-placement="top" :data-original-title="props.option.responsavel">
                            <div class="option__desc">
                                <span class="option__title" >{{ props.option.d1 }}</span>
                                <span class="option__small">{{ props.option.solicitante }} </span>
                            </div>
                        </div>
                    </template>
                    <template slot="noOptions">A lista está vazia.</template>
                </multiselect>
            </div>
        </div>
            
        <!--  Chamada inicial para construção dos menus recursivamente -->
        <ul class="sidebar-menu">
            <vc-menu-item v-for="(menu, index) in menuObj" :menuItem="menu" :key="index"></vc-menu-item>
        </ul>
    <!--/.sidebar-menu  -->
    </div>
</template>

<script>
export default {
    props:['menus'],
    data () {
        return {
            menuObj: {},
            buscaMenu: '',
            optionsMenu: [],
            optionsMenuPadrao: [
               /* {tipo: 'pagina', d1: 'página: acessar página do sistema', value: '', $isDisabled: true},
                {tipo: 'pesquisa', d1: '?pesquisa: realizar pesquisa na base de conhecimento', value: '?', $isDisabled: true },
                {tipo: 'funcionario', d1: '@nome do funcionário: visualizar contatos do funcionário', value: '@', $isDisabled: true },*/
                {tipo: 'ticket', d1: '#TK...: acessar o ticket correspondente', value: '#TK', $isDisabled: true},
            ],

            isLoading: false,
        }
    },
    methods:
    {
        limitText (count) {
            return `and ${count} other countries`
        },
        customLabel ({ tipo, desc }) {
          return tipo;
        },
        menuPesquisa(query)
        {
            if( query.length == 0 )
            {
                this.optionsMenu = this.optionsMenuPadrao;
            }
            else
            {
                query = query.trim();

                if(query[0] == '#')
                {
                    if( query.length < 3 )
                    {
                        this.optionsMenu = [{tipo: 'ticket', d1: '#TK...: acessar o ticket correspondente', value: '#TK', $isDisabled: true}];
                    }
                    else
                    {
                        this.isLoading = true
                        window.axios.post( '/core/buscaMenu', {'busca': query} )
                        .then(response=>{
                            if( response.data.length > 0 ){
                                this.optionsMenu = response.data;
                            }
                            else{
                                this.optionsMenu = [{d1: 'Não possui resultados.', $isDisabled: true}]   
                            }
                            this.isLoading = false
                        });
                    }
                }
                else
                {
                    this.optionsMenu = [{d1: 'Não possui resultados.', $isDisabled: true}]
                }
            }
        },
        redirecionaBusca( busca )
        {
            if( busca.value != '#TK' ){
                window.location.href = busca.url;
            }
            else{
                this.$el.querySelector('.multiselect.menu').focus();
                console.log(this.$el.querySelector('.multiselect__input'));
                this.buscaMenu = busca.value;
                this.menuPesquisa(busca.value);
            }
        },
    },
    mounted()
    {
        this.menuObj = JSON.parse(this.menus);
        this.optionsMenu = this.optionsMenuPadrao;
    },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style scoped>
    .menu >>> .multiselect__tag,.multiselect__option--highlight,.multiselect__option--highlight:after, .multiselect__tag-icon:focus, .multiselect__tag-icon:hover 
    {
        background: #2b8cb8 !important;
    
    }
    .menu >>> .multiselect__tag-icon:after {
        color: #ccc;
    }
    .menu >>> .multiselect__single 
    {
        font-size: 12px !important;
        font-family: "Source Sans Pro", "Helvetica Neue", Helvetica, Arial, sans-serif !important;
        text-transform: uppercase !important ;
        color: #a4a4a4 !important;
    
    }
    .menu >>> .multiselect__tags
    {
        border-radius: 5px !important; 
        box-shadow: none !important;
        border-color: #d2d6de !important;
    }
    .menu >>> .optionSelect
    {
        opacity: 0.5;
    }
    .menu >>> .multiselect__content-wrapper{
        width: auto !important;
        max-height: 500px !important;
    }
    .menu >>> .multiselect__option--disabled{
        background-color: #f9f9f9 !important;
        border-top: 1px solid #ddd;
        color: #35495e !important;
    }

    .menu >>> .option__desc, .menu >>> .option__image {
        display: inline-block;
        vertical-align: middle;
    }

    .menu >>> .tamanho_option {
        width: 450px !important;
    }

    .menu >>> .option__image{
        width: 40px;
        border-radius: 50%;
        /* vertical-align: middle; */
        background-color: #fff;
        float: left;
        border: 2px solid #d2d6de;
        margin: -5px 10px 0 0;
    }
    .menu >>> .option__small {
        margin-top: rem(10px);
        display: block;
    }
</style>