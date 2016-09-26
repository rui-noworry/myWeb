<template>
  <div class="hello">
    <h1>{{ msg }}{{tips}}</h1>
    <input type="button" @click="changeColor" value="change color" />
  </div>
</template>

<script>
export default {
  // props 是获取父组件中调用子组件自定义标签的属性
  props:['msg','tips'],
  methods:{
    changeColor() {
      this.$dispatch('changeBg', 'red')
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1 {
  color: #42b983;
}
input{
  margin-bottom: 10px;
}
</style>
