<?php
namespace App\myclass\sszg\role;

//另外指定目标
trait makeTarget{
	public $make_target=[];//指定目标

	// 指定当前行动回合的目标 $arr=['fengwang1','yemeng1']
	public function makeTarget($arr){
		$this->make_target[]=[
			$this->action['action_id']=>$arr
		];
	}	
	
}



?>