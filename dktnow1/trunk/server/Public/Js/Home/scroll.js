// JavaScript Document

$(function(){
              var scrolling=false;
              //$("scroll_box").onselectstart=function(e){return false}
              function $(o){return document.getElementById(o)}
              //function $(o){return document.getElementsByClassName(o)}

              //鼠标移入显示滚动条
              $("scroll_box").onmouseover=function show(){
                        //计算滚动条相对高度
                        var height = $("scroLeft").scrollHeight/$("scroll_box").scrollHeight;
                       $("scroLine").style.height=$("scroll_box").clientHeight/height+"px";
                       
                       $("scroLine").style.opacity=1;//设置可见
              }
              //鼠标移出隐藏滚动条
              $("scroll_box").onmouseout=function hide(){
                  $("scroLine").style.opacity=0;//设置隐藏
              }

              //鼠标拖动
              $("scroLine").onmousedown=function scroMove(){
                   scrolling=true;
                   document.onmousemove=function(e){
                       if(scrolling){
                           scroNow(e)
                       }
                       else{
                           return false;
                           }
                       }
                   document.onmouseup=function(e){
                       document.onmousemove=null;
                       scrolling=false
                       }
                       return false;   //非常重要！防止鼠标粘连，特别是火狐下
                  }

               
               //鼠标点击
              function scroNow(event){
                       var event=event?event:(window.event?window.event:null);
                       var Y=event.clientY-$("scroll_box").getBoundingClientRect().top-$("scroLine").clientHeight/2;
                       var H=$("scroll_box").clientHeight-$("scroLine").clientHeight;
                       var SH=Y/H*($("scroLeft").scrollHeight-$("scroLeft").clientHeight);
                  
                       if (Y<0)Y=0;
                       if (Y>H)Y=H;
                       $("scroLine").style.top=Y+"px";
                       $("scroLeft").scrollTop=SH;
              }
              $("scroRight").onclick= scroNow;
              //鼠标滚轮
              $("scroll_box").onmousewheel=function scrollWheel(){
                       var Y=$("scroLeft").scrollTop;
                       var H=$("scroLeft").scrollHeight-$("scroLeft").clientHeight;
                       if (event.wheelDelta >=120)
                       {Y=Y-180}
                       else
                       {Y=Y+180}
                       if(Y<0)Y=0;if(Y>H)Y=H;
                       $("scroLeft").scrollTop=Y;
                       var SH=Y/H*$("scroll_box").clientHeight-$("scroLine").clientHeight;
                       if(SH<0)SH=0;
                       $("scroLine").style.top=SH+"px";
              }

});
