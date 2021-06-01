<?php
namespace App\myclass\sszg\role\ob;

// ob并装饰
trait ob{

	private $i='nihao';

	private $ob=[
	'beforeUnderattack'=>[//受到攻击之前
			'xibiekezhi'=>['App\myclass\sszg\role\ob\other\xibie','getResult'],//系别克制加成
		],
	'beforeAction'=>[//行动之前
	],
	'beforeHurt'=>[
			['App\myclass\sszg\role\ob\hurtExtendsWork\hurtExtendsWork','getResult']//各类伤害加成、伤害减免计算
		],
	'beforeAttack'=>[//出手攻击之前
			['App\myclass\sszg\role\ob\other\addInfo','getResult'],//增加额外的信息
			['App\myclass\sszg\role\ob\other\news','beforeAttack'],//新闻播报
		],
	'afterUnderattack'=>[//受到攻击之后
			['App\myclass\sszg\role\ob\other\news','afterUnderattack'],//新闻播报
		],
	];

	//初始化
	public function obInti(){

		//防止重复实例化
		$class=[];
		//实例化装饰类
		foreach ($this->ob as $k => $v) {
			
			foreach ($v as $key => $value) {
				
				if(!is_object($value[0])) { 

					$v[$key][0]=isset($class[$value[0]])?$class[$value[0]]:(new $value[0]); 

					$class[$value[0]]=$v[$key][0];

				};
			}
			$this->ob[$k]=$v;
		}

	}

	//获取监听 $key 监听阶段key
	public function getOb($key){
		return isset($this->ob[$key])?$this->ob[$key]:[]; 
	}

	/**
	 * 添加监听
	 *@param string $key 监听阶段key 
	 *@param string $key1 监听别名 
	 *@param object $obj 具体监听实例 
	 *@param string $func 实例中的监听方法 
	 */
	public function addOb($key ,$key1, $obj,$func){
		$this->ob[$key][$key1]=[$obj,$func];
	}

	//删除监听 $key0 
	public function deleteOb($arr){
		// $arr=[
		// 	'key0'=>'',//监听阶段key
		// 	'key1'=>'',//监听别名
		// ];

		if(isset($arr['key0'])&&!isset($arr['key1'])){
			unset($this->ob[$arr['key0']]);
		}elseif(isset($arr['key1'])&&!isset($arr['key0'])){
			foreach ($this->ob as $k => $v) {
				foreach ($v as $key => $value) {
					if($key==$arr['key1']){
						unset($this->ob[$k][$kye]);
					}
				}
			}
		}elseif (isset($arr['key1'])&&isset($arr['key0'])) {
			unset($this->ob[$arr['key0']][$arr['key1']]);
		}
	}

	//回合开始之前 包括回合开始
	private function beforeRound(){

	}

	//回合开始之后
	private function afterRound($info){
		return $info;
	}

	//行动之前
	private function beforeAction($action_type){

		foreach ($this->getOb(__FUNCTION__) as $k => $v) {

			call_user_func_array([$v[0],$v[1]],[]);

		}
	}

	//行动之后
	private function afterAction($action_type){
		
	}

	//攻击之前
	private function beforeAttack(&$attack_info){

		$releaser =$this->qipan->getRole($attack_info['releaser'])->getAttrString('name');

		foreach ($this->getOb(__FUNCTION__) as $k => $v) {

			$attack_info =call_user_func_array([$v[0],$v[1]],[$attack_info,$this->qipan]);

		}

		if($attack_info==null){return false;}
			
	}
	
	//受到攻击之前
	private function beforeUnderattack(&$attack_info){
		
		foreach ($this->getOb(__FUNCTION__) as $k => $v) {

			$attack_info =call_user_func_array([$v[0],$v[1]],[$attack_info,$this->qipan]);

		}
	}

	//攻击之后
	private function afterAttack($result){
		
	}

	// 受到伤害之后
	private function afterUnderattack($info){
		
		foreach ($this->getOb(__FUNCTION__) as $k => $v) {

			$attack_info =call_user_func_array([$v[0],$v[1]],[$info,$this->qipan]);

		}
	}

	//结算伤害之前 扩展攻击结算 
	private function beforeHurt(&$hurt,$attack_info){
		//去掉原有结算
		$hurt=[];

		foreach ($this->getOb(__FUNCTION__) as $k => $v) {
			
			$hurt =call_user_func_array([$v[0],$v[1]],[$hurt,$attack_info,$this->qipan]);

		}
		
	}
}