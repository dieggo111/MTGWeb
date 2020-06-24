<template>
    <div class="results-box">
        <ToolbarComp    :displayOptions="displayOptions"
                        :displayOptionDict="displayOptionDict"
                        :sortOptions="sortOptions"
                        :sortOptionDict="sortOptionDict"
                        :perPage="perPage"
                        :paginationRows="getSearchResultsLength"
                        :items="searchResults"
                        @change="setCurrentPage"/>

        <div v-if="searchResults.length >= 1">
            <b-container v-if="getDisplayOption == 'Row'">
                <b-table
                    :fields="fields"
                    :items="searchResults.slice(
                        perPage*(currentPage-1),
                        perPage*currentPage)">
                    <template v-slot:cell(manacost)="{ item }">
                        <div v-html="getManaSymbols(item.manacost)"></div>
                    </template>
                </b-table>
            </b-container>
            <b-container v-else-if="getDisplayOption == 'Card'">
                <b-row
                    v-for="i in Math.ceil(perPage / perRow)"
                    :key="i">
                    <b-col
                        v-for="(result, index) in searchResults.slice(
                                perPage*(currentPage-1),
                                perPage*currentPage).slice((i - 1) * perRow, i * perRow)"
                        :key="result.card_id">
                        <div :class="'card-row-item-' + index">
                            <CardComp
                                :result="result"
                                :displayOption="getDisplayOption" />
                        </div>
                    </b-col>
                </b-row>
            </b-container>
            <b-container v-else>
                <span   v-for="result in searchResults.slice(
                            perPage*(currentPage-1),
                            perPage*currentPage)"
                        :key="result.card_id">
                    <CardComp
                            :result="result"
                            :displayOption="getDisplayOption" />
                </span>
            </b-container>
        </div>
        <div v-else>Can't find card with the name <span>"{{this.$route.query.name}}"</span></div>
    </div>
</template>



<script>
import CardComp from './CardComp';
import ToolbarComp from './ToolbarComp';

export default {
    name: "search",
    components: {
        CardComp,
        ToolbarComp
    },
    created() {
        this.searchCard();
    },
    data() {
        return{
            i: 0,
            fields: [
                "name",
                "manacost",
                "rarity",
                "convertedmanacost",
                "types"
            ],
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
            perPage: 10,
            perRow: 3,
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
        getSearchResultsLength() {
            return this.searchResults.length
      }
    },
    methods: {
        searchCard() {
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
        setCurrentPage(page) {
            this.currentPage = page;
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