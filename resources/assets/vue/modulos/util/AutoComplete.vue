<template>

    <div class="autocomplete">
        <input v-model="keyword" class="form-control input-sm"  placeholder="Digite o nome do funcionário..." style="text-transform: uppercase;" 
                @input="onInput($event.target.value)"
                @keydown.down="moveDown">
        <div v-if="fOptions">
            <ul v-show="isOpen" class="options-list" style="max-height: 150px;" v-if="fOptions.length > 0">
                <li v-for="(option, index) in fOptions" 
                :class="{'highlighted': index === highlightedPosition}" 
                @mouseenter="highlightedPosition = index" 
                @mousedown="select">
                    
                    <section>
                        <div class="user-panel">
                            <div class="pull-left image">
                                <img :src="option.avatar" class="img-circle" alt="option.nome">
                            </div>
                            <div class="center-block info">
                                <p style="font-size: 14px; color: #333">{{ option.nome }}</p>
                            </div>
                        </div>
                    </section>

                </li>
            </ul>
            
        </div>
    </div>

</template>


<script>
    
    export default {
        
        props: {
            options: {
                type: Array,
                required: true,

            },            
            limpaInput: {
                type: String
            }
        },
        name: 'AutoComplete',   
        data() {
            return {
                isOpen: false,
                highlightedPosition: 0,
                keyword: '',                
            }
        },
        computed: {

          fOptions() {

            if( this.keyword.length > 0 && this.keyword != ''){

                var nome = this.retiraAcento(this.keyword)                
                const re = new RegExp(nome, 'i')                
                var _this = this;
                return this.options.filter(function( o ){                    
                    return _this.retiraAcento(o.nome).match(re)
                });
            }

          }
        },        
        methods: {
            onInput(value) {
                this.$emit('input', value)
                this.highlightedPosition = 0
                this.isOpen = true;

            },
            moveDown() {

                return false;
                
                if (!this.isOpen) {
                    return
                }
                this.highlightedPosition = (this.highlightedPosition + 1) % this.fOptions.length
            },
            moveUp() {

                return false;
                
                if (!this.isOpen) {
                    return
                }
                this.highlightedPosition = this.highlightedPosition - 1 < 0 ? this.fOptions.length - 1 : this.highlightedPosition - 1
            },
            select() {
                
                const selectedOption = this.fOptions[this.highlightedPosition]
                this.$emit('select', selectedOption)
                this.isOpen = false
                this.keyword = selectedOption.nome
            },            
            retiraAcento(palavra) {

                var com_acento = 'áàãâäéèêëíìîïóòõôöúùûüçÁÀÃÂÄÉÈÊËÍÌÎÏÓÒÕÖÔÚÙÛÜÇ';
                var sem_acento = 'aaaaaeeeeiiiiooooouuuucAAAAAEEEEIIIIOOOOOUUUUC';
                var nova='';

                for(var i=0;i<palavra.length;i++) {
                    if (com_acento.search(palavra.substr(i,1))>=0) {
                        nova+=sem_acento.substr(com_acento.search(palavra.substr(i,1)),1);
                    } else {
                        nova+=palavra.substr(i,1);
                    }
                }

                return nova;
            }

        },
        watch: {
            'limpaInput': function(newVal, oldVal) {

                if( newVal == 'limpa' ){
                    this.keyword = ''  ;
                    this.$emit('limpaInputs', true)                    
                }
            }

        }
    };

</script>

<style>
    

    .autocomplete {
      position: relative;
      height: 0px;
      z-index: 1000;
    }

    .autocomplete ul {
      list-style-type: none;
      padding: 0;
    }

    .autocomplete li {
      display: inline-block;
      margin: 0 10px;      
    }

    .autocomplete ul.options-list {
      display: flex;
      flex-direction: column;
      margin-top: -12px;
      border: 1px solid #dbdbdb;
      border-radius: 0 0 3px 3px;
      position: absolute;
      width: 100%;
      overflow-y: scroll;      
      max-height: 200px;
      top: 42px;
    }

    .autocomplete ul.options-list li {
      width: 100%;
      flex-wrap: wrap;
      background: white;
      margin: 0;
      border-bottom: 1px solid #eee;
      color: #363636;
      padding: 7px;
      cursor: pointer;
    }

    .autocomplete ul.options-list li.highlighted {
      background: #f8f8f8
    }

    .autocomplete p {
      margin: 10px 0 10px 0;
    }

</style>
