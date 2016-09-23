<template>
  <div>
    <button class="btn btn-primary" v-if="$route.path !== '/time-entries/log-time'" v-link="'/time-entries/log-time'">创建</button>

    <div v-if="$route.path === '/time-entries/log-time'">
      <h3>创建</h3>
    </div>
    <hr>

    <!--// 下一级视图-->
    <router-view></router-view>
    <div class="time-entries">
      <p v-if="!timeEntries.length"><strong>还没有任何任务</strong></p>
      <div class="list-group">
        <!--// v-for循环渲染  track-by 配合v-for使用 每个对象默认有一个唯一的index如果两个完全相同的对象是无法
        继续追加会报错,所以加这个属性是id自增-->
        <a class="list-group-item" v-for="timeEntry in timeEntries" track-by="$index">
          <div class="row">
            <div class="col-sm-2 user-details">
              <img :src="timeEntry.user.image" class="avatar img-cicle img-responsive" />
              <p class="text-center">
                <strong>
                  {{timeEntry.user.name}}
                </strong>
              </p>
            </div>
            <div class="col-sm-2 text-center time-block">
              <h3 class="list-group-item-text total-time">
                <i class="glyphicon glyphicon-calendar"></i>
                {{timeEntry.totalTime}}
              </h3>
              <p class="label label-primary text-center">
                <i class="glyphicon glyphicon-calendar"></i>
                {{timeEntry.date}}
              </p>
            </div>

            <div class="col-sm-7 comment-section">
              <p>{{timeEntry.comment}}</p>
            </div>

            <div class="col-sm-1">
                <button class="btn btn-xs btn-danger delete-button"
                        @click="deleteTimeEntry(timeEntry)">
                </button>
            </div>
          </div>
        </a>
      </div>
    </div>


  </div>


</template>

<script>
  export default{
    data(){
      // 事先模拟一个数据
      let existingEntry = {
        user:{
          name:'二货',
          email:'119@qq.com',
          image:'https://sfault-avatar.b0.upaiyun.com/888/223/888223038-5646dbc28d530_huge256'
        },
        comment:'我的第一个备注',
        totalTime:1.5,
        date:'2016-05-01'
      }
      return {
        timeEntries:[existingEntry]
      }
    },
    methods:{
      deleteTimeEntry(timeEntry){
          /* 方法1 */
//        let index = this.timeEntries.indexOf(timeEntry)
        if (window.confirm('确定要删除吗？')) {
//          this.timeEntries.splice(index,1)
          /*方法2*/
          this.timeEntries.$remove(timeEntry)
          // 这里会派发到父组件，执行父组件events里的deleteTime方法
          this.$dispatch('deleteTime', timeEntry)
        }
      }
    },
    events:{
      timeUpdate(timeEntry){
        this.timeEntries.push(timeEntry)
        return true
      }
    }
  }
</script>
<style>
  .avatar{
    height:75px;
    margin:0 auto;
    margin-top:10px;
    margin-bottom: 10px;
  }
  .user-details{
    background-color: #f5f5f5;
    border-right: 1px solid #ddd;
    margin:-10px 0;
  }
  .time-block{
    padding:10px;
  }
  .comment-section{
    padding:20px;
  }
</style>
