<?php
namespace App\myclass\sszg\ob;

// ob并装饰
trait ob{

	private $i='nihao';

	private $ob=[
	'beforeAttack'=>[//监听阶段
			'xibiekezhi'=>['App\myclass\sszg\ob\xibie','getResult'],//系别克制加成监听
		],
	'beforeAction'=>[]
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
	public function beforeAction($action_type,$self,$qipan){
		$obkey='beforeAction';
		foreach ($this->getOb($obkey) as $k => $v) {

			$action_type =call_user_func_array([$v[0],$v[1]],[$action_type,$self,$this->qipan]);

		}
		return $action_type;
	}

	//行动之后
	public function afterAction($action_type,$self){

	}
	//攻击之前
	public function beforeAttack(&$attack_info){
		$obkey='beforeAttack';


		foreach ($this->getOb($obkey) as $k => $v) {

			$attack_info =call_user_func_array([$v[0],$v[1]],[$attack_info,$this->qipan]);

		}

		if($attack_info==null){return false;}
			
	}
	//攻击之后
	public function afterAttack($releas_info,$target){
		
	}

	//回合开始
	// public function beforeround()
}