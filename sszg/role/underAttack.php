<?php
namespace App\myclass\sszg\role;

//受到攻击
trait underAttack{

	// 进攻		
	protected function attack($info){

		//攻击装饰 引用监听$info
		$this->beforeAttack($info);

		
		$attack_result=[];
		$targets=$info['target'];
		foreach ($targets as $k => $v) {
			//调用目标的 受到攻击接口
			$attack_info=$info;
			$attack_info['target']=$v;
			$attack_result[]=$this->qipan->getRole($v)->underattack($attack_info);
		}

		//攻击结束装饰
		$this->afterAttack($attack_result);
		
		$this->action['action_info'][]=$attack_result;

		return $attack_result;
	}
	
	//受到攻击 攻击解析
	public function underattack($attack_info){

		// print_r($attack_info);die;

		//引用监听$attack_info参数
		$this->beforeUnderattack($attack_info);

		//攻击包类型
		$attack_rang=['wushang','fashang','zhenshang'];

		//buff包类型
		$buff_rang=['buff','debuff'];

		$attacker=$this->qipan->getRole($attack_info['releaser']);
		

		$hurt=[
			'main'=>[
				'value'=>$attack_info['attack_info']['main']['bacevalue']
			]
			];//受到伤害
		foreach ($attack_info['attack_info'] as $k => $v) {

			//攻击处理
			if(in_array($v['type'],$attack_rang)){
				// 伤害计算
				$hurt[] = $this->underAttackWork($v,$attacker);
			}

			//buff处理
			if(in_array($v['type'],$buff_rang)){
				$this->isGetBuff($v,$attacker);//buff概率生效判定
			}

		}



		print_r($hurt);die;

		$this->beforeHurt($attack_info);

		//执行伤害
		$hurt = $this->hurt($hurt);
		
		$attack_info['hurt_result']=$hurt;

		$attack_info = $this->afterUnderattack($attack_info);

		return $attack_info;
	}

	//受到攻击 攻击信息结算
	public function underAttackWork($attack_info,$attacker){

		$arr=['value'=>$attack_info['bacevalue']];//value为伤害值

		//监听 扩展攻击结算
		$arr=$this->attackWork($arr,$attack_info,$attacker,$this);

		return $arr;

	}

	
}



?>