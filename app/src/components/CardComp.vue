<template>
    <div class="card-box">
        <div class="image-box" v-if="displayOption == 'Card'">
            <div @click="clickImage(result)">
                <img class="card-img" :src="result.image_uri_normal" />
            </div>
        </div>
        <b-container class="detail-box" v-if="displayOption == 'Detail'">
            <b-row>
                <b-col>
                    <div @click="clickImage(result)">
                        <img class="detail-card-img" :src="result.image_uri_normal" />
                    </div>
                </b-col>
                <b-col>
                    <b-container
                        class="detail-infos"
                        v-for="key in detailsOrder"
                        :key="key">
                        <b-row>
                            <b-col>{{ cardDetails[key] }}</b-col>
                            <b-col>{{ result[key] }}</b-col>
                        </b-row>
                            <!-- <b-col>{{key}}</b-col>
                            <b-col>{{value}}</b-col> -->
                            <!-- <hr> -->
                    </b-container>
                </b-col>
            </b-row>
        </b-container>

    </div>
</template>



<script>

export default {
    name: "card",
    props: {
        displayOption: {
            type: String,
            required: true
        },
        result: {
            type: Object,
            required: true
        }
    },
    created() {
    },
    data() {
        return{
            cardDetails: {
                "name": "Name",
                "colors": "Colors",
                "manacost": "Manacost",
                "rarity": "Rarity",
                "setname": "Set",
                "text": "Text",
                "type": "Type"
            },
            detailsOrder: [
                "name",
                "colors",
                "manacost",
                "rarity",
                "setname",
                "text",
                "type"
            ]
        };
    },
    filters: {
    },
    computed: {

    },
    methods: {
        checkKey(key) {
            if (Object.keys(this.cardDetails).includes(key)) {
                return true;
            }
            return false;
        },
        adjustName(key) {
            console.log(key)
            console.log(this.cardDetails[key])
            return key;
        },
        clickImage(card) {
            this.$emit("cardSelected", card)
        }
    }
}
</script>

<style lang="sass" scoped>
@import '../styles/_styles.sass'
</style>