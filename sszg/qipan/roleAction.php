<?php
namespace App\myclass\sszg\qipan;

/**
 * 角色行动器
 */
trait roleAction
{

	private $actioned_role=[];//已经行动过的角色
	private $actioning_role=''; //正在行动的角色ID
	private $is_overAction=false;//是否全部行动结束

	// 所有角色开始行动 执行入口
	public function roleAction(){
		$this->roleActionInti();
		$this->nextRoleAction();
		$this->is_overAction=false;
	}

	//初始化
	private function roleActionInti(){
		$this->actioning_role='';
		$this->actioned_role=[];
	}

	//当前角色行动中
	private function roleActioning(){
		$this->getRole($this->actioning_role)->round();// 执行角色行动
		$this->actioned_role();//标记已行动
		$this->nextRoleAction();//下一个角色行动
	}

	// 下一个角色行动
	private function nextRoleAction(){
		
		// 标记将要行动的角色
		$this->actioning_role();
		
		//是否全部行动结束
		if($this->is_overAction){
			$this->overAction();
			return false;
		}

		$this->roleActioning();
	}

	// 标记已经行动的角色
	private function actioned_role(){
		$this->actioned_role[]=$this->actioning_role;
	}

	// 标记一个合理的角色为行动状态
	private function actioning_role(){
		//目标工具筛选条件
		$where=[
			'rang'=>['life'],//存活的英雄
 			'where'=>'max_s',//速度最高
 			'number'=>1,//数量
 			'remove'=>$this->actioned_role,//排除的角色ID
		];

		//调用棋盘的工具类筛选行动目标
		$id =$this->useTool('target','getTargetId',[$where,$this]);
		
		//已经没有可行动角色
		if(empty($id)){
			$this->is_overAction=true;//标记所有行动结束
			return false;
		}

		//标记
		$this->actioning_role=$id[0];
	}

	//全部角色行动结束
	private function overAction(){

	}
}
?>