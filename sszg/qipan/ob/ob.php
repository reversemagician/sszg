<?php
namespace App\myclass\sszg\qipan\ob;

// ob
trait ob{

	private $ob_set=[//配置
		'default_order'=>50,//默认执行顺序 1-100
		'default_paichi'=>'',//默认排斥
	];

	private $ob_all=[//全监听
		'recordInfo'=>'App\myclass\sszg\qipan\ob\recordInfo'//数据记录
	];

	private $ob=[];

	private $unload_info=[
	];

	//初始化
	public function obInti(){

		//防止重复实例化 同样的类只会实例化一次
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

		//实例化全监听类
		foreach ($this->ob_all as $k => $v) {
			$this->ob_all[$k]=new $v;
		}
	}

	/**
	 * 获取指定的通用监听
	 *@param string $key 监听阶段key 
	 *@param string $nickname 别名
	 *@return $obj $obj或空  
	 */
	function getOb($key,$nickname){
		if(isset($this->ob[$key])){
			if(isset($this->ob[$key][$nickname])){
				return $this->ob[$key][$nickname];
			}
		}
		return '';
	}

	/**
	 * 获取监听
	 *@param string $key 监听阶段key 
	 *@param string $model 该参数不为空则返回未经处理的原始通用ob 
	 *@return array $ob  
	 */
	public function getObs($key,$model=''){

		if ($model!='') {
			return isset($this->ob[$key])?$this->ob[$key]:[];
		}

		//通用监听
		$ob =isset($this->ob[$key])?$this->ob[$key]:[];

		//并入全监听
		foreach ($this->ob_all as $v) {
			if(method_exists($v, $key)){
				$ob[]=[$v,$key,$this->ob_set['default_order'],$this->ob_set['default_order']];
			};
		}

		//监听排序	
		if (!empty($ob)) {
			foreach ($ob as $k=> $v) {

				//默认排序
				$v[2]=isset($v[2])?$v[2]:$this->ob_set['default_order'];

				$order[$k]=$v[2];

				$ob[$k]['key']=$k;
			}

			array_multisort($order,SORT_ASC,$ob);
			
		}	
		
		return $ob; 
	}

	/**
	 * 添加监听
	 *@param string $key 监听阶段key 
	 *@param string $nickname 监听别名 
	 *@param object $obj 具体监听实例 
	 *@param string $func 实例中的监听方法 
	 */
	public function addOb($key ,$nickname, $obj,$func,$order=''){

		// 执行顺序
		$order=$order==''?$this->ob_set['default_order']:$order;

		$this->ob[$key][$nickname]=[$obj,$func,$order];
	}

	/**
	 * 添加全监听
	 *@param object $obj 具体监听实例 
	 *@param object $key 别名 
	 */
	public function addObAll($obj,$key=''){
		if ($key=='') {
			$this->ob_all[]=$obj;
		}else{
			$this->ob_all[$key]=$obj;
		}
		
	}
	//删除全监听 $key 
	public function deleteObAll($key){
		unset($this->ob_all[$key]);
	}

	//获取全监听 $key 
	public function getObAll($key){
		return $this->ob_all[$key];
	}

	//删除监听 $arr 
	public function deleteOb($arr){
		// $arr=[
		// 	'key'=>'',//监听阶段key
		// 	'nickname'=>'',//监听别名
		// ];

		if(isset($arr['key'])&&!isset($arr['nickname'])){
			unset($this->ob[$arr['key']]);
		}elseif(isset($arr['nickname'])&&!isset($arr['key'])){
			foreach ($this->ob as $k => $v) {
				foreach ($v as $key => $value) {
					if($key==$arr['nickname']){
						unset($this->ob[$k][$kye]);
					}
				}
			}
		}elseif (isset($arr['nickname'])&&isset($arr['key'])) {
			unset($this->ob[$arr['key']][$arr['nickname']]);
		}
	}

	//游戏开始
	private function obGameStart($info=''){
		foreach ($this->getObs(__FUNCTION__) as $k => $v) {
			
			$info =call_user_func_array([$v[0],$v[1]],[$info,$this,__FUNCTION__]);

		}
	}

	//游戏结束
	private function obGameEnd($info=''){
		foreach ($this->getObs(__FUNCTION__) as $k => $v) {
			
			$info =call_user_func_array([$v[0],$v[1]],[$info,$this,__FUNCTION__]);

		}
	}

	//单回合开始
	private function obRoundStart($info=''){
		foreach ($this->getObs(__FUNCTION__) as $k => $v) {
			
			$info =call_user_func_array([$v[0],$v[1]],[$info,$this,__FUNCTION__]);

		}
	}

	//单回合结束
	private function obRoundEnd($info=''){
		foreach ($this->getObs(__FUNCTION__) as $k => $v) {
			
			$info =call_user_func_array([$v[0],$v[1]],[$info,$this,__FUNCTION__]);

		}
	}

	//每回合的阶段2 英雄行动阶段
	private function ObRoundLevel2($info=''){
		foreach ($this->getObs(__FUNCTION__) as $k => $v) {
			
			$info =call_user_func_array([$v[0],$v[1]],[$info,$this,__FUNCTION__]);

		}
	}
	

}