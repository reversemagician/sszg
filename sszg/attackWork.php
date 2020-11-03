<?php 
namespace App\myclass\sszg;

 /**
 * 常规加成类计算
 */
 class attackWork
 {

 	private $default_baoji_rang=['fashang','wushang'];//默认可暴击伤害类型
 	private $default_shanghai_rang=['fashang','wushang'];//默认伤害加成属性生效伤害类型


 	 /**
	 * 获取结果
	 *@param object $attacker 攻击者 
	 *@param object $underattack 被攻击者 
	 *@param object $attack_info_package 攻击信息包 
	 *@return array $result 加成信息
	 */
 	public function getResult($attacker,$attack_info_package){
 		$result=[];//加成信息
		// 暴击结算信息
		$result=array_merge($result,$this->baoshang_jiacheng_jiesuan($attacker,$attack_info_package));

		//物理、法术伤害加成
		$result=array_merge($result,$this->wufashang_jiacheng_jiesuan($attacker,$attack_info_package));

		//其他附带伤害加成项结算
		$result=array_merge($result,$this->qita_jiacheng_jiesuan($attacker,$attack_info_package));
 		
 		return $result;
 	}


 	/**
	 *物理、法术伤害加成结算 
	 *@param array $attack_info_package 攻击者 
	 *@param array $attack_info_package 攻击信息包 
	 *@return array $result 物伤信息
	 */
	public function wufashang_jiacheng_jiesuan($attacker,$attack_info_package){
			
			$up_result=[];
			$rang=['wushang','fashang'];
			foreach ($rang as $k => $v) {
				$upname=$v;//加成名
				$arr=[$upname];//指定默认生效伤害类型
				$isup=false;

				$isup=in_array($attack_info_package['type'],$arr)?true:$isup;
				$isup=in_array('no'.$upname,$attack_info_package['other'])?false:$isup;//指定加成不生效
				$isup=in_array($upname,$attack_info_package['other'])?true:$isup;//指定加成生效


				if ($isup) {
					$up=0;//加成
					if(isset($attack_info_package['attributechange'][$upname])){
						$up=$attack_info_package['attributechange'][$upname]['value']+$attack_info_package['attributechange'][$upname]['p']*$attacker->getAttrValue($upname)/100+$attacker->getAttrValue($upname);
					}else{
						$up=$attacker->getAttrValue($upname);
					}
					//记录数据
					$up_result[$upname]=['shengxiao'=>$isup];
					$up_result[$upname]['up']=$up<0?0:$up;
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
	public function baoshang_jiacheng_jiesuan($attacker,$attack_info_package){

		$upname='baoshang';
		$baojiarr=$this->default_baoji_rang;//指定默认可暴击的伤害类型
		$isbaojiup=false;
		$isbaojiup=in_array($attack_info_package['type'],$baojiarr)?true:$isbaojiup;
		$isbaojiup=in_array('no'.$upname,$attack_info_package['other'])?false:$isbaojiup;//判断是否指定不结算暴击
		$isbaojiup=in_array($upname,$attack_info_package['other'])?true:$isbaojiup;//判断是否指定必定结算暴击

		//暴击结果数据记录
		$baoji_result=[];
		
		if($isbaojiup){
			//判定为可暴击伤害 结算暴击

			//暴击伤害
			$baoshang=0;
			if(isset($attack_info_package['attributechange']['baoshang'])){
				$baoshang=$attack_info_package['attributechange']['baoshang']['value']+$attack_info_package['attributechange']['baoshang']['p']*$attacker->getAttrValue('baoshang')/100+$attacker->getAttrValue('baoshang');
			}else{
				$baoshang=$attacker->getAttrValue('baoshang');
			}

			//暴击率
			$baoji=0;
			if(isset($attack_info_package['attributechange']['baoji'])){
				$baoji=$attack_info_package['attributechange']['baoji']['value']+$v['attributechange']['baoji']['p']*$attacker->getAttrValue('baoji')/100+$attacker->getAttrValue('baoji');
			}else{
				$baoji=$attacker->getAttrValue('baoji');
			}

			

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
			$baoji_result['up']=$baoshang<0?0:$baoshang;//暴伤
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
		}

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
	public function qita_jiacheng_jiesuan($attacker,$attack_info_package){
		$up_result=[];
		$paichu=['wushang','fashang','baoshang'];//排除已结算的项
		foreach ($attack_info_package['valuechange'] as $k => $v) {
			if(!in_array($k,$paichu)){
				$up_result[$k]=[
					'shengxiao'=>true,
					'up'=>$v['p'],
				];
			}
		}
		return $up_result;
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