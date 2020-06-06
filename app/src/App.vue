<template>
    <div class="app">
        <b-navbar class="navbar" fixed="top" variant="info" type="dark">
        <b-navbar-brand tag="h1" class="mb-0" href="/">MTG Web</b-navbar-brand>
        <b-navbar-nav class="nav-search-form">
            <b-form-input
                @keydown.native="keydownSubmit"
                v-model="input"
                size="sm"
                class="mr-sm-2"
                placeholder="Card Name">
                <span class="glyphicon glyphicon-search"></span>
            </b-form-input>
            <b-button
                @click="searchCard"
                size="sm"
                class="my-2 my-sm-0"
                type="submit">Find
            </b-button>
        </b-navbar-nav>
        <b-navbar-nav class="nav-items">
            <b-nav-item href="/search">Advanced Search</b-nav-item>
            <b-nav-item href="/deckbuilder">Deck Builder</b-nav-item>
        </b-navbar-nav>
        </b-navbar>
        <div class="content">
        <router-view></router-view>
        </div>
    </div>
</template>


<script>

export default {
    name: 'App',
    data(){
        return{
            input: "",
        };
    },
    methods:{
        searchCard() {
            console.log(this.input)
            this.$router.push("/results")
            // fetch('http://localhost:12345/items')
            //     .then(res => res.json())
            //     .then(res => {
            //         this.$store.commit(
            //             "setShopItems", this.addUtilityItems(res))
            //         this.prepareCart(res)
            //     })
            //     .catch(error => {
            //         console.log(error)
            //     })
        },
        keydownSubmit(event) {
            if (event.which === 13) {
                this.searchCard()
            }
        }
    }
}
</script>

<style lang="sass" scoped>
@import './styles/_styles.sass'
</style>
