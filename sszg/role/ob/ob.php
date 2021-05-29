<?php
namespace App\myclass\sszg\role\ob;

// ob并装饰
trait ob{

	private $i='nihao';

	private $ob=[
	'beforeUnderattack'=>[//监听阶段
			'xibiekezhi'=>['App\myclass\sszg\role\ob\xibie','getResult'],//系别克制加成监听
		],
	'beforeAction'=>[],
	'attackWork'=>[
		['App\myclass\sszg\role\ob\attackExtendsWork\attackExtendsWork','getResult'],
	],
	];

	//初始化
	public function obInti(){

		//实例化装饰类
		foreach ($this->ob as $k => $v) {
			foreach ($v as $key => $value) {
				
				if(!is_object($value[0])) {$v[$key][0]= new $value[0];};
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

	//行动之前
	private function beforeAction($action_type){
		$obkey='beforeAction';
		foreach ($this->getOb($obkey) as $k => $v) {

			call_user_func_array([$v[0],$v[1]],[]);

		}
	}

	//行动之后
	private function afterAction($action_type,$self){
		print_r($action_type);die;
	}
	//攻击之前
	private function beforeAttack(&$attack_info){
		$obkey='beforeAttack';
		$releaser =$this->qipan->getRole($attack_info['releaser'])->getAttrString('name');
		echo $releaser.'出手攻击了';	
		foreach ($this->getOb($obkey) as $k => $v) {

			$attack_info =call_user_func_array([$v[0],$v[1]],[$attack_info,$this->qipan]);

		}

		if($attack_info==null){return false;}
			
	}
	//攻击之后
	private function afterAttack($result){
		
	}

	// 扩展攻击结算 
	private function attackWork($arr,$attack_info,$attacker,$under_attacker){
		$obkey='attackWork';
		foreach ($this->getOb($obkey) as $k => $v) {

			$arr =call_user_func_array([$v[0],$v[1]],[$arr,$attack_info,$attacker,$under_attacker]);

		}
		
		return $arr;
	}

	// 受到攻击之前
	private function beforeUnderattack(&$attack_info){

		$obkey='beforeUnderattack';
		
		foreach ($this->getOb($obkey) as $k => $v) {

			$attack_info =call_user_func_array([$v[0],$v[1]],[$attack_info,$this->qipan]);

		}
	}

	// 受到攻击之后
	private function afterUnderattack($info){

		
		$target=$this->qipan->getRole($info['target']);
		echo $target->getAttrString('name').'剩余血量：'.$target->getAttrString('h').'<br>';
		return $info;
	}

	//回合开始
	private function beforeRound(){

	}

	private function afterRound($info){
		return $info;
	}
}