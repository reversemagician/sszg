<?php
namespace App\myclass\sszg\role;

//角色通用基本方法
trait action{
	public $action=[
			'role_id'=>'',//ID
			'action_id'=>1,
			'action_type'=>'',//none未行动|skill1技能1|skill2技能2|putong普通攻击
			'action_class'=>'',//wuliattack|mofaattack|nai|buff
			'action_info'=>[],//行动详细
			'extends'=>[],//扩展参数
		];

	
	//回合开始
	public function round(){

		//监听
		$this->beforeRound($this);

		//开始行动
		$this->action();
		
		// 监听
		$this->afterRound($this->action);

		return  $this->action;
	}

	//行动数据初始化
	protected function actionInti(){
		$this->action['role_id']=$this->getAttrString('id');
		$this->action['action_id']=$this->qipan->getOnlyId();
	}

	//行动阶段
	public function action($action_type=''){

		if($this->qipan->is_game_over()){
			return false;
		}

		$this->actionInti();//行动数据初始化
		
		$action_type =$action_type==''?$this->getActionType():$action_type;

		//行动装饰
		$this->beforeAction($action_type);
		
		$this->action['action_type']=$action_type;
		if($action_type=='none'){return false;} //行动终止
		
		$this->actioning($action_type);

		//行动装饰
		$this->afterAction($this->action);
	}

	// 获取行动方式 
	protected function getActionType(){
		$round=$this->qipan->getRound();

		if($round%4==1){
			return 'skill1';
		}elseif ($round%4==2) {
			return 'skill2';
		}else{
			return 'putong';
		}
	}

	//行动类型
	public function actioning($action_type){
		//释放一技能
		if($action_type=='skill1'){
			$this->skill1();
		}

		if($action_type=='skill2'){
			$this->skill2();
		}

		if($action_type=='putong'){
			$this->putong();
		}
	}
	
}



?>