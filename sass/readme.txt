执行sass bus.scss bus.css 即可生成bus.css 

常用的几种方法：

1.声明一个%block 

继承的语法：@extend %block

这样做的好处是：在不调用的情况下，不会编译，所以不会产生冗余的代码

2. 引入其他scss公共文件

   @import "mixin"

3.@function 定义的函数 直接用method()调用

4.@mixin定义的函数 调用 @include method(arg)

5. 定义一个公共变量
   
  $font-size:12px;