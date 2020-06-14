<template>
    <body>
        <div class="index_box">
            <p>This website lets you browse through magic cards. Currently features sets are:</p>
                <div class="set-list" v-for="set in sets" :key="set.setname">
                    - <b>{{ set.setname }}</b>
                </div>
            <p>
            <p>You might also want to check out the deck builder</p>
        </div>
    </body>
</template>



<script>

export default {
    name: 'index',
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
            console.log(this.input)
            fetch('http://localhost:8000/sets')
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