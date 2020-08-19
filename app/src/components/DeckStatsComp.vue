<template>
    <div class="deck-stats">
        <b-container v-if="deckList">
            <h3>Deck Stats</h3>
            <b-row>
                <b-col>Card Count</b-col>
                <b-col v-if="getCardCount < 60">
                    <span class="invalid-card-count">
                        {{getCardCount + "/60"}}
                    </span>
                </b-col>
                <b-col v-if="getCardCount >= 60">
                    <span class="valid-card-count">
                        {{getCardCount + "/60"}}
                    </span>
                </b-col>
            </b-row>
            <b-row>
                <b-col>Color Stats</b-col>
                <b-col>{{getColorStats}}</b-col>
            </b-row>
            <b-row>
                <b-col>Type Stats</b-col>
                <b-col>{{getTypeStats}}</b-col>
            </b-row>
            <b-row>
                <b-container>
                    <b-row>
                        <b-col>Land Count</b-col>
                        <b-col>{{getLandCount}}</b-col>
                    </b-row>
                </b-container>
            </b-row>
                <!-- <b-col>{{key}}</b-col>
                <b-col>{{value}}</b-col> -->
                <!-- <hr> -->
        </b-container>
    </div>
</template>



<script>

export default {
    name: 'Deckstats',
    props: ["deckList"],
    data() {
        return{
            colors: ["W", "R", "B", "G", "U"],
            types: [
                "Artifact",
                "Artifact,Creature",
                "Creature",
                "Enchantment",
                "Enchantment,Creature",
                "Instant",
                "Land",
                "Planeswalker",
                "Sorcery"
            ]
        }
    },
    computed: {
        getCardCount() {
            var totalCards =
                this.deckList["lands"].length + this.deckList["cards"].length
            return totalCards
        },
        getColorStats() {
            var colorStats = {
                "W": 0,
                "B": 0,
                "U": 0,
                "G": 0,
                "R": 0
            };
            colorStats = this.countParam(this.colors, "colors", colorStats)
            var total = Object.values(colorStats).reduce((a, b) => a + b, 0);
            var colorRatios = Object();
            Object.keys(colorStats).forEach(color => {
                if (colorStats[color] != 0) {
                    var ratio = colorStats[color]/total + Number.EPSILON
                    colorRatios[color] = Math.round(ratio * 100) / 100
                }
            })
            return colorRatios;
        },
        getTypeStats() {
            var typeStats = {
                "Artifact": 0,
                "Artifact,Creature": 0,
                "Creature": 0,
                "Enchantment": 0,
                "Enchantment,Creature": 0,
                "Instant": 0,
                "Land": 0,
                "Planeswalker": 0,
                "Sorcery": 0
            };
            typeStats = this.countParam(this.types, "types", typeStats);
            var total = Object.values(typeStats).reduce((a, b) => a + b, 0);
            var typeRatios = Object();
            Object.keys(typeStats).forEach(type => {
                if (typeStats[type] != 0) {
                    var ratio = typeStats[type]/total + Number.EPSILON
                    typeRatios[type] = Math.round(ratio * 100) / 100
                }
            })
            return typeRatios;
        },
        getLandCount() {
            return this.deckList["lands"].length
        }
    },
    methods: {
        countParam(paramList, key, paramStats) {
            console.log(this.deckList["cards"])
            this.deckList["cards"].forEach(card => {
                paramList.forEach(param => {
                    if(card[key].includes(param)) {
                        paramStats[param] += 1;
                    }
                })
            });
            return paramStats;
        }
    }
}
</script>

<style lang="sass" scoped>
@import '../styles/_styles.sass'
</style>