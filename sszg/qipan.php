<?php
namespace App\myclass\sszg;

/**
* 棋盘类
*/
class qipan
{
	
	private $role=[];
	private $xibie=[];//系别克制效果

	function __construct()
	{
		
	}

	//初始化
	private function inti(){

	}

	// 添加系别克制内容
	public function xibie($arr){
		$this->xibie=$arr;
	}

	//添加多个角色
	public function addRoles($arr){
		$this->role=array_merge($this->role,$arr);
	}

	// 添加一个角色
	public function addRole($role){
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

}

?>