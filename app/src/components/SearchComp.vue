<template>
    <div class="results-box">
        <!-- <header><h3>Search Results</h3></header> -->
        <body>
            <b-container class="search-toolbar">
                <b-row>
                    <b-col>
                        <div class="search-options">
                            <b-button-group size="sm" class="mr-1">
                                <b-container class="m-md-2">
                                    Display option:
                                </b-container>
                                <b-dropdown
                                        id="display-opt-dropdown"
                                        :text="getSelectedDisplayOption"
                                        class="m-md-2">
                                    <b-dropdown-item
                                            :v-bind="displayOptions"
                                            v-for="option in displayOptions"
                                            :key="option">
                                        {{ option }}
                                    </b-dropdown-item>
                                </b-dropdown>
                                <b-container class="m-md-2">
                                    Sort by:
                                </b-container>
                                <b-dropdown
                                        id="display-opt-dropdown"
                                        :text="getSelectedSortOption"
                                        class="m-md-2">
                                    <b-dropdown-item
                                            :v-bind="sortOptions"
                                            v-for="option in sortOptions"
                                            :key="option">
                                        {{ option }}
                                    </b-dropdown-item>
                                </b-dropdown>
                            </b-button-group>
                        </div>
                    </b-col>
                    <b-col>
                        <div class="search-navigation">
                            <b-pagination
                                    class="m-md-2"
                                    v-model="currentPage"
                                    :total-rows="rows"
                                    :per-page="perPage"
                                    first-number
                                    last-number>
                            </b-pagination>
                        </div>
                    </b-col>
                </b-row>
            </b-container>

            <div v-if="searchResults.length >= 1">
                <div class="results" v-for="result in searchResults" :key="result.card_id">
                    <img class="result-img" :src="result.image_uri_normal" />
                </div>
            </div>
            <div v-else>No result...</div>
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
            sortOptions: ["Name", "Rarity", "Color", "Manacost"],
            selectedSortOption: {
                "Name": true,
                "Rarity": false,
                "Color": false,
                "Manacost": false
            },
            displayOptions: ["Card", "Row", "Detail"],
            selectedDisplayOption: {
                "Card": true,
                "Row": false,
                "Detail": false
            },
            searchResults: [],
            rows: 10,
            perPage: 10,
            currentPage: 1
        };
    },
    computed: {
        getSelectedDisplayOption() {
            var selected;
            this.displayOptions.forEach(option => {
                if (this.selectedDisplayOption[option] === true) {
                    selected = option;
                }
            })
            return selected;
        },
        getSelectedSortOption() {
            var selected;
            this.sortOptions.forEach(option => {
                if (this.selectedSortOption[option] === true) {
                    selected = option;
                }
            })
            return selected;
        }
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