<template>
    <div class="adv-search-box">
        <header><h3>Advanced Search</h3></header>
        <body>
            <table class="adv-search-table">
                <tr>
                    <td>Set</td>
                    <td>
                        <template>
                            <b-form-tags :input-attrs="{ list: 'set-list' }">
                            </b-form-tags>
                            <b-form-datalist id="set-list" :options="sets">
                            </b-form-datalist>
                        </template>
                    </td>
                </tr>
                <tr>
                    <td>Colors</td>
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
                    <td>Rarity</td>
                    <td><b-form-group>
                            <b-form-checkbox-group id="checkbox-group-1"
                                    v-model="selectedType"
                                    :options="options"
                                    name="flavour-1"/>
                        </b-form-group>
                    </td>
                </tr>
                <tr>
                    <td>Card Type</td>
                    <td>
                        <template>
                            <b-form-tags :input-attrs="{ list: 'types-list' }">
                            </b-form-tags>
                            <b-form-datalist id="types-list" :options="getTypes('types')">
                            </b-form-datalist>
                        </template>
                    </td>
                </tr>
            </table>
        </body>
    </div>
</template>



<script>

export default {
    name: 'advancedsearch',
    data() {
        return{
            tagsOptions: ["awdaw", "awdawda"],
            selectedType: [],
            options: ['Common', 'Uncommon', 'Rare', 'Mythic Rare'],
            selectedSet: "",
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
        showSet() {
            if (this.selectedSet == "") {
                return "Select Set";
            }
            return this.selectedSet;
        },
    },
    methods:{
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
        }
    }
}

</script>

<style lang="sass" scoped>
@import '../styles/_styles.sass'
tr
    margin-top: 100px
</style>