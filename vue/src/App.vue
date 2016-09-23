<template>
  <div id="app">
    <div id="logo"><img class="logo" src="./assets/logo.png"></div>
    <hello></hello>
    <input type="text" v-model="inputValue" @keyup.enter="addli" />
    <ul>
      <li v-for="item in items" @click="underline(item)" :class="{show:item.ishow}">
        {{item.label}}
        <span @click="del($index)">delete</span>
      </li>
    </ul>

  </div>

  <p v-if="ok">aaa</p>
  <p v-show="show">bbb</p>
</template>

<script>
import Hello from './components/Hello'
export default {
  data(){
    return {
      inputValue:'',
      items:[
        {
          label:'huangrui1',
          ishow:true
        },
        {
          label:'huangrui2',
          ishow:true
        },
        {
          label:'huangrui3',
          ishow:false
        }
      ]
    }
  },
  components: {
    Hello
  },
  methods:{
    underline:function (item) {
      item.ishow = !item.ishow;
    },
    addli:function () {
      this.items.push({
        ishow:true,
        label:this.inputValue
      });
      this.inputValue = "";
    },
    del:function (index) {
      this.items.splice(index,1);
    }
  }
}
</script>

<style>
html {
  height: 100%;
}
body {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;
}

#app {
  color: #2c3e50;
  margin-top: -100px;
  max-width: 600px;
  font-family: Source Sans Pro, Helvetica, sans-serif;
  text-align: left;
  border: 1px solid #eee;
  padding: 20px;
}

#app a {
  color: #42b983;
  text-decoration: none;
}
#logo{
  text-align: center;
}
.logo {
  width: 100px;
  height: 100px
}
.show{
  text-decoration: underline;
}
ul{
  padding-left: 0;
}

ul li {
  list-style: none;
  text-align: left;
  border-bottom: 1px dashed #ddd;
  line-height: 30px;
  background: url("./assets/li-icon.png") no-repeat left center;
  padding-left: 20px;
  background-size: 15px 15px;
  cursor: pointer;
}
ul li span{
  background: red;
  /*padding:5px;*/
  color: #fff;
  display: inline-block;
  border-radius: 5px;
  line-height: 20px;
  padding: 0 5px;
  margin-left: 5px;
}
</style>
