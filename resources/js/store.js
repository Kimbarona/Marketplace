import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        isLoggedIn: null,
        apiURL: "http://10.10.12.11:8181/api",
        serverPath: "http://10.10.12.11:8181",
        profile: {}
    },
    mutations: {
        // authenticate(state, payload){
        //     state.isLoggedIn = auth.isLoggedIn();
        //     if (state.isLoggedIn) {
        //         state.profile = payload;
        //     }else{
        //         state.profile = {};
        //     }
        // }
    },
    actions: {
        // authenticate(context, payload){
        //     context.commit('authenticate', payload);
        // }
    }
});
