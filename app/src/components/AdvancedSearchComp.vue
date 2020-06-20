<template>
    <div class="adv-search-box">
        <!-- <header><h3>Advanced Search</h3></header> -->
        <body>
            <table class="adv-search-table">
                <tr>
                    <td class="first-col">Set</td>
                    <td>
                        <template>
                            <b-form-tags
                                    :input-attrs="{ list: 'set-list' }"
                                    v-model="selectedSets">
                            </b-form-tags>
                            <b-form-datalist id="set-list" :options="sets">
                            </b-form-datalist>
                        </template>
                    </td>
                </tr>
                <tr>
                    <td class="first-col">Colors</td>
                    <td><b-button
                            class="color-btn"
                            id="black-btn"
                            variant="outline-secondary"
                            @click="setColor('black')">
                        <img class="rounded-image" :src="blackImage" />
                        </b-button>
                        <b-button
                            class="color-btn"
                            id="white-btn"
                            variant="outline-secondary"
                            @click="setColor('white')">
                            <img class="rounded-image" :src="whiteImage" />
                        </b-button>
                        <b-button
                            class="color-btn"
                            id="green-btn"
                            variant="outline-secondary"
                            @click="setColor('green')">
                            <img class="rounded-image" :src="greenImage" />
                        </b-button>
                        <b-button
                            class="color-btn"
                            id="red-btn"
                            variant="outline-secondary"
                            @click="setColor('red')">
                            <img class="rounded-image" :src="redImage" />
                        </b-button>
                        <b-button
                            class="color-btn"
                            id="blue-btn"
                            variant="outline-secondary"
                            @click="setColor('blue')">
                            <img class="rounded-image" :src="blueImage" />
                        </b-button>
                    </td>
                </tr>
                <tr>
                    <td class="first-col">Rarity</td>
                    <td><b-form-group>
                            <b-form-checkbox-group id="checkbox-group-1"
                                    v-model="selectedRarities"
                                    :options="options"
                                    name="flavour-1"/>
                        </b-form-group>
                    </td>
                </tr>
                <tr>
                    <td class="first-col">Card Type</td>
                    <td>
                        <template>
                            <b-form-tags
                                    :input-attrs="{ list: 'types-list' }"
                                    v-model="selectedTypes">
                            </b-form-tags>
                            <b-form-datalist id="types-list" :options="getTypes('types')">
                            </b-form-datalist>
                        </template>
                    </td>
                </tr>
            </table>
            <div>
                <b-button class="mt-5" block @click="goAdvSearch">Search Card</b-button>
            </div>
        </body>
    </div>
</template>



<script>

export default {
    name: 'advancedsearch',
    data() {
        return{
            tagsOptions: ["awdaw", "awdawda"],
            selectedTypes: [],
            selectedRarities: [],
            selectedSets: [],
            options: ['Common', 'Uncommon', 'Rare', 'Mythic Rare'],
            selectedColors: {
                "black": false,
                "white": false,
                "green": false,
                "red": false,
                "blue": false
            },
            sets: [],
            types: [],
            supertypes: [],
            blackImage: require('../assets/black_trans.png'),
            whiteImage: require('../assets/white_trans.png'),
            greenImage: require('../assets/green_trans.png'),
            redImage: require('../assets/red_trans.png'),
            blueImage: require('../assets/blue_trans.png'),
            // ikoraImage: require('../assets/ikora_trans.png'),
        };
    },
    created() {
        this.searchSets();
        this.searchTypes();
        this.searchSuperTypes();
    },
    computed:{
    },
    methods:{
        goAdvSearch() {
            console.log(this.selectedColors)
            console.log(this.selectedRarities)
            console.log(this.selectedTypes)
            console.log(this.selectedSets)
            this.$router.push({
                            name: 'search',
                            query: {
                                types: this.selectedTypes,
                                rarity: this.selectedRarities,
                                colors: this.getColors(this.selectedColors)
                            }
            }).catch(error => {
                console.log("Ignoring dublicate navigation");
                error;
            });
        },
        searchSets() {
            fetch('http://localhost:8000/sets')
                .then(res => res.json())
                .then(res => {
                    console.log(res);
                    this.sets = res;
                    }
                )
                .catch(error => {
                    console.log(error);
                })
        },
        searchTypes() {
            fetch('http://localhost:8000/types')
                .then(res => res.json())
                .then(res => {
                    console.log(res);
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
                    console.log(res);
                    this.supertypes = res;
                    }
                )
                .catch(error => {
                    console.log(error);
                })
        },
        setColor(color) {
            this.selectedColors[color] = !this.selectedColors[color];
        },
        setSet(setname) {
            this.selectedSet = setname;
        },
        getTypes(arg) {
            if (arg == "types") {
                return this.convertSqlArrays(this.types);
            }
            if (arg == "supertypes") {
                return this.convertSqlArrays(this.supertypes);
            }
        },
        convertSqlArrays(array) {
            for (let i=0; i<array.length; i++) {
                array[i] = array[i].replace("{", "");
                array[i] = array[i].replace("}", "");
                array[i] = array[i].replace(",", " ");
            }
            return array;
        },
        getColors(colorObject) {
            return Object.keys(colorObject).filter(key => colorObject[key])
        }
    }
}

</script>

<style lang="sass" scoped>
@import '../styles/_styles.sass'
</style>