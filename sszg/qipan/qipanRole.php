<?php
namespace App\myclass\sszg\qipan;

/**
 * 棋盘角色
 */
trait qipanRole
{
	private $role=[];//棋盘上的角色

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


	//获取全部角色
	public function getAllRoles(){
		return $this->role;
	}

	//根据id获取角色
	public function getRole($id){
		foreach ($this->role as $k => $v) {
			if ($v->getAttrString('id')==$id) {
				return $v;
			}
		}
		return null;
	}
}
?>