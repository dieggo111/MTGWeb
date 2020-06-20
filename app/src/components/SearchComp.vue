<template>
    <div class="results-box">
        <!-- <header><h3>Search Results</h3></header> -->
        <b-container class="search-toolbar">
            <b-row>
                <b-col cols="4">
                    <!-- <div class="search-options"> -->
                        <!-- <b-button-group size="sm" class="mr-1"> -->
                        <b-container class="mt-2">
                            Display option:
                        </b-container>
                <!-- </b-col>
                <b-col cols="2"> -->
                        <b-dropdown
                                id="display-opt-dropdown"
                                :text="getDisplayOption"
                                class="m-md-3">
                            <b-dropdown-item
                                    @click="changeDisplayOption(option)"
                                    :v-bind="displayOptions"
                                    v-for="option in displayOptions"
                                    :key="option">
                                {{ option }}
                            </b-dropdown-item>
                        </b-dropdown>
                </b-col>
                <b-col cols="4">
                        <b-container class="mt-2">
                            Sort by:
                        </b-container>
                <!-- </b-col>
                <b-col cols="2"> -->
                        <b-dropdown
                                id="sort-opt-dropdown"
                                :text="getSortOption"
                                class="m-md-3">
                            <b-dropdown-item
                                    @click="changeSortOption(option)"
                                    :v-bind="sortOptions"
                                    v-for="option in sortOptions"
                                    :key="option">
                                {{ option }}
                            </b-dropdown-item>
                        </b-dropdown>
                        <!-- </b-button-group> -->
                    <!-- </div> -->
                </b-col>
                <!-- <b-col cols="3"> -->
                <!-- </b-col> -->
                <b-col cols="4">
                    <div class="search-navigation">
                        <b-pagination
                                class="mt-5"
                                v-model="currentPage"
                                :total-rows="getPaginationRows"
                                :per-page="perPage"
                                :items="searchResults"
                                limit="3">
                        </b-pagination>
                    </div>
                </b-col>
            </b-row>
        </b-container>

        <div v-if="searchResults.length >= 1">
            <div v-if="getDisplayOption == 'Row'">
                <b-table :fields="fields" :items="searchResults.slice(perPage*(currentPage-1), perPage*currentPage)">
                    <template v-slot:cell(manacost)="{ item }">
                        <div v-html="getManaSymbols(item.manacost)"></div>
                    </template>
                </b-table>
            </div>
            <div v-else>
                <div class="results" v-for="result in searchResults.slice(10*(currentPage-1), 10*currentPage)" :key="result.card_id">
                    <CardComp :result="result" :displayOption="getDisplayOption"></CardComp>
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
            fields: ["name", "manacost", "rarity", "convertedmanacost", "types"],
            sortOptions: ["Name", "Rarity", "Conv. Manacost", "Types"],
            sortOptionDict: {
                "Name": true,
                "Rarity": false,
                "Conv. Manacost": false,
                "Types": false
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
            currentPage: 1,
            blackImage: '../assets/black_trans.png',
            whiteImage: '../assets/white_trans.png',
            greenImage: '../assets/green_trans.png',
            redImage: '../assets/red_trans.png',
            blueImage: '../assets/blue_trans.png'
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
        },
        getPaginationRows() {
            return this.searchResults.length
      }
    },
    methods: {
        searchCard() {
            console.log(this.$route.fullPath.split("?")[1])
            fetch('http://localhost:8000/cards?' + this.$route.fullPath.split("?")[1])
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
        },
        getManaSymbols(manacost) {
        //     console.log(manacost)
            var statement = String()
            statement = manacost
        //     if (manacost.includes("{R}")) {
        //         statement += '<img class="mana-thumbnail" src="../assets/red_trans.png" />'
        //     }
            // if (manacost.includes("{W}")) {
            //     return statement += '<img class="mana-thumbnail" :src="whiteImage" />'
            // }
            // console.log(statement)
        return statement
        }


    }
}
</script>

<style lang="sass" scoped>
@import '../styles/_styles.sass'
</style>