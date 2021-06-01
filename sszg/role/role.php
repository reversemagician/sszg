<?php
namespace App\myclass\sszg\role;
use App\myclass\sszg\role\ob\ob;
use App\myclass\sszg\role\bace;
use App\myclass\sszg\role\baceTarget;
use App\myclass\sszg\role\attack;
use App\myclass\sszg\role\buff;


/*
角色类
 */
class role{
	use ob,bace,baceTarget,attack,buff;
	public $role=[];
	

	public $action=[
			'role_id'=>'',//ID
			'action_id'=>1,
			'action_type'=>'',//none未行动|skill1技能1|skill2技能2|putong普通攻击
			'action_class'=>'',//wuliattack|mofaattack|nai|buff
			'action_info'=>[],//行动详细
			'extends'=>[],//扩展参数
		];

	public $qipan='';
	//初始化时执行的自身方法
	protected $inti=[
		'obInti',//ob类初始化
	];

	public function __construct($role_info){
		$this->role=$role_info;
		$this->inti();
	}

	//初始化
	protected function inti(){

		foreach ($this->inti as $k => $v) {
			call_user_func_array([$this,$v],[]);
		}
	}

	//行动数据初始化
	protected function actionInti(){
		$this->action['role_id']=$this->getAttrString('id');
		$this->action['action_id']=$this->qipan->getOnlyId();
	}


	//加入棋盘
	public function qipan($qipan){
		$this->qipan=$qipan;
	}

	

	//回合开始
	public function round(){

		//监听
		$this->beforeRound($this);

		//开始行动
		$this->action();
		
		// 监听
		$this->action = $this->afterRound($this->action);

		return $this->action;
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

	public function skill1(){echo "需要实现".__CLASS__.':'.'()';die;}
	public function skill2(){echo "需要实现".__CLASS__.':'.__FUNCTION__.'()';die;}
	public function putong(){echo "需要实现".__CLASS__.':'.__FUNCTION__.'()';die;}
}



?>