<template>
  <div class="form-horizontal">
    <div class="form-group">
      <div class="col-sm-6">
        <label>日期</label>
        <input type="date" v-model="timeEntry.date" class="form-control" placeholder="Date"/>
      </div>
      <div class="col-sm-6">
        <label>时间</label>
        <input type="text" v-model="timeEntry.totalTime" class="form-control" placeholder="Hours"/>
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-6">
        <label>备注</label>
        <input type="date" v-model="timeEntry.comment" class="form-control" placeholder="Comment"/>
      </div>
    </div>
    <button class="btn btn-primary" @click="save">保存</button>
    <button class="btn btn-danger" v-link="/time-entries">取消</button>
    <hr>
  </div>
</template>

<script>
  export default{
    data(){
      return {
        timeEntry:{
          user:{
            name:'二货',
            email:'119@qq.com',
            image:'https://sfault-avatar.b0.upaiyun.com/888/223/888223038-5646dbc28d530_huge256'
          }
        }
      }
    },
    methods:{
      save(){
        let timeEntry = this.timeEntry
        //  JSON.parse(JSON.stringify(timeEntry)) 转换成字符串再解析 目的是重新拷贝对象不指向原来的地址（
        // 这么做的原因是v-model是双向绑定，文本框的值和对象是指向同一个地址，所以当文本框值改变的时候所有
        // 后来追加的元素值都会跟着变，所以要重新拷贝，不指向原来的引用地址，值一样，但是引用地址不同）
        this.$dispatch('timeUpdate',  JSON.parse(JSON.stringify(timeEntry)))
      }
    }
  }
</script>
