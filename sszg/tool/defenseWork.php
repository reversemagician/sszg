<?php 
namespace App\myclass\sszg\tool;

 /**
 * 防御加成类计算
 */
 class defenseWork 
 {
 	private $default_rang=[//默认生效范围
 		'wumian'=>['wushang'],
 		'fashang'=>['fashang'],
 		'shanghai'=>['fashang','wushang'],
 		'baoshang'=>['fashang','wushang'],
 	];

 	 /**
	 * 获取结果
	 *@param object $underattack 被攻击者 
	 *@param object $attack_info_package 攻击信息包 
	 *@return array $result 信息
	 */
 	public function getResult($underattack,$attack_info_package){
 		$result=[];
	
		// print_r($attack_info_package);die;
		// 
		// $result=array_merge($result,$this->wfs($underattack,$attack_info_package));

		
 		
 		return $result;
 	}

 	/**
	 *物理、法术、伤害加成结算 
	 *@param array $attack_info_package 攻击者 
	 *@param array $attack_info_package 攻击信息包 
	 *@return array $result 物伤信息
	 */
	private function wfs($underattack,$attack_info_package){
			
			$up_result=[];
			$rang=['wushang','fashang','shanghai'];
			foreach ($rang as $k => $v) {
				$upname=$v;//加成名
				$up_rang=$this->default_rang[$upname];//指定默认生效伤害类型
				
				//是否生效
				$isup=$this->getshengxiao($attack_info_package['type'],$upname,$up_rang,$attack_info_package['other']);

				if ($isup) {
					//加成值
					$up=$this->getUp($upname,$attack_info_package,$underattack);
					//记录数据
					$up_result[$upname]=['shengxiao'=>$isup];
					$up_result[$upname]['up']=$up;
				}
			}

		return $up_result;
	}


	/**
	 *是否生效 
	 *@param array $type 攻击类型 
	 *@param array $name 加成名
	 *@param array $rang 默认生效范围 
	 *@param array $other 信息包 
	 *@return bool $result 
	 */
	private function getshengxiao($type,$name,$rang,$other){
		$result=false;
		$result=in_array($type,$rang)?true:$result;
		$result=in_array('no'.$name,$other)?false:$result;//指定加成不生效
		$result=in_array($name,$other)?true:$result;//指定加成生效
		return $result;
	}

	/**
	 *获取加成值 
	 *@param array $upname 加成名 
	 *@param array $attack_info_package 
	 *@param array $attacker  
	 *@param array $other 信息包 
	 *@return int $up 
	 */
	private function getUp($upname,$attack_info_package,$attacker){
		$up=0;//加成
		if(isset($attack_info_package['attributechange'][$upname])){
			$up=$attack_info_package['attributechange'][$upname]['value']+$attack_info_package['attributechange'][$upname]['p']*$attacker->getAttrValue($upname)/100+$attacker->getAttrValue($upname);
		}else{
			$up=$attacker->getAttrValue($upname);
		}

		return $up<0?0:$up;
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