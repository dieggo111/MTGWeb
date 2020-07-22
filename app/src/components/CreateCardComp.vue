<template>
    <div class="create-card-box">
        <b-container>
            <b-row>
                <b-col cols="8">
                    <table class="upload-card-table">
                        <tr>
                            <td class="first-col">Name</td>
                            <td>
                                <b-form-input
                                    class="card-name-input"
                                    v-model="cardName"
                                    :state="cardNameState"
                                    placeholder="Enter a card name" />
                                <b-form-invalid-feedback
                                    id="card-name-feedback">
                                    At least 3 letters and maximum of 20 letters
                                </b-form-invalid-feedback>
                            </td>
                        </tr>
                        <tr>
                            <td class="first-col">Colors</td>
                            <td><b-button
                                    class="color-btn"
                                    id="black-btn"
                                    variant="outline-secondary"
                                    :pressed.sync="selectedColors['B']">
                                <img class="rounded-image" :src="blackImage" />
                                </b-button>
                                <b-button
                                    class="color-btn"
                                    id="white-btn"
                                    variant="outline-secondary"
                                    :pressed.sync="selectedColors['W']">
                                    <img class="rounded-image" :src="whiteImage" />
                                </b-button>
                                <b-button
                                    class="color-btn"
                                    id="green-btn"
                                    variant="outline-secondary"
                                    :pressed.sync="selectedColors['G']">
                                    <img class="rounded-image" :src="greenImage" />
                                </b-button>
                                <b-button
                                    class="color-btn"
                                    id="red-btn"
                                    variant="outline-secondary"
                                    :pressed.sync="selectedColors['R']">
                                    <img class="rounded-image" :src="redImage" />
                                </b-button>
                                <b-button
                                    class="color-btn"
                                    id="blue-btn"
                                    variant="outline-secondary"
                                    :pressed.sync="selectedColors['U']">
                                    <img class="rounded-image" :src="blueImage" />
                                </b-button>
                            </td>
                        </tr>
                        <tr>
                            <td class="first-col">Mana Cost</td>
                            <td>
                                <table>
                                    <tr>
                                        <td>Generic</td>
                                        <td>
                                            <b-form-spinbutton
                                                class="card-cost-generic-input"
                                                v-model="genericCardCost"
                                                min="0"
                                                max="20" />
                                        </td>
                                    </tr>
                                    <tr v-if="selectedColors['B'] === true">
                                        <td>Black</td>
                                        <td>
                                            <b-form-spinbutton
                                                class="card-cost-mana-input"
                                                v-model="selectedManaCost['B']"
                                                min="0"
                                                max="20" />
                                        </td>
                                    </tr>
                                    <tr v-if="selectedColors['W'] === true">
                                        <td>White</td>
                                        <td>
                                            <b-form-spinbutton
                                                class="card-cost-mana-input"
                                                v-model="selectedManaCost['W']"
                                                min="0"
                                                max="20" />
                                        </td>
                                    </tr>
                                    <tr v-if="selectedColors['G'] === true">
                                        <td>Green</td>
                                        <td>
                                            <b-form-spinbutton
                                                class="card-cost-mana-input"
                                                v-model="selectedManaCost['G']"
                                                min="0"
                                                max="20" />
                                        </td>
                                    </tr>
                                    <tr v-if="selectedColors['R'] === true">
                                        <td>Red</td>
                                        <td>
                                            <b-form-spinbutton
                                                class="card-cost-mana-input"
                                                v-model="selectedManaCost['R']"
                                                min="0"
                                                max="20" />
                                        </td>
                                    </tr>
                                    <tr v-if="selectedColors['U'] === true">
                                        <td>Blue</td>
                                        <td>
                                            <b-form-spinbutton
                                                class="card-cost-mana-input"
                                                v-model="selectedManaCost['U']"
                                                min="0"
                                                max="20" />
                                        </td>
                                    </tr>

                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="first-col">Rarity</td>
                            <td>
                                <b-form-select
                                    v-model="cardRarity"
                                    :options="rarityOptions" />
                            </td>
                        </tr>
                        <tr>
                            <td class="first-col">Card Type</td>
                            <td>
                                <b-form-select
                                    v-model="cardType"
                                    :options="getTypes('types')" />
                            </td>
                        </tr>
                    </table>
                </b-col>
                <b-col cols="4">
                    <CardPreviewComp :cardParams="getCardParams" />
                </b-col>
            </b-row>
        </b-container>
    </div>
</template>



<script>
import CardPreviewComp from './CardPreviewComp';
import {convertSqlArrays} from '../utils';

export default {
    name: 'createcard',
    components: {CardPreviewComp},
    data() {
        return{
            cardName: "",
            blackImage: require('../assets/black_trans.png'),
            whiteImage: require('../assets/white_trans.png'),
            greenImage: require('../assets/green_trans.png'),
            redImage: require('../assets/red_trans.png'),
            blueImage: require('../assets/blue_trans.png'),
            cardColors: [],
            genericCardCost: 0,
            types: [],
            cardType: null,
            supertypes: [],
            rarityOptions: [
                { value: null, text: 'Select a rarity', disabled: true},
                { value: 'common', text: 'Common'},
                { value: 'uncommon', text: 'Uncommon'},
                { value: 'rare', text: 'Rare' },
                { value: 'mythic rare', text: 'Mythic Rare'}
            ],
            cardRarity: null,
            selectedColors: {
                "B": false,
                "W": false,
                "G": false,
                "R": false,
                "U": false
            },
            selectedManaCost: {
                "B": 0,
                "W": 0,
                "G": 0,
                "R": 0,
                "U": 0
            },
        };
    },
    created() {
        this.searchTypes();
        this.searchSuperTypes();
    },
    computed: {
        getCardParams() {
            return {
                "name": this.cardName,
                "genericMana": this.genericCardCost,
                "coloredMana": this.getManaCost(),
                "type": this.cardType,
                "rarity": this.cardRarity,
            }
        },
        cardNameState() {
            return (2 < this.cardName.length && this.cardName.length < 20) ? true : false
        }
    },
    methods:{
        searchTypes() {
            fetch('http://localhost:8000/types')
                .then(res => res.json())
                .then(res => {
                    this.types = res;
                    }
                )
                .catch(error => {
                    console.log(error);
                })
        },
        searchSuperTypes() {
            fetch('http://localhost:8000/supertypes')
                .then(res => res.json())
                .then(res => {
                    this.supertypes = res;
                    }
                )
                .catch(error => {
                    console.log(error);
                })
        },
        getTypes(arg) {
            if (arg == "types") {
                return this.adjustTypes(convertSqlArrays(this.types));
            }
            if (arg == "supertypes") {
                return convertSqlArrays(this.supertypes);
            }
        },
        getManaCost() {
            var manaCost = String();
            Object.keys(this.selectedManaCost).forEach(color => {
                for (let i = 0; i < this.selectedManaCost[color]; i++) {
                    manaCost += color;
                }
            });
            return manaCost;
        },
        adjustTypes(types) {
            var typeOptions = [{
                value: null,
                text: 'Select a type',
                disabled: true
            }]
            types.forEach(type => {
                typeOptions.push({
                    value: type,
                    text: type
                });
            })
            return typeOptions;
        }
    }
}
</script>

<style lang="sass" scoped>
@import '../styles/_styles.sass'
</style>