<!DOCTYPE html>
    <html lang="zh-CN">
      <head>
        <meta charset="utf-8">
        <title>智慧食堂</title>
      </head>
      <body><!--nobanner-->
<?php
/*
class Mysql{

  const DB_Host = 'localhost';
  const DB_USER = 'root';
  const DB_PWD = '123456';
  const DB_NAME = 'lmokey';

  function testConst() {

    echo self::DB_Host;
  }

}

$m = new Mysql;

 $m->testConst();

 */
/*
 class Computer {
  public $type = "MAC Pro";
  public $menory = "2G";
  public $disk = "1T";
  public $cpu = "i7 5.8M";
  static public $color = "pink";

  function playGame(){
    echo '面对疾风';
  }

  function seeMoive(){
    echo '风雪家人';
  }

 static function zhuangx(){
    echo 'BBB';
    echo self::$color;
  }

 }

 echo Computer::$color;
 Computer::zhuangx();
 */
/*
  abstract class Person {
  protected $name;
  protected $country;

  function __construct($name="", $country="China") {
    $this->country=$country;
    $this->name=$name;
  }

  abstract function say();

  abstract function eat();

   function run() {
    echo "都跑的快";

 }

} 
 class ChineseMan extends Person {

  function say() {
    echo $this->name."是".$this->country."的人，将汉语<br>";
  }

  function eat() {
    echo $this->name."使用筷子吃饭<br>";
  }

 }

 class Americans extends Person {

  function say() {
    echo $this->name."是".$this->country."的人，说英文<br>";
  }

  function eat() {
    echo $this->name."是".$this->country."用刀叉吃饭<br>";
  }
 }

 $chineseman = new ChineseMan("老冯","中国");
 $american = new Americans("Ben C","美国人");

 $chineseman->say();
 $chineseman->eat();

 $american->say();
 $american->eat();


 //接口
 inerface One {
  const CONSTANT = "CONSTANT value";
  function fun1();
  function fun2();
 }

 abstract class Three implements One {

 }

class Four implements One {

  function fun1() {

  }
  function fun2(){

  }
}
*/
//多态性的应用

trait Demo1_trait {
  function func() {
    echo "ss";
  }
}
trait Demo2_trait {
   function func(){
    echo "dem2";
   }
}
class Demo_class {
  use Demo1_trait, Demo2_trait {
    Demo1_trait::func insteadof Demo2_trait;
  }
}

$obj = new Demo_class();

$obj->func();
?>



      </body>
    </html>