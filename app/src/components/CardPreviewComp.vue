<template>
    <div class="card-preview">
        <img class="blank-image" :src="blankImage" />
        <div class="card-name">{{ getCardName }}</div>
        {{ cardParams['coloredMana'].split('') }}
        <div class="mana-cost">
            <span
                class="generic-mana-circle"
                v-bind:style="{right: getGenericManaXPosition}">
                <span
                    class="generic-mana-cost-value"
                    v-bind:style="{fontSize: getGenericCostSize}">
                    {{ cardParams["genericMana"] }}
                </span>
            </span>
            <span
                class="colored-mana-cost"
                v-for="(color, index) in cardParams['coloredMana'].split('')"
                v-bind:key="index">
                <img
                    class="mana-symbol"
                    :src="manaSymbols[color]"
                    :style="{right: getColoredManaXPosition(index)}" />
            </span>
        </div>
        <div class="card-type">{{ cardParams["type"] }}</div>
        <div class="card-rarity">
            <span
                class="card-rarity-circle"
                v-bind:style="{background: getRarityColor}">
            </span>
        </div>
    </div>
</template>



<script>

export default {
    name: 'cardpreview',
    props: ["cardParams"],
    data() {
        return{
            blankImage: require('../assets/blank_card.jpg'),
            manaSymbols: {
                "B": require('../assets/black.png'),
            },
            blackSymbol: require('../assets/black.png'),
            whiteSymbol: require('../assets/white.png'),
            greenSymbol: require('../assets/green.png'),
            redSymbol: require('../assets/red.png'),
            blueSymbol: require('../assets/blue.png'),
        };
    },
    computed: {
        getCardName() {
            var name = this.cardParams["name"]
            if (name != false) {
                return name[0].toUpperCase() + name.substring(1);
            }
            return name;
        },
        getGenericManaXPosition() {
            if (this.cardParams["coloredMana"] != "") {
                var x = 35 + this.cardParams["coloredMana"].length * 20;
                return x + "px";
            }
            return "34px"
        },
        getGenericCostSize() {
            if (this.cardParams["genericMana"] > 9) {
                return "10px"
            }
            return "14px"
        },
        getRarityColor() {
            if (this.cardParams["rarity"] == "common") {
                return "#000000";
            }
            if (this.cardParams["rarity"] == "uncommon") {
                return "#bababa";
            }
            if (this.cardParams["rarity"] == "rare") {
                return "#f7ff14";
            }
            if (this.cardParams["rarity"] == "mythic rare") {
                return "#fa7000";
            }
            return "";
        },

    },
    methods:{
        getColoredManaXPosition(index) {
            x = 35 + index * 20
            return x + "px"
        },
    }
}
</script>

<style lang="sass" scoped>
@import '../styles/_styles.sass'
</style>