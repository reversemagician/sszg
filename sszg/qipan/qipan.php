<?php
namespace App\myclass\sszg\qipan;

use App\myclass\sszg\qipan\roundRunning;//回合运行器
use App\myclass\sszg\qipan\roleAction;//角色行动器
use App\myclass\sszg\qipan\addRole;//棋盘添加角色
use App\myclass\sszg\qipan\ob\ob;//棋盘添加角色

/**
* 棋盘类
*/
class qipan
{
	use roundRunning,roleAction,addRole,ob;

	
	private $onlyid=1;//全局唯一标记


	private $tool=[
        'ordinary'=>'App\myclass\sszg\qipan\tool\ordinary',//常用工具类集合
        'buff'=>'App\myclass\sszg\buff\buff',//buff类
        'target'=>'App\myclass\sszg\qipan\tool\target',//通用目标器
	];//工具

	function __construct()
	{
		$this->inti();
	}

	//初始化
	private function inti(){

		//ob初始化
		$this->obInti();
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
}

?>