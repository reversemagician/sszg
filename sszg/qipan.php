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
        'defenseWork'=>'App\myclass\sszg\tool\defenseWork',//防守结算
        'ordinary'=>'App\myclass\sszg\tool\ordinary',//常用工具类集合
        'buff'=>'App\myclass\sszg\buff\buff',//buff类
        'target'=>'App\myclass\sszg\tool\target'
	];//工具

	function __construct()
	{
		$this->inti();
	}

	//初始化
	private function inti(){
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
			//实例化工具
			if(!is_object($this->tool[$toolname])){
				$this->tool[$toolname]=new $this->tool[$toolname];
			}
			//返回工具
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
	public function getRoles($where){
		if ($where=='all') {
			return $this->role;
		}
	}	

	//获取唯一标记id
	public function getOnlyId(){
		return $this->onlyid++;
	}

	//获取buff的统一格式
	public function getBuff($arr=[]){

		$buff=[    
			'id'=>0,//唯一标识
			'releaser'=>'roleid',//释放者id
	        'name'=>'',//buff名 同名buff一般仅生效最高效果
	        'bufftype'=>'buff',//增益 减益
	        'type'=>'attrup',// attrup|attrdown|other|{'chenmo','xuanyun'}|''   属性提升|属性降低|其他(或复合型)|其他类型
	        'turn'=>1,//持续回合
	        'ceng'=>1,//层数
	        'diejia'=>'none',//叠加类型 name|ceng|none  同名叠加|层数叠加|无叠加
	        'attrchange'=>[],//属性改变效果 属性增益或属性减益
	        'other'=>[
            	// 'noqusan',//不可驱散
            	
	        ],
        ];

		return array_merge($buff,$arr);
	}

	
}

?>