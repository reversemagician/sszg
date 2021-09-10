<?php
namespace App\myclass\sszg\role;

//受到攻击
trait attack{

	// 进攻		
	protected function attack($info){
		
		//重构 攻击信息包
		$info =$this->remarkAttackInfo($info);

		//攻击装饰 引用监听$info
		$this->beforeAttack($info);
		
		//攻击结果
		$attack_result=[];

		//攻击分发
		foreach ($info as  $v) {
			//执行攻击中
			$this->beforeAttacking($v);
			$v=$this->qipan->getRole($v['target'])->underattack($v);
			$this->afterAttacking($v);
			$attack_result[]=$v;
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
		$attack_info['attack_info'][0]['hurt_value']=$attack_info['attack_info'][0]['bacevalue'];
		
		// 引用监听$hurt参数
		$this->beforeHurt($attack_info);

		//执行伤害
		$attack_info['attack_info'] = $this->hurt($attack_info['attack_info']);

		//监听
		$this->afterUnderattack($attack_info);

		return $attack_info;
	}
	
	//重构攻击信息包 并增加一些必要信息
	protected function remarkAttackInfo($info){
		$new_info=[];
		$releaser=$this->getAttrString('id');

		if(isset($info['target'])){
			if(is_array($info['target'])){
				$targets=$info['target'];
				$info['releaser']=$releaser;
				foreach ($targets as $k => $v) {
					$info['target']=$v;
					$new_info[]=$info;
				}
			}
		}else{
			foreach ($info as $key => $value) {
				if (is_array($value['target'])) {
					$targets=$info['target'];
					$value['releaser']=$releaser;
					foreach ($targets as $k => $v) {
						$value['target']=$v;
						$new_info[]=$value;
					}
				}else{
					$value['releaser']=$releaser;
					$new_info[]=$value;
				}
			}
		}

		return $new_info;
	}
}



?>