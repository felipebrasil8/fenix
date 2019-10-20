<template> 
    
    <nav>
    
        <ul class="pagination">
        
            <li :class="{ disable: source.current_page == 1 }">
              <a href="#" @click="nextPrev($event, source.current_page-1)"  aria-label="Previous">
                <span aria-hidden="true">&laquo</span>
               </a>
            </li>
       
            <li  v-for="page in pages" track-by="$index" :class="{ active: source.current_page == page }">
                <span v-if="page == '...'" >{{page}}</span>
                <span class="pointer" v-else @click="navigate($event, page)" >{{page}}</span>
            </li>
            
            <li :class="{ disable: source.current_page == source.last_page }">
              <a href="#" @click="nextPrev($event, source.current_page+1)" aria-label="Next">
                <span aria-hidden="true">&raquo</span>
               </a>
            </li>
          
        </ul>
    </nav>



</template>
<script>

    export default{

        props:['source'],

        data () {
            return {
                pages:[],
            }
        },
        watch:{

            source () {
                let s = this.source
                this.pages = this.generatePagesArray( s.current_page, s.total, s.per_page, 6 )
            }
        
        },
        methods: {
            navigate (ev, page) {
                ev.preventDefault()
                this.$emit('navigate', page)
      
            },

            nextPrev (ev, page) {
                
                if ( page == 0 || page == this.source.last_page+1){
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
            }

        },
        mounted(){

            let s = this.source
            this.pages = this.generatePagesArray( s.current_page, s.total, s.per_page, 6 )
        
        }
        
        

    }


</script>

<style>
    

</style>