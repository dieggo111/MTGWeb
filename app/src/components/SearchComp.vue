<template>
    <div class="results-box">
        <!-- <header><h3>Search Results</h3></header> -->
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
                                    :text="getDisplayOption"
                                    class="m-md-2">
                                <b-dropdown-item
                                        @click="changeDisplayOption(option)"
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
                                    :text="getSortOption"
                                    class="m-md-2">
                                <b-dropdown-item
                                        @click="changeSortOption(option)"
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
            <div v-if="getDisplayOption == 'Row'">
                <!-- <b-table :items="searchResults" thead-class="hidden_header"> -->
                <b-table :fields="fields" :items="searchResults">
                </b-table>
            </div>
            <div v-else>
                <div class="results" v-for="result in searchResults" :key="result.card_id">
                    <CardComp :result="result" :displayOption="getDisplayOption"></CardComp>
                    <!-- <img class="result-img" :src="result.image_uri_normal" /> -->
                </div>
            </div>
        </div>
        <div v-else>Can't find card with the name <span>"{{this.$route.query.name}}"</span></div>
    </div>
</template>



<script>
import CardComp from './CardComp';

export default {
    name: "search",
    components: {
        CardComp
    },
    created() {
        this.searchCard();
    },
    data() {
        return{
            fields: ["name", "colors"],
            sortOptions: ["Name", "Rarity", "Color", "Manacost"],
            sortOptionDict: {
                "Name": true,
                "Rarity": false,
                "Color": false,
                "Manacost": false
            },
            displayOptions: ["Card", "Row", "Detail"],
            displayOptionDict: {
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
        getDisplayOption() {
            for (var key in this.displayOptionDict) {
                if (this.displayOptionDict[key] === true) {
                    return key;
                }
            }
            return "";
        },
        getSortOption() {
            for (var key in this.sortOptionDict) {
                if (this.sortOptionDict[key] === true) {
                    return key;
                }
            }
            return "";
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
        },
        changeDisplayOption(option) {
            for (var key in this.displayOptionDict) {
                if (key == option) {
                    this.displayOptionDict[key] = true;
                } else {
                    this.displayOptionDict[key] = false;
                }
            }
        },
        changeSortOption(option) {
            for (var key in this.sortOptionDict) {
                if (key == option) {
                    this.sortOptionDict[key] = true;
                } else {
                    this.sortOptionDict[key] = false;
                }
            }
        }

    }
}
</script>

<style lang="sass" scoped>
@import '../styles/_styles.sass'
</style>