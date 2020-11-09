<?php
namespace App\myclass\sszg;

// ob并装饰
trait ob{

	private $i='nihao';

	private $ob=[
	'beforeAttack'=>[
			'xibiekezhi'=>'App\myclass\sszg\ob\xibie',
		],
	];

	//初始化
	public function obInti(){

		//实例化装饰类
		foreach ($this->ob as $k => $v) {
			foreach ($v as $key => $value) {
				
				if(!is_object($value)) {$v[$key]= new $value;};
			}
			$this->ob[$k]=$v;
		}
	}

	//获取监听 $key 监听key
	public function getOb($key){
		return isset($this->ob[$key])?$this->ob[$key]:[]; 
	}
	//添加监听 $key监听key 具体ob对象
	public function addOb($key ,$key1, $ob){
		$this->ob[$key][$key1]=$ob;
	}

	//添加监听 $key监听key 具体ob对象
	public function deleteOb($key ,$key1=''){
		if($key1=''){
			unset($this->ob[$key]);
		}else{
			unset($this->ob[$key][$key1]);
		}
	}

	//装饰 行动之前
	public function beforeAction($action_type,$self){
	
	}
	//装饰 行动之后
	public function afterAction($action_type,$self){

	}
	//装饰 攻击之前
	public function beforeAttack($attack_info){
		$obkey='beforeAttack';


		foreach ($this->getOb($obkey) as $k => $v) {
			$attack_info=$v->getResult($attack_info,$this->qipan);
		}

		return $attack_info;
			
	}
	//装饰 攻击之后
	public function afterAttack($releas_info,$target){
		
	}
}