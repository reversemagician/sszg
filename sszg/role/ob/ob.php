<?php
namespace App\myclass\sszg\role\ob;

// ob并装饰
trait ob{

	private $ob_set=[//配置
		'default_order'=>50,//默认执行顺序 1-100
		'default_paichi'=>'',//默认排斥
	];

	private $ob_all=[//全监听
		// 'news'=>'App\myclass\sszg\role\ob\other\news',//战斗新闻播报
	];

	private $ob=[//标准监听
	'beforeUnderattack'=>[//受到攻击 结算之前
			// 'xibiekezhi'=>['对象','方法','执行顺序','排斥 同名排斥'],
			'xibiekezhi'=>['App\myclass\sszg\role\ob\other\xibie','getResult',100],//系别克制加成
			
		],
	'beforeAction'=>[//行动之前
	],
	'beforeHurt'=>[//执行伤害之前
			['App\myclass\sszg\role\ob\hurtExtendsWork\hurtExtendsWork','getResult']//各类伤害加成、伤害减免计算
		],
	'beforeAttack'=>[//出手攻击之前
			
		],
	'afterAttack'=>[//出手攻击之后
		],
	'beforeAttacking'=>[//攻击指定目标

		],
	'afterUnderattack'=>[//受到攻击之后
		],
	'beforeChangeH'=>[//改变血量之前
			
		],
	'beforeChangeH'=>[//改变血量之后
			
		],
	];

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

	/**
	 * 挂载和卸载ob 
	 *@param string $ob_key 监听阶段key 
	 *@param object $obj 具体监听实例 
	 *@param string $func 实例中的监听方法 
	 *@param int $time 执行次数  大于0|小于等于0代表不计数 
	 *@param string $unload_ob_key 在何阶段卸载该监听 优先级大于$time 当该值有效时 $time将有可能失效
	 *@return array $ob_load_info 二维数组 [['目标阶段(ob_key)','别名(ob_nickname)'],['卸载阶段(ob_key)','别名(ob_nickname)']]
	 */
	public function loadingUnloadOb($ob_key,$obj,$func,$number,$unload_ob_key=''){
		//出手顺序
		$order=$this->ob_set['default_order'];
		//挂载信息
		$ob_info=[
			'load'=>[
				'ob_key'=>$ob_key,
				'nickname'=>'load_ob_nickname'.$this->qipan->getOnlyId(),//别名	
			],
		];	
		
		//挂载 
		$this->addOb($ob_info['load']['ob_key'],$ob_info['load']['nickname'], $obj,$func,$order);

		//挂载 计数方法
		if ($number>0) {
			$ob_info['number']=[
				'ob_key'=>$ob_key,//计数的key
				'nickname'=>'number_ob_nickname'.$this->qipan->getOnlyId(),//别名
				'number'=>$number,
			];
			//挂载 计数
			$this->addOb($ob_info['number']['ob_key'],$ob_info['number']['nickname'] ,$obj,'obNumber',$order+1);

		}
		
		//挂载 卸载方法
		if ($unload_ob_key!='') {
			$ob_info['unload']=[
				'ob_key'=>$unload_ob_key,
				'nickname'=>'unload_ob_nickname'.$this->qipan->getOnlyId(),//别名
			];
			//挂载 卸载方法
			$this->addOb($ob_info['unload']['ob_key'],$ob_info['unload']['nickname'], $obj,'unloadFunc',$order+2);
		}

		if (isset( $ob_info['unload'])) {

			$this->unload_info[$ob_info['unload']['ob_key']][]=$ob_info;
		}else{
			$this->unload_info['no_unload'][]=$ob_info;
		}

		return $ob_info;
	}

	//计数方法
	private function obNumber($info,$self,$ob_key){
		$unload_info=$this->unload_info;

		foreach ($unload_info as $k => $v) {
			foreach ($v as $key => $value) {
				if(isset($value['number']) ){
					if ($value['number']['ob_key']==$ob_key) {
						
						$this->unload_info[$k][$key]['number']['number']--;

						if ($this->unload_info[$k][$key]['number']['number']<=0) {
							//卸载 
							 foreach ($value as $vv) {
							 	$this->deleteOb(['key'=>$vv['ob_key'],'nickname'=>$vv['nickname']]);
							 }

							//清理$this->unload_info
							unset($this->unload_info[$k][$key]) ;
						}
					}
				}
			}
		}
		return $info;
	}
	//卸载方法
	private function unloadFunc($info,$self,$ob_key){

		//卸载 
		if (isset($this->unload_info[$ob_key])) {
			foreach ($this->unload_info[$ob_key] as $k => $v) {
				
				foreach ($v as $key => $value) {
					$this->deleteOb(['key'=>$value['ob_key'],'nickname'=>$value['nickname']]);
				}
			}

			//清理$this->unload_info
			unset($this->unload_info[$ob_key]);
		}

		return $info;
	}

	//回合开始之前 包括回合开始
	private function beforeRound($role_obj){

	}

	//回合开始之后
	private function afterRound(&$info){
		foreach ($this->getObs(__FUNCTION__) as $k => $v) {
			
			$info =call_user_func_array([$v[0],$v[1]],[$info,$this,__FUNCTION__]);

		}
	}

	//行动之前
	private function beforeAction($action_type){

	}

	//行动之后
	private function afterAction($action_type){
		
	}

	//攻击之前
	private function beforeAttack(&$info){

		foreach ($this->getObs(__FUNCTION__) as $k => $v) {

			$info =call_user_func_array([$v[0],$v[1]],[$info,$this,__FUNCTION__]);

		}

		if($info==null){return false;}
			
	}

	//攻击之后
	private function afterAttack($result){

		foreach ($this->getObs(__FUNCTION__) as $k => $v) {

			$info =call_user_func_array([$v[0],$v[1]],[$result,$this,__FUNCTION__]);

		}

	}

	//攻击目标之前
	private function beforeAttacking(&$info)
	{
		foreach ($this->getObs(__FUNCTION__) as $k => $v) {

			$info =call_user_func_array([$v[0],$v[1]],[$info,$this,__FUNCTION__]);

		}

	}

	//攻击目标之后
	private function afterAttacking($info)
	{
		
	}


	//受到攻击 执行结算之前
	private function beforeUnderattack(&$info){
		
		foreach ($this->getObs(__FUNCTION__) as $k => $v) {

			$info =call_user_func_array([$v[0],$v[1]],[$info,$this,__FUNCTION__]);

		}
	}



	// 受到伤害之后
	private function afterUnderattack($info){
		
		foreach ($this->getObs(__FUNCTION__) as $k => $v) {

			$info =call_user_func_array([$v[0],$v[1]],[$info,$this,__FUNCTION__]);

		}
	}

	//结算伤害之前 扩展攻击结算 
	private function beforeHurt(&$info){
	
		foreach ($this->getObs(__FUNCTION__) as $k => $v) {
			
			$info =call_user_func_array([$v[0],$v[1]],[$info,$this,__FUNCTION__]);

		}
		
	}

	private function beforeDownH(&$value)
	{
		foreach ($this->getObs(__FUNCTION__) as $k => $v) {
			
			$value =call_user_func_array([$v[0],$v[1]],[$value,$this,__FUNCTION__]);

		}
	}

	private function afterDownH(&$value)
	{

		foreach ($this->getObs(__FUNCTION__) as $k => $v) {
			
			$value =call_user_func_array([$v[0],$v[1]],[$value,$this,__FUNCTION__]);

		}
	}

}