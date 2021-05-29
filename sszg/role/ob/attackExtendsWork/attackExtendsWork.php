<?php 
namespace App\myclass\sszg\role\ob\attackExtendsWork;

 /**
 * 攻击结算扩展
 */
 class attackExtendsWork
 {

	private $work=[
		'up'=>[//加成计算扩展类
			['App\myclass\sszg\role\ob\attackExtendsWork\attackWork','getResult']
		],
		'down'=>[//防御计算扩展类
		],
	];

 	public function getResult($arr,$attack_info,$attacker,$under_attacker){

 		$up=$this->underAttackWork($attack_info,$attacker,$under_attacker);
 		$up['value']+=$arr['value'];
 		return array_merge($arr,$up);
 	}

 	public function underAttackWork($attack_info,$attacker,$under_attacker){
		
		$arr['bace']=$attack_info['bacevalue'];//基础伤害
	
		//监听 计算加成
		$arr['up'] = $this->Work('up',$attacker,$attack_info);
		
		//监听 计算防御
		$arr['down']=$this->Work('down',$under_attacker,$attack_info);
		
		//结算伤害值
		$val =  $this->settlement($arr);

		return [ 'value'=> $val,'attack_type'=>$attack_info['type'],'info'=>$arr];

	}

	private function Work($key,$attacker,$attack_info){
		$up=[];
		foreach ($this->work[$key] as $k => $v) {
			if(!is_object($v[0])) {$v[0]= new $v[0]; $this->upwork[$k][0]=$v[0];}
			$up =array_merge($up,call_user_func_array([$v[0],$v[1]],[$attacker,$attack_info])) ;

		}
		return $up;
	}

	//结算伤害
	private function settlement($arr){
		
		$count=0;
		// 伤害加成
		foreach ($arr['up'] as $k => $v) {
			$count+=$v['shengxiao']==1?($v['p'])*$arr['bace']/100:0;
		}

		//伤害减值
		foreach ($arr['down'] as $k => $v) {

			if($v['shengxiao']==1){
				$down_val= isset($v['p'])?$v['p']*$arr['bace']/100:(isset($v['value'])?$v['value']:0);

				$count-=$down_val;
			}
			
		}

		return $count<0?0:$count;
	}	


	//攻击基本伤害
	// public function attack_bace($info,$qipan){

	// 	$bace=0;
	// 	foreach ($info['bacevalue'] as $k => $v) {
	// 		$one = explode('.',$k);

	// 		$hero=$qipan->getRole($one[0]);// $one[0]是英雄的识别id

			
	// 		$value=$hero->getAttrValue($one[1]);//角色值
	// 		$upvalue=isset($info['attributechange'][$one[1]])?$info['attributechange'][$one[1]]:0;//附带属性提升值


	// 		$bace=$bace+($value+$upvalue)*$v/100;
	// 	}
	// 	return $bace;
	// }
 	
 }

?>