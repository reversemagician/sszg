<?php
namespace App\myclass\sszg\qipan;

/**
 * 信息记录
 */
trait recordInfo
{
	private $recorded_info=[];

	//获得记录
	public function getRecordInfo(){
		return $this->recorded_info;
	}

	// $info ==角色行动信息
	private function recordRoleActionInfo($info){
		$this->recorded_info['role'][$this->round_info['round']][]=$info;
	}

	// 受到伤害
	// function needInfo($info){
	// 	$arr=[];
	// 	foreach ($this->role as $k => $v) {
	// 		$arr['']
	// 	}
	// }
}
?>