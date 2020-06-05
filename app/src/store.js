import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export const store = new Vuex.Store({
    state: {
    },
    getters: {
        // getAccessToken(state) {
        //     return state.token
        // }
        // getTransactionStatus(state) {
        //     return state.transactionStatus
        // }
    },
    mutations: {
        // retrieveToken(state, token) {
        //     state.token = token
        // },
        // destroyToken(state) {
        //     state.token = null
        // },
    },
    actions: {
        // getCoupon(context, username) {
        //     return new Promise((resolve, reject) => {
        //         fetch('http://localhost:12345/getCoupon', {
        //             method: 'post',
        //             body: username
        //         })
        //         .then(res => res.json())
        //         .then(res => {
        //             localStorage.setItem("coupon_token", res)
        //             context.commit("setCouponToken", res)
        //             resolve(res)
        //         })
        //         .catch(error => {
        //             reject(error)
        //         })
        //     })
        // }
    }
});

