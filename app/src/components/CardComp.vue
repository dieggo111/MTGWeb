<template>
    <div class="card-box">
        <div class="image-box" v-if="displayOption == 'Card'">
            <img class="card-img" :src="result.image_uri_normal" />

        </div>
        <b-container class="detail-box" v-if="displayOption == 'Detail'">
            <b-row>
                <b-col>
                    <img class="detail-card-img" :src="result.image_uri_normal" />
                </b-col>
                <b-col>
                    <b-container
                            class="detail-infos"
                            v-bind="result"
                            v-for="(value, key, index) in result"
                            :key="index">
                        <b-row v-if="checkKey(key)">
                            <!-- <b-col>{{this.cardDetails[key]}}</b-col> -->
                            <b-col>{{key}}</b-col>
                            <b-col>{{value}}</b-col>
                            <hr>
                        </b-row>
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
                "artist": "Artist",
                "colors": "Colors",
                "manacost": "Manacost",
                "rarity": "Rarity",
                "setname": "Set",
                "text": "Text",
                "type": "Type"
            }
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
            // return this.cardDetails[key];
            return key;
        }

    }
}
</script>

<style lang="sass" scoped>
@import '../styles/_styles.sass'
</style>