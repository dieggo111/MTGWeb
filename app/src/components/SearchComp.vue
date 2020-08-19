<template>
    <div class="results-box">
        <ToolbarComp
            :displayOptions="displayOptions"
            :displayOptionDict="displayOptionDict"
            :sortOptions="sortOptions"
            :sortOptionDict="sortOptionDict"
            :perPage="perPage"
            :paginationRows="getSearchResultsLength"
            :items="searchResults"
            @change="setCurrentPage"/>

        <div v-if="searchResults.length >= 1">
            <b-container v-if="getDisplayOption == 'Row'">
                    {{perPage}} {{currentPage}}
                <b-table
                    :fields="fields"
                    :items="searchResults.slice(
                        perPage*(currentPage-1),
                        perPage*currentPage)"
                    hover
                    @row-clicked="spawnModal">
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
                                :displayOption="getDisplayOption"
                                @cardSelected="spawnModal" />
                        </div>
                    </b-col>
                </b-row>
            </b-container>
            <b-container v-else>
                <div
                    v-for="result in searchResults.slice(
                    perPage*(currentPage-1),
                    perPage*currentPage)"
                    :key="result.card_id">
                    <CardComp
                        :result="result"
                        :displayOption="getDisplayOption"
                        @cardSelected="spawnModal" />
                </div>
            </b-container>
        </div>
        <div v-else>Can't find card with the name <span>"{{this.$route.query.name}}"</span></div>
        <b-modal id="bv-modal-example" hide-footer>
            <h4>Add this card to your deck list?</h4>
            <b-button class="mt-3 mr-2" @click="addCard()">Yes</b-button>
            <b-button class="mt-3 ml-2" @click="$bvModal.hide('bv-modal-example')">No</b-button>
        </b-modal>
    </div>
</template>



<script>
import CardComp from './CardComp';
import ToolbarComp from './ToolbarComp';
import {backendAddress, countDuplicates} from '../utils'

export default {
    name: "Search",
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
            perPage: 24,
            perRow: 3,
            currentPage: 1,
            blackImage: '../assets/black_trans.png',
            whiteImage: '../assets/white_trans.png',
            greenImage: '../assets/green_trans.png',
            redImage: '../assets/red_trans.png',
            blueImage: '../assets/blue_trans.png',
            arrayFields: ["colors"],
            selectedCard: {}
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
            fetch(backendAddress.concat(
                'cards?',
                this.$route.fullPath.split("?")[1]
            ))
            .then(res => res.json())
            .then(res => {
                console.log(res);
                this.searchResults = res;
            })
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
        },
        spawnModal(card) {
            console.log(card)
            this.selectedCard = card;
            this.$bvModal.show('bv-modal-example');
        },
        addCard() {
            this.$bvModal.hide('bv-modal-example');
            var deckList = JSON.parse(localStorage.getItem("deckList"));
            console.log(deckList)
            if (this.selectedCard.types.includes("Land")) {
                deckList["lands"].push(this.selectedCard.card_id);

            } else {
                if (countDuplicates(this.selectedCard.card_id, deckList["cards"]) < 4) {
                    deckList["cards"].push(this.selectedCard.card_id);
                }
            }
            localStorage.setItem("deckList", JSON.stringify(deckList))
        }


    }
}
</script>

<style lang="sass" scoped>
@import '../styles/_styles.sass'
</style>