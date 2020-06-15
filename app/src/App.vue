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
                    @click="goSearch"
                    size="sm"
                    class="my-2 my-sm-0"
                    type="submit">
                Find
            </b-button>
        </b-navbar-nav>
        <b-navbar-nav class="nav-items">
            <b-nav-item href="/advancedsearch">Advanced Search</b-nav-item>
            <b-nav-item href="/deckbuilder">Deck Builder</b-nav-item>
        </b-navbar-nav>
        </b-navbar>
        <body>
            <div class="content">
                <router-view :key="$route.fullPath"></router-view>
            </div>
        </body>
    </div>
</template>


<script>

export default {
    name: 'App',
    data() {
        return{
            input: "",
        };
    },
    methods:{
        goSearch() {
            this.$router.push({
                            name: 'search',
                            query: { name: this.input }
            }).catch(error => {
                console.log("Ignoring dublicate navigation");
                error;
            });
        },
        keydownSubmit(event) {
            if (event.which === 13) {
                this.goSearch()
            }
        }
    }
}
</script>

<style lang="sass" scoped>
@import './styles/_styles.sass'
</style>
