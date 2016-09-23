<template>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h1 class="text-center">已有时长</h1>
    </div>
    <div class="panel-body">
      <h1 class="text-center">{{time}}小时</h1>
    </div>
  </div>
</template>

<script>
//  props 的意思是获取父元素的time属性值，可以是多个
  export default{
    props:['time']
  }
</script>
