<?php 
namespace App\myclass\sszg;

 /**
 * 防御加成类计算
 */
 class defenseWork
 {


 	 /**
	 * 获取结果
	 *@param object $underattack 被攻击者 
	 *@param object $attack_info_package 攻击信息包 
	 *@return array $result 信息
	 */
 	public function getResult($underattack,$attack_info_package){
 		$result=[];//加成信息

 		
 		return $result;
 	}

	/**
	 * 概率命中
	 *
	 * @param array $ps array('a'=>0.5,'b'=>5.2,'c'=>0.4)
	 * @return string 返回上面数组的key
	 */
	private function probability($ps){
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
	private function getFloatLen($num) {
		$num =(float)$num;
		$temp = explode ( '.', $num );
		$count = 0;
		if (count( $temp ) > 1) {
			$decimal = end ( $temp );
			$count = strlen ( $decimal );
		}
		return $count;
	}
 }

?>