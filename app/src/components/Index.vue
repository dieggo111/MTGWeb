<template>
    <body>
        <div class="index-box">
            <div class="index-about-box">
                <h2>About MTG Web</h2>
                    <p>This website lets you browse through magic cards. Currently features sets are:</p>
                        <div class="set-list" v-for="set in sets" :key="set">
                            - <b>{{ set }}</b>
                        </div>
            </div>
            <div class="index-deckbuilder-box">
                <h2>Deck Builder</h2>
                    <p>While browsing, you can add cards to your
                    personal deck list by clicking on them. Check and edit
                    your deck list in the
                    <b>deck builder</b> section.</p>
            </div>
        </div>
    </body>
</template>



<script>
import {backendAddress} from '../utils'

export default {
    name: 'Index',
    data() {
        return{
            sets: []
        };
    },
    created() {
        this.searchSets();
    },
    methods:{
        searchSets() {
            fetch(backendAddress.concat('sets'))
                .then(res => res.json())
                .then(res => {
                    console.log(res);
                    this.sets = res;
                    }
                )
                .catch(error => {
                    console.log(error)
                })
        },
    }
}
</script>

<style lang="sass" scoped>
@import '../styles/_styles.sass'
</style>