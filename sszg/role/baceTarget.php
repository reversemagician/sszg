<?php
namespace App\myclass\sszg\role;

//角色通用基本方法
trait baceTarget{
	public $make_target=[];//指定目标

	//一技能目标 返回目标实例数组
	public function skill1Target(){
		echo "需要实现".__CLASS__.':'.__FUNCTION__.'()';die;
	}

	//二技能目标 返回目标实例数组
	public function skill2Target(){
		echo "需要实现".__CLASS__.':'.__FUNCTION__.'()';die;
	}

	//普通攻击目标 返回目标实例数组
	public function putongTarget(){
		echo "需要实现".__CLASS__.':'.__FUNCTION__.'()';die;
	}

	//$type 'skill1'|'skill2'|'putong'
	public function getTarget($type){
		if(isset($this->make_target[$this->action['action_id']])){
			$targets=[];
			foreach ($this->make_target[$this->action['action_id']] as $k => $v) {
				$targets[]=$this->getRole($v);
			}
			return $targets;
		}

		return $this->{$type.'Target'}();
	}

	// 指定当前行动回合的目标 $arr=['fengwang1','yemeng1']
	public function makeTarget($arr){
		$this->make_target[]=[
			$this->action['action_id']=>$arr
		];
	}	
	
}



?>