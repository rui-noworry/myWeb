import Vue from 'vue'
import App from './App'
import Home from './components/Home'
import TimeEntries from './components/TimeEntries'
import LogTime from './components/LogTime'

// router和resource必须配合使用
// vue路由插件
import VueRouter from 'vue-router'
// vueXHR ajax 异步请求插件
import VueResource from 'vue-resource'

Vue.use(VueResource)
Vue.use(VueRouter)

// 实例路由
const router  = new VueRouter();

// 映射路由
router.map({
  '/home':{
    component:Home
  },
  '/time-entries':{
    component:TimeEntries,
    subRoutes:{
      '/log-time':{
        component:LogTime
      }
    }
  }
})

// 路由由当前路径跳转到指定路径
router.redirect({
  '/':'/home'
})
// 路由器的运行需要一个根组件，router.start(App, '#app') 表示router会创建一个App实例，并且挂载到#app元素。
router.start(App, '#app')
