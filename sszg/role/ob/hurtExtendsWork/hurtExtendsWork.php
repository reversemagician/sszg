<?php 
namespace App\myclass\sszg\role\ob\hurtExtendsWork;

 /**
 * 伤害扩展结算
 */
 class hurtExtendsWork
 {

	private $work=[
		'up'=>[//加成计算类
			['App\myclass\sszg\role\ob\hurtExtendsWork\upWork','getResult']
		],
		'down'=>[//防御计算类
		],
	];

	private $attacker='';
	private $target='';

 	public function getResult($info,$self,$ob_key){
 		$qipan=$self->qipan;
 		$info =$this->analysis($info,$qipan);
 		
 		return $info;
 	}

 	//解析
 	private function analysis($info,$qipan){

 		$this->attacker=$qipan->getRole($info['releaser']);
 		$this->target=$qipan->getRole($info['target']);

 		foreach ($info['attack_info'] as $k => $v) {

			//攻击包处理
			// 伤害计算
			$info['attack_info'][$k] = array_merge($v,$this->hurtWork($v)) ;
			
		}

		return $info;
 	}

 	// 攻击包处理
 	private function hurtWork($pack){

 		$arr['bace']=$pack['bacevalue'];//基础伤害
	
		//计算加成
		$arr['up'] = $this->Work('up',$this->attacker,$pack);
		
		//计算防御
		$arr['down']=$this->Work('down',$this->target,$pack);
		
		//结算伤害值
		$val =  $this->settlement($arr);

		return [ 'hurt_value'=> $val,'hurt_info'=>$arr];
 	}
 	// buff包处理
 	private function hasBuff($v){
 		
 	}


	private function Work($key,$role,$attack_info){
		$up=[];
		foreach ($this->work[$key] as $k => $v) {
			if(!is_object($v[0])) {$v[0]= new $v[0]; $this->upwork[$k][0]=$v[0];}
			$up =array_merge($up,call_user_func_array([$v[0],$v[1]],[$role,$attack_info])) ;

		}
		return $up;
	}

	//结算伤害
	private function settlement($arr){
		
		$count=$arr['bace'];
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
	// private function attack_bace($info,$qipan){

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