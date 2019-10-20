<template>

    <modal :name="'modal-text'+modalId" transition="pop-out" :width="modalWidth" height="auto">

        <div class="modal-content" >
            <div class="modal-header">    
                <button type="button" class="close" @click="$modal.hide('modal-text'+modalId)">
                    <span aria-hidden="true">&times;</span>
                </button>
            
                <h4 class="modal-title" id="modal-title" v-if="titulo">{{ titulo }}</h4>
            </div>
            <div class="modal-body" v-if="texto">   
                <div class="row">
                    <div class="col-md-12">                        
                        <p v-html="texto"></p>                        
                    </div>
                </div>
            </div>
        </div>

    </modal>

</template>

<script>

    const MODAL_WIDTH = 600

    export default {
        props:['modalId', 'titulo', 'texto'],
        name: 'ModalText',
        data (){
            return {
                modalWidth: MODAL_WIDTH,
            }
        }, 
        created () {
          this.modalWidth = window.innerWidth < MODAL_WIDTH
            ? MODAL_WIDTH / 2
            : MODAL_WIDTH
        }
    };
</script>