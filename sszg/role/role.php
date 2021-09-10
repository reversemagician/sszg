<?php
namespace App\myclass\sszg\role;
use App\myclass\sszg\role\ob\ob;
use App\myclass\sszg\role\action;
use App\myclass\sszg\role\makeTarget;
use App\myclass\sszg\role\attack;
use App\myclass\sszg\role\buff;


/*
角色类
 */
class role{
	use ob,action,makeTarget,attack,buff;
	public $role=[];

	public $qipan='';
	//初始化时执行的自身方法
	protected $inti=[
		'obInti',//ob类初始化
		'extendInti',//用于备用扩展inti方法
	];

	//备用扩展
	protected function extendInti(){}

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

	
	//加入棋盘
	public function qipan($qipan){
		$this->qipan=$qipan;
	}

	//角色死亡
	private function roleDeath(){

	}

	//生命值增加
	public function upH($value){
		return $this->changeH($value);
	}

	//生命值减少
	public function downH($value){
		return $this->changeH(-$value);
	}

	//血量改变
	public function changeH($value){

		$value=intval($value);

		$this->role['h']=$this->role['h']+$value;
		
		//最高生命值为h_max 
		$this->role['h']=$this->role['h']>=$this->role['h_max']?$this->role['h_max']:$this->role['h'];

		// 最低为0
		$this->role['h']=$this->role['h']<=0?0:$this->role['h'];

		if($this->role['h']==0){
			$this->roleDeath();
		}

		return [$value,$this->role['h']];
	}

	// 执行伤害
	private function hurt($arr){

		foreach ($arr as $k => $v) {
			
			$this->beforeDownH($v);

			$v['hurt'] = $this->downH($v['hurt_value']);

			$this->afterDownH($v);

			$arr[$k]=$v;
		}

		return $arr;
	}

	//获取属性值 $rang指定获取范围
	public function getAttrValue($attr,$rang=[]){
		$name=$attr;
		
		//buff属性
		$buff=[];
			
			foreach ($this->role['buff'] as $k => $v) {
				$value=0;
				if(isset($v['attrchange'][$attr])){
					//固定值
					$val=isset($v['attrchange'][$attr]['value'])?$v['attrchange'][$attr]['value']:0;
					//基础百分比值
					$p=isset($v['attrchange'][$attr]['p'])?$v['attrchange'][$attr]['p']*$this->role[$name]/100:0;
					$value=($val+$p)*$v['ceng'];
				}
				
				if($v['diejia']=='none'){
					$buff[$v['name']]=isset($buff[$v['name']])?($buff[$v['name']]>$value?$buff[$v['name']]:$value):$value;
				}else{
					$buff[]=$value;
				}
			}


		//临时属性
		$linshi=[];
		foreach ($this->role['linshiattr'] as $k=> $v) {
			if($v['type']==$name){
				$linshivalue=$v['value']+$v['value_p']*$this->role[$name]/100;
				$linshi[]=$linshivalue;
			}
		}


		if(empty($rang)){
			// 返回总属性
			return array_sum($buff)+array_sum($linshi)+(isset($this->role[$name])?$this->role[$name]:0);
		}else{
			$result =0;
			$result+=in_array('buff',$rang)?array_sum($buff):0;
			$result+=in_array('linshi',$rang)?array_sum($linshi):0;
			$result+=in_array('bace',$rang)?(isset($this->role[$name])?$this->role[$name]:0):0;
			return $result;
		}

	}

	// 获取字符串属性
	public function getAttrString($name){
		return isset($this->role[$name])?$this->role[$name]:null;
	}

	//获取对应行动的目标 $type 'skill1'|'skill2'|'putong'
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

	public function skill1(){echo "需要实现".__CLASS__.':'.'()';die;}
	public function skill2(){echo "需要实现".__CLASS__.':'.__FUNCTION__.'()';die;}
	public function putong(){echo "需要实现".__CLASS__.':'.__FUNCTION__.'()';die;}

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
}



?>