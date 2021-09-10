<?php
namespace App\myclass\sszg\qipan;

/**
 * 棋盘角色
 */
trait addRole
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

	//根据条件获取一个角色
	public function getRoleByWhere($where='')
	{
		foreach ($this->role as $k => $v) {
			
			if ($where=='fristone') {
				return $v;
			}
			if($where=='fristteam0'){
				if ($v->getAttrString('team')==0) {
					return $v;
				}
			}
			if($where=='fristteam1'){
				if ($v->getAttrString('team')==1) {
					return $v;
				}
			}
		}
		return null;
	}
}
?>