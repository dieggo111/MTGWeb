<template>
    <div class="card-preview">
        <img class="blank-image" :src="blankImage" />
        <div
            class="card-name"
            :style="{fontSize: getNameSize}">
            {{ getCardName }}
        </div>
        <div class="mana-cost">
            <div
                class="generic-mana-cost"
                v-if="showGenericManaCost">
                <span
                    class="generic-mana-circle"
                    v-bind:style="{right: getGenericManaXPosition}">
                    <span
                        class="generic-mana-cost-value"
                        :style="{fontSize: getGenericCostSize}">
                        {{ cardParams["genericMana"] }}
                    </span>
                </span>
            </div>
            <div class="colored-mana-cost">
                <span v-for="(color, index) in cardParams['coloredMana'].split('')"
                    :key="index"
                    :style="{right: getColoredManaXPosition(index)}">
                    <img
                        class="mana-symbol"
                        :src="manaSymbols[color]" />
                </span>
            </div>
        </div>
        <div class="card-type">{{ cardParams["type"] }}</div>
        <div class="card-rarity">
            <span
                class="card-rarity-symbol"
                :style="{background: getRarityColor}">
            </span>
        </div>
        <div
            class="card-text"
            :style="{fontSize: getTextSize, top: getTextYPosition}">
            {{ cardParams["text"] }}
        </div>
        <div class="card-pt" v-if="cardParams['type'] == 'Creature'">
            <span class=card-pt-text>
                {{ cardParams["power"] + "/" + cardParams["thoughness"] }}
            </span>
        </div>
        <div class="card-artwork">
            <img class="card-artwork-image" :src="cardParams['artwork']">
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
                "W": require('../assets/white.png'),
                "G": require('../assets/green.png'),
                "R": require('../assets/red.png'),
                "U": require('../assets/blue.png')
            },
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
        getNameSize() {
            if (this.cardParams["name"].length > 20
                && this.cardParams["coloredMana"].length > 4) {
                return "12px";
            }
            return "14px";
        },
        showGenericManaCost() {
            if (this.cardParams["coloredMana"] != ""
                && this.cardParams["genericMana"] == 0) {
                return false;
            }
            return true;
        },
        getGenericManaXPosition() {
            if (this.cardParams["coloredMana"] != "") {
                var x = 75 + this.cardParams["coloredMana"].length * 18;
                return x + "px";
            }
            return "75px";
        },
        getGenericCostSize() {
            if (this.cardParams["genericMana"] > 9) {
                return "10px"
            }
            return "14px";
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
        getTextSize() {
            if (this.cardParams["text"].length > 130) {
                return "12px"
            }
            return "14px"
        },
        getTextYPosition() {
            if (this.cardParams["text"].length > 116) {
                return "270px"
            }
            return "300px"
        }
    },
    methods:{
        getColoredManaXPosition(index) {
            var x = 200 + index * 20
            return x + "px"
        },
    }
}
</script>

<style lang="sass" scoped>
@import '../styles/_styles.sass'
</style>