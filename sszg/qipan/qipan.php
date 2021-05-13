<?php
namespace App\myclass\sszg\qipan;

use App\myclass\sszg\qipan\roundRunning;//回合运行器
use App\myclass\sszg\qipan\roleAction;//角色行动器
use App\myclass\sszg\qipan\qipanRole;//棋盘添加角色

/**
* 棋盘类
*/
class qipan
{
	use roundRunning,roleAction,qipanRole;

	
	private $onlyid=1;//全局唯一标记


	private $tool=[
        'defenseWork'=>'App\myclass\sszg\tool\defenseWork',//防守结算
        'ordinary'=>'App\myclass\sszg\tool\ordinary',//常用工具类集合
        'buff'=>'App\myclass\sszg\buff\buff',//buff类
        'target'=>'App\myclass\sszg\tool\target'//通用目标器
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
			//使用工具，返回工具结果
			return call_user_func_array([$this->tool[$toolname],$funcname],$parameter);
		}else{
			// echo '工具不存在';
			return [];
		}
		
	}

	//获取唯一标记
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