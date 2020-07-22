<template>
    <b-container class="search-toolbar">
        <b-row>
            <b-col cols="3">
                <b-container class="mt-2">
                    Display option:
                </b-container>
                <b-dropdown
                        id="display-opt-dropdown"
                        :text="getDisplayOption"
                        class="m-md-3">
                    <b-dropdown-item
                            @click="changeDisplayOption(option)"
                            :v-bind="displayOptions"
                            v-for="option in displayOptions"
                            :key="option">
                        {{ option }}
                    </b-dropdown-item>
                </b-dropdown>
            </b-col>
            <b-col cols="3">
                <b-container class="mt-2">
                    Sort by:
                </b-container>
                <b-dropdown
                        id="sort-opt-dropdown"
                        :text="getSortOption"
                        class="m-md-3">
                    <b-dropdown-item
                            @click="changeSortOption(option)"
                            :v-bind="sortOptions"
                            v-for="option in sortOptions"
                            :key="option">
                        {{ option }}
                    </b-dropdown-item>
                </b-dropdown>
            </b-col>
            <b-col>
                <div class="search-navigation">
                    <b-pagination
                            class="mt-5"
                            v-model="currentPage"
                            :total-rows="paginationRows"
                            :per-page="perPage"
                            :items="items"
                            limit="3"
                            @change="changeEvent">
                    </b-pagination>
                </div>
            </b-col>
        </b-row>
    </b-container>
</template>


<script>

export default {
    name: "Toolbar",
    props: [
        "displayOptions",
        "displayOptionDict",
        "sortOptions",
        "sortOptionDict",
        "paginationRows",
        "perPage",
        "items"
    ],
    data() {
        return{
            currentPage: 1
        };
    },
    computed: {
        getDisplayOption() {
            for (var key in this.displayOptionDict) {
                if (this.displayOptionDict[key] === true) {
                    return key;
                }
            }
            return "";
        },
        getSortOption() {
            for (var key in this.sortOptionDict) {
                if (this.sortOptionDict[key] === true) {
                    return key;
                }
            }
            return "";
        },
    },
    methods: {
        changeDisplayOption(option) {
            for (var key in this.displayOptionDict) {
                if (key == option) {
                    this.displayOptionDict[key] = true;
                } else {
                    this.displayOptionDict[key] = false;
                }
            }
        },
        changeSortOption(option) {
            for (var key in this.sortOptionDict) {
                if (key == option) {
                    this.sortOptionDict[key] = true;
                } else {
                    this.sortOptionDict[key] = false;
                }
            }
        },
        changeEvent() {
            this.$emit('change', this.currentPage + 1);
        }
    }
}
</script>

<style lang="sass" scoped>
@import '../styles/_styles.sass'
</style>
