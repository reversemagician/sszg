<?php
namespace App\myclass\sszg\role;

//受到攻击
trait attack{

	// 进攻		
	protected function attack($info){
		$info['releaser']=$this->role['id'];
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

		//引用监听$attack_info参数
		$this->beforeUnderattack($attack_info);
		
		//受到伤害
		$hurt=[
			'main'=>[
				'value'=>$attack_info['attack_info']['main']['bacevalue']
			]
			];
		
		// 引用监听$hurt参数
		$this->beforeHurt($hurt,$attack_info);

		//执行伤害
		$hurt_result = $this->hurt($hurt);
		
		$attack_info['hurt_result']=$hurt_result;

		//监听
		$this->afterUnderattack($attack_info);

		return $attack_info;
	}
	
}



?>