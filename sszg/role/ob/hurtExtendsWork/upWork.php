<?php 
namespace App\myclass\sszg\role\ob\hurtExtendsWork;

 /**
 * 攻击加成类计算
 */
 class upWork
 {

 	private $default_rang=[//默认生效范围
 		'wushang'=>['wushang'],
 		'fashang'=>['fashang'],
 		'shanghai'=>['fashang','wushang'],
 		'baoshang'=>['fashang','wushang'],
 	];

 	 /**
	 * 获取结果
	 *@param object $attacker 攻击者 
	 *@param object $attack_info_package 攻击信息包 
	 *@return array $result 加成信息
	 */
 	public function getResult($attacker,$attack_info_package){
 		
 		$result=[];//加成信息
		// 暴击结算信息
		$result=array_merge($result,$this->baoshang($attacker,$attack_info_package));

		//物理、法术伤害加成
		$result=array_merge($result,$this->wfs($attacker,$attack_info_package));

		//其他附带伤害加成项结算
		$result=array_merge($result,$this->other($attacker,$attack_info_package));
 		
 		return $result;
 	}


 	/**
	 *物理、法术、伤害加成结算 
	 *@param array $attack_info_package 攻击者 
	 *@param array $attack_info_package 攻击信息包 
	 *@return array $result 物伤信息
	 */
	private function wfs($attacker,$attack_info_package){
			
			$up_result=[];
			$rang=['wushang','fashang','shanghai'];
			foreach ($rang as $k => $v) {
				$upname=$v;//加成名
				$up_rang=$this->default_rang[$upname];//指定默认生效伤害类型
				
				//是否生效
				$isup=$this->getshengxiao($attack_info_package['type'],$upname,$up_rang,$attack_info_package['other']);

				if ($isup) {
					//加成值
					$up=$this->getUp($upname,$attack_info_package,$attacker);
					//记录数据
					$up_result[$upname]=['shengxiao'=>$isup];
					$up_result[$upname]['p']=$up;
				}
			}

		return $up_result;
	}

	/**
	 *暴击结算 
	 *@param array $attack_info_package 攻击者 
	 *@param array $attack_info_package 攻击信息包 
	 *@return array $baoji_result 暴击结算信息
	 */
	private function baoshang($attacker,$attack_info_package){

		$upname='baoshang';
		$up_rang=$this->default_rang['baoshang'];//指定默认可暴击的伤害类型

		$isbaojiup=$this->getshengxiao($attack_info_package['type'],$upname,$up_rang,$attack_info_package['other']);

		//暴击结果数据记录
		$baoji_result=[];
		

		//暴击伤害
		$baoshang=$this->getUp($upname,$attack_info_package,$attacker)-100;
		$baoshang=$baoshang<=0?0:$baoshang;

		//暴击率
		$baoji=$this->getUp('baoji',$attack_info_package,$attacker);

		//暴击命中
		$baoji=$baoji>=100?100:$baoji;
		$baoji=$baoji<0?0:$baoji;
		$baoji_p=[
			'baoji'=>$baoji,
			'bubaoji'=>100-$baoji,
		];
		$is_baoji= $this->probability($baoji_p);
		//数据记录
		$baoji_result['baoji']=$baoji;//暴击率
		$baoji_result['p']=$baoshang<0?0:$baoshang;//暴伤
		$baoji_result['baojimingzhong']=$is_baoji=='baoji'?true:false;//暴击命中
		$baoji_result['result']=$is_baoji;//暴击结果

			// //抗暴命中
			// if($is_baoji=='baoji'){
			// 	$kangbao=$this->getAttrValue('kangbao')>100?100:$this->getAttrValue('kangbao');
			// 	$kangbao_p=[
			// 		'bubaoji'=>$kangbao,
			// 		'baoji'=>100-$kangbao,
			// 	];
			// 	$is_baoji= $this->probability($baoji_p);

			// 	//数据记录
			// 	$baoji_result['kangbao']=$kangbao;//抗暴率
			// 	$baoji_result['kangbaomingzhong']=$is_baoji=='bubaoji'?true:false;//抗暴命中
			// 	$baoji_result['result']=$is_baoji;//暴击结果
				
			// }

		//必定暴击和必定不暴击参数
		$baoji_result['result']=in_array('baoji',$attack_info_package['other'])?'baoji':$baoji_result['result'];
		$baoji_result['result']=in_array('bubaoji',$attack_info_package['other'])?'bubaoji':$baoji_result['result'];

		$baoji_result['shengxiao']=	$baoji_result['result']=='baoji'?true:false;		

		return ['baoshang'=>$baoji_result];//暴击结果
		
	}

	/**
	 *其他伤害加成结算 
	 *@param array $attack_info_package 攻击者 
	 *@param array $attack_info_package 攻击信息包 
	 *@return array $result 物伤信息
	 */
	private function other($attacker,$attack_info_package){
		$up_result=[];
		foreach ($attack_info_package['valuechange'] as $k => $v) {
			$up_result[$k]=[
				'shengxiao'=>true,
				'p'=>$v,
			];
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
			$up=$attack_info_package['attributechange'][$upname]+$attacker->getAttrValue($upname);
		}else{
			$up=$attacker->getAttrValue($upname);
		}

		return $up<0?0:$up; //不小于0
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