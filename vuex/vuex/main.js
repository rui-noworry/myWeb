/**
 * Created by Administrator on 2016/10/24 0024.
 */

import Vue from 'Vue'
import store from './vuex/store'
import App from './components/App.vue'

new Vue({
    store,
    el:'body',
    components:{
        App
    }
})
