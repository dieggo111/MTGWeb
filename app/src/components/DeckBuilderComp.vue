<template>
    <div class="deck-builder">
        <b-container>
            <b-row>
                <b-col cols="8">
                    <DeckListComp :deckList="deckList" />
                </b-col>
                <b-col cols="4">
                    <b-row>
                        <DeckStatsComp
                            @addBasicLand="addBasicLand"
                            :deckList="deckList" />
                    </b-row>
                    <b-row>
                        <b-col>
                            <b-form-select
                                label="Add Basic Land"
                                v-model="selectedLandType"
                                :options="landTypes">
                                <template v-slot:first>
                                    <b-form-select-option
                                        :value="null"
                                        disabled>
                                        Add Basic Land
                                    </b-form-select-option>
                                </template>
                            </b-form-select>
                        </b-col>
                        <b-col>
                            <b-button
                                class="deck-stats-add-btn"
                                @click="addBasicLand()"
                                size="sm"
                                variant="primary">
                                <span class="btn-text">+</span>
                            </b-button>
                            <b-button
                                class="deck-stats-remove-btn"
                                @click="removeBasicLand()"
                                size="sm"
                                variant="danger">
                                <span class="btn-text">-</span>
                            </b-button>
                        </b-col>
                    </b-row>
                    <b-row>
                        <b-table
                            class="deck-table"
                            :items="getDeckTableItems"
                            :fields="fields">
                            <template v-slot:cell(amount)="{item}">
                                <b-button
                                    class="deck-table-add-btn"
                                    @click="addCard(item)"
                                    size="sm"
                                    variant="primary">
                                    <span class="btn-text">+</span>
                                </b-button>
                                <b-button
                                    class="deck-table-delete-btn"
                                    @click="deleteCard(item)"
                                    size="sm"
                                    variant="danger">
                                    <span class="btn-text">-</span>
                                </b-button>
                            </template>
                        </b-table>
                    </b-row>
                </b-col>
            </b-row>
        </b-container>
    </div>
</template>



<script>
import DeckListComp from './DeckListComp';
import DeckStatsComp from './DeckStatsComp';
import {backendAddress} from '../utils';

export default {
    name: 'Deckbuilder',
    components: {DeckListComp, DeckStatsComp},
    data() {
        return{
            rawDeckList: {},
            deckList: {
                "cards": [],
                "lands": [],
            },
            basicMana: {
                "Plains": {
                    "image_uri_normal": "https://img.scryfall.com/cards/normal/front/1/6/16ebbce9-fd10-4c14-b52d-cf82c0c1a58c.jpg?1591228746",
                    "card_id": 255
                },
                "Swamp": {
                    "image_uri_normal": "https://img.scryfall.com/cards/normal/front/6/c/6c8c3f0e-7af4-410b-a675-9ea84f51e812.jpg?1591228797",
                    "card_id": 328
                },
                "Island": {
                    "image_uri_normal": "https://img.scryfall.com/cards/normal/front/4/b/4b2ad5b3-7257-4521-8916-6b1cbfb89e27.jpg?1591228769",
                    "card_id": 166
                },
                "Forest": {
                    "image_uri_normal": "https://img.scryfall.com/cards/normal/front/9/c/9c348494-f60c-4bd1-9077-bff24f2e634b.jpg?1591228875",
                    "card_id": 112
                },
                "Mountain": {
                    "image_uri_normal": "https://img.scryfall.com/cards/normal/front/a/e/ae3d2fcd-11e0-4071-8c53-cb3315b7360a.jpg?1591228835",
                    "card_id": 217
                }
            },
            fields: ["name", "manacost", "amount"],
            selectedLandType: null,
            landTypes: ["Plains", "Mountain", "Swamp", "Forest", "Island"],
        }
    },
    created() {
        this.loadDeckList();
    },
    computed: {
        getDeckTableItems() {
            return this.deckList['cards'].concat(this.deckList['lands']);
        }
    },
    methods: {
        loadDeckList() {
            this.rawDeckList = JSON.parse(localStorage.getItem("deckList"));
            this.fetchCards('cards');
            this.fetchCards('lands');
        },
        fetchCards(type) {
            this.rawDeckList[type].forEach(id => {
                fetch(backendAddress.concat('cards?card_id=', id))
                .then(res => res.json())
                .then(res => {
                    this.deckList[type].push(res[0]);
                    }
                )
                .catch(error => {
                    console.log(error);
                })
            });
        },
        addBasicLand() {
            var land = this.selectedLandType;
            if (land !== null) {
                this.deckList["lands"].push(this.basicMana[land]);
                var deckList = JSON.parse(localStorage.getItem("deckList"));
                deckList["lands"].push(this.basicMana[land]["card_id"]);
                localStorage.setItem("deckList", JSON.stringify(deckList))
            }
        },
        removeBasicLand() {
            var idx = this.deckList["lands"].indexOf(this.basicMana[this.selectedLandType]);
            console.log(idx)
            if (idx != -1) {
                this.deckList["lands"].splice(idx, 1);
                var deckList = JSON.parse(localStorage.getItem("deckList"));
                deckList["lands"].splice(idx, 1);
                localStorage.setItem("deckList", JSON.stringify(deckList));
            }
        },
        addCard(item) {
            this.deckList["cards"].push(item);
            var deckList = JSON.parse(localStorage.getItem("deckList"));
            deckList["cards"].push(item["card_id"]);
            localStorage.setItem("deckList", JSON.stringify(deckList))
        },
        deleteCard(item) {
            this.deckList["cards"].pop(item);
            var deckList = JSON.parse(localStorage.getItem("deckList"));
            deckList["cards"].pop(item["card_id"]);
            localStorage.setItem("deckList", JSON.stringify(deckList));
        }
    }
}
</script>

<style lang="sass" scoped>
@import '../styles/_styles.sass'
</style>