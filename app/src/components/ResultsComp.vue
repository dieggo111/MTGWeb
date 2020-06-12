<template>
    <div class="results-box">
        <header><h3>Search Results</h3></header>
        <body>

            <div v-if="searchResults.length >= 1">
                <div class="results" v-for="result in searchResults" :key="result.card_id">
                    <img class="result-img" :src="result.image_uri_normal" />
                </div>
            </div>
            <div v-else>Nothing tho search for...</div>
        </body>
    </div>
</template>



<script>

export default {
    props: {
        searchResults: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return{
            results: this.searchResults
        };
    },
    name: 'results',
    methods: {
        checkResults() {
            console.log(this.searchResults);
        }
    }
}
</script>

<style lang="sass" scoped>
@import '../styles/_styles.sass'
</style>