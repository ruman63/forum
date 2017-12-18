<template>
    <ul class="pagination pagination-primary" v-if="showPagination">
        <li class="page-item" v-show="prevUrl">
            <a class="page-link" href="#" rel="prev" @click.prevent="page--"> &laquo; Prev</a>
        </li>
        <li class="page-item" v-show="nextUrl">
            <a class="page-link" href="#" rel="next" @click.prevent="page++">Next &raquo; </a>
        </li>
    </ul>
</template>

<script>
export default {
    props: ['dataSet'],
    data() {
        return {
            page: 1,
            nextUrl: false,
            prevUrl: false
        }
    },
    watch: {
        dataSet() {
            this.page = this.dataSet.current_page;
            this.nextUrl = this.dataSet.next_page_url;
            this.prevUrl = this.dataSet.prev_page_url;
            
        },
        page() {
            this.broadcast().updateUrl();
        }
    },
    methods:{
        showPagination() {
            return !! this.nextUrl || !! this.prevUrl;
        },
        broadcast() {
            if(this.page > this.last)
                this.page = this.last;
            else if( this.page < 1)
                this.page = 1;
            return this.$emit('changed', this.page);
        },
        updateUrl() {
            history.replaceState(null, null, `?page=${this.page}`);
        }
        
    }

}
</script>
