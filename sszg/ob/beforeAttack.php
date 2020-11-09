<?php
namespace App\myclass\sszg\ob;

interface beforeAttack{
	/**
	 * getResult
	 *@param object $attack_info 攻击信息
	 *@param object $qipan 攻击信息
	 *@return $attack_info  攻击信息
	 */
	public function getResult($attack_info,$qipan);
}
?>