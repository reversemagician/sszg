<?php 
namespace App\myclass\sszg\tool;

 /**
 * 通用工具集
 */
 class ordinary
 {
 	/**
	 * 概率命中
	 *
	 * @param array $ps array('a'=>0.5,'b'=>5.2,'c'=>0.4)
	 * @return string 返回上面数组的key
	 */
	public function probability($ps){

        $sum = array_sum($ps);
        $len=0;
       	foreach ($ps as $k => $v) {
       		$newlen=$this->getFloatLen($v);
       		$len =$newlen>$len?$newlen:$len;
       	}
       	$base=1;
       	for ($i=0; $i < $len; $i++) { 
       		$base=$base*10;
       	}
        $max=$sum*$base;
        $rand = mt_rand(1,$max);
        $randsum=0;
        $result='';
        foreach ($ps as $k => $v) {
        	$randsum=$base*$v+$randsum;
        	if( $rand <= $randsum ){
        		$result=$k; break;
        	}
        }
	    return $result;
	}

	//获取数字的小数长度
	public function getFloatLen($num) {
		$num =(float)$num;
		$temp = explode ( '.', $num );
		$count = 0;
		if (count( $temp ) > 1) {
			$decimal = end ( $temp );
			$count = strlen ( $decimal );
		}
		return $count;
	}


	/**
	 *获取多维数组的某个值 
	 *@param array $arr 数组 
	 *@param array $key 属性名 key0.key1.key2 
	 *@return value 属性不存在则返回字符串'undefined'
	 */
	public function getArrayValue($key='',$arr=[]){
		if($key==''||empty($arr)){return '';}
		$layer=explode('.',$key);
		$vstr='$arr';
		foreach ($layer as $v) {
			$vstr.='["'.$v.'"]';
		}
		$evalstr='$result=isset('.$vstr.')?'.$vstr.':"undefined";';//undefined该值不存在
		eval($evalstr);
		return $result;
	}
 }

?>