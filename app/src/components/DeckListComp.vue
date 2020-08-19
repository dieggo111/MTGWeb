<template>
    <b-container class="deck-list" :style="{width: getBoxWidth(), height: getBoxHeight()}">
        <b-row>
            <b-col>
                <b-container class="card-list">
                    <h3>Cards</h3>
                    <b-col
                        v-for="(card, index) in getSortedCardList"
                        :key="card.id">
                        <b-row>
                            <img
                                class="deck-list-card-img"
                                :src="card.image_uri_normal"
                                :style="{top: getImagePosition(index)}">
                        </b-row>
                    </b-col>
                </b-container>
            </b-col>
            <b-col>
                <b-container class="land-list">
                    <h3>Lands</h3>
                    <b-col
                        v-for="(card, index) in getSortedLandList" :key="card.id">
                        <b-row>
                            <img
                                class="deck-list-card-img"
                                :src="card.image_uri_normal"
                                :style="{top: getImagePosition(index)}">
                        </b-row>
                    </b-col>
                </b-container>
            </b-col>
        </b-row>
    </b-container>
</template>



<script>
import {sortArrayByProp} from '../utils';

export default {
    name: 'Decklist',
    props: ["deckList"],
    data() {
        return{
        }
    },
    computed: {
        getSortedLandList() {
            return sortArrayByProp("name", this.deckList['lands']);
        },
        getSortedCardList() {
            return sortArrayByProp("name", this.deckList['cards']);
        }
    },
    methods: {
        getImagePosition(idx) {
            var pos = idx*40;
            return pos + "px"
        },
        getBoxWidth() {
            var width = 700;
            return width + "px";

        },
        getBoxHeight() {
            var len = 0;
            if (this.deckList["cards"].length > this.deckList["lands"].length) {
                len = this.deckList["cards"].length;
            } else {
                len = this.deckList["lands"].length
            }
            var height = 900 + 20 * (len - 1) + (len - 1) * 10;
            return height + "px";
        }
    }
}
</script>

<style lang="sass" scoped>
@import '../styles/_styles.sass'
</style>