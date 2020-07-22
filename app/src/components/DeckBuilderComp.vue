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
                        <b-table
                            class="deck-table"
                            :items="deckList['cards'].concat(deckList['lands'])"
                            :fields="fields">
                            <template v-slot:cell(delete)>
                                <b-button
                                    class="deck-table-delete-btn"
                                    @click="addBasicLand()"
                                    variant="outline-primary">
                                    +
                                </b-button>
                                <!-- <b-button
                                    @click="addBasicLand()"
                                    variant="outline-danger">
                                    -
                                </b-button> -->
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
            fields: ["name", "manacost", "delete"]
        }
    },
    created() {
        this.loadDeckList()
    },
    methods: {
        loadDeckList() {
            this.rawDeckList = JSON.parse(localStorage.getItem("deckList"));
            this.rawDeckList["cards"].forEach(id => {
                fetch('http://localhost:8000/cards?card_id=' + id)
                .then(res => res.json())
                .then(res => {
                    // console.log(res);
                    this.deckList["cards"].push(res[0]);
                    }
                )
                .catch(error => {
                    console.log(error);
                })
            });
            this.rawDeckList["lands"].forEach(id => {
                fetch('http://localhost:8000/cards?card_id=' + id)
                .then(res => res.json())
                .then(res => {
                    // console.log(res);
                    this.deckList["lands"].push(res[0]);
                    }
                )
                .catch(error => {
                    console.log(error);
                })
            });
        },
        addBasicLand(color) {
            console.log("add")
            this.deckList["lands"].push(this.basicMana[color]);
            var deckList = JSON.parse(localStorage.getItem("deckList"));
            deckList["lands"].push(this.basicMana[color]["card_id"]);
            localStorage.setItem("deckList", JSON.stringify(deckList))
        }
    }
}
</script>

<style lang="sass" scoped>
@import '../styles/_styles.sass'
</style>