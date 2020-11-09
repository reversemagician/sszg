<?php
namespace App\myclass\sszg;

/**
* 棋盘类
*/
class qipan
{

	private $onlyid=1;
	private $role=[];//棋盘上的角色

	private $tool=[
		'attackWork'=>'App\myclass\sszg\tool\attackWork',
        'defenseWork'=>'App\myclass\sszg\tool\defenseWork',
        'ordinary'=>'App\myclass\sszg\tool\ordinary',//常用工具类集合
	];//工具

	function __construct()
	{
		$this->inti();
	}

	//初始化
	private function inti(){
		
		//实例化工具类
		$this->inti_tool();
	}

	private function inti_tool(){
		foreach ($this->tool as $k => $v) {
				
			if(!is_object($v)) {
				$this->tool[$k]= new $v;
			};
		}
	}

	
	/**
	 * 使用工具
	 *@param object $toolname 工具名 
	 *@param object $funcname 工具方法名 
	 *@param object $parameter 参数 
	 *@return 结果
	 */
	public function useTool($toolname,$funcname,$parameter){
		if(isset($this->tool[$toolname])){
			return call_user_func_array([$this->tool[$toolname],$funcname],$parameter);
		}else{
			// echo '工具不存在';
			return [];
		}
		
	}

	//添加多个角色
	public function addRoles($roles){
		foreach ($roles as $k => $v) {
			$this->addRole($v);
		}
	}

	// 添加一个角色,
	public function addRole($role){
		$role->qipan($this);
		$this->role[]=$role;
	}

	//获取角色 $key指定$str对应的键
	public function getRole($str,$key='id'){
		
		foreach ($this->role as $k => $v) {
			
			if($str==$v->role[$key]){
				return $v;
			}
		}

		return null;
	}

	//获取多个角色
	public function getRoles($where,$key){

	}

	//获取唯一标记id
	public function getOnlyId(){
		return $this->onlyid++;
	}

	//获取buff的统一格式
	public function getBuff($arr){

		$buff=[    
			'releaser'=>'id',
	        'name'=>'',//buff名
	        'buffid'=>'',//同类型id相同仅生效一个效果最高的buff
	        'pid'=>'',//父级id
	        'type'=>'buff',//增益 减益
	        'turn'=>1,//持续回合
	        'value'=>0,//固定值
	        'value_p'=>0,//百分比
	        'other'=>[
            	// 'noqusan',//不可驱散
	        ]
        ];

		return array_merge($buff,$arr);
	}

	
}

?>