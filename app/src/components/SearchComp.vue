<template>
    <div class="adv-search-box">
        <header><h3>Advanced Search</h3></header>
        <body>
            <p>Set
            <b-button class="color-btn"
                      variant="outline-secondary"
                      @click="colorSelect('ikora')">
                <img class="rounded-image" :src="ikoraImage" />
            </b-button>
            </p>
            <p>Colors
            <b-button class="color-btn"
                      variant="outline-secondary"
                      @click="colorSelect('black')">
                <img class="rounded-image" :src="blackImage" />
            </b-button>
            <b-button class="color-btn"
                      variant="outline-secondary"
                      @click="colorSelect('white')">
                <img class="rounded-image" :src="whiteImage" />
            </b-button>
            <b-button class="color-btn"
                      variant="outline-secondary"
                      @click="colorSelect('green')">
                <img class="rounded-image" :src="greenImage" />
            </b-button>
            <b-button class="color-btn"
                      variant="outline-secondary"
                      @click="colorSelect('red')">
                <img class="rounded-image" :src="redImage" />
            </b-button>
            <b-button class="color-btn"
                      variant="outline-secondary"
                      @click="colorSelect('blue')">
                <img class="rounded-image" :src="blueImage" />
            </b-button></p>
            <p>Converted mana cost</p>
        </body>
    </div>
</template>



<script>

export default {
    name: 'SearchComp',
    data() {
        return{
            blackImage: require('../assets/black_trans.png'),
            whiteImage: require('../assets/white_trans.png'),
            greenImage: require('../assets/green_trans.png'),
            redImage: require('../assets/red_trans.png'),
            blueImage: require('../assets/blue_trans.png'),
            ikoraImage: require('../assets/ikora_trans.png'),
        };
    },
    methods: {
        colorSelect(event, color) {
            console.log(event)
            console.log(color)
        }
    }
}

</script>

<style lang="sass" scoped>
@import '../styles/_styles.sass'
</style>