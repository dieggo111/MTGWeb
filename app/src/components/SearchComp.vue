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
    name: "search",
    // props: {
    //     searchResults: {
    //         type: Array,
    //         default: () => []
    //     }
    // },
    created() {
        this.searchCard();
    },
    data() {
        return{
            searchResults: []
        };
    },
    methods: {
        searchCard() {
            fetch('http://localhost:8000/cards?name='.concat(this.$route.query.name))
            .then(res => res.json())
            .then(res => {
                console.log(res);
                this.searchResults = res;
                }
            )
            .catch(error => {
                console.log(error);
            })
        },
        checkResults() {
            console.log(this.searchResults);
        }
    }
}
</script>

<style lang="sass" scoped>
@import '../styles/_styles.sass'
</style>