/**
 * Created by Administrator on 2016/9/26 0026.
 */

export default{
  fetch(){
    return JSON.parse(window.localStorage.getItem('STORE_KEY'));
  },
  save(items){
    window.localStorage.setItem('STORE_KEY', JSON.stringify(items))
  }
}
