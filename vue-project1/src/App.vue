<template>
  <div id="wrapper">
    <nav class="navbar navbar-default">
      <div class="container">
        <a href="#" class="navbar-brand">
          <i class="glyphicon glyphicon-time"></i>
          计划表
        </a>
        <ul class="nav navbar-nav">
          <li>
            <a v-link="'/home'">首页</a>
          </li>
          <li>
            <a v-link="'/time-entries'">计划列表</a>
          </li>
        </ul>
      </div>
    </nav>
    <div class="container">
      <div class="col-sm-3">
          <sidebar :time="totalTime"></sidebar>
      </div>
      <div class="col-sm-9">
        <router-view></router-view>
      </div>
    </div>
  </div>
</template>

<script>
  import sidebar from './components/sidebar'
  export default{
    components:{
      sidebar
    },
    data(){
      return {
        totalTime:1.5
      }
    },
    events:{
      timeUpdate(timeEntry){
        this.totalTime += parseFloat(timeEntry.totalTime)
      },
      deleteTime(timeEntry){
        this.totalTime -= parseFloat(timeEntry.totalTime)
      }
    }
  }
</script>

