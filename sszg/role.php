<?php  
namespace App\myclass\sszg;


interface role{
	// private function skill1($target);//主动
	// private function skill2();//被动
	// private function skill3();//主动
	// private function skill4();//被动
	// public function useSkill1();//使用1技能
	// public function useSkill3();//使用3技能
	// public function attacking();//使用普通攻击
	// private function stageOfThebattlebegins();//整盘战斗开始阶段
	// private function stageOfEndTheBattle();//整盘战斗结束阶段
	// private function firstStageOfTheRound();//每回合第一阶段	回合开始阶段
	// private function secondStageOfTheRound();//每回合第二阶段	神器阶段
	// private function thirdStageOfTheRound();//每回合第三阶段	精灵阶段
	// private function fourthStageOfTheRound();//每回合第四阶段	英雄行动阶段
	// private function fifthStageOfTheRound();//每回合第五阶段	回合结束阶段

	// private function action();//每回合行动阶段
	// private function attack($attack_info);//攻击行为
	// private function underAttack($attack_info);//受到攻击
	// private function treatment();//治疗行为
	// private function getTreatment();//受到治疗
	// private function death();//死亡
	// private function resurrection();//复活
	// private function getStatus();//获得状态
	// private function putStatus();//失去状态
	// private function getBuff();//获得增益状态
	// private function getDebuff();//获得减益状态
	// private function valueChange();//值改变

}

/*技能基本参数*/
/*
基本参数
类型 			$class= attack 【攻击】 | buff 【添加buff】| treatment 【治疗】；  

$target=[//目标选择
			'target_z'=>2,
			'target_rang_priority'=>[[11,12,13]], 
			'target_n'=>2,
			'target_s'=>'random',
		];		
	目标阵容 			'target_z'=1 【我方】|2 【敌方】|0 【双方】;  
	目标范围优先级 	'target_rang_priority'=
					{
						{
						12,13,
						22,23,
						32,33,
						},
						{
						11,12,13,
						21,22,23,
						31,32,33,
						}
					}; 
	目标数			'target_n'=0;代表有效范围内目标数
	选择目标方式  	'target_s'=  random 【随机】| 
							minh 【血量最低】| maxh【血量最高】 |
							mina 【攻击最低】 | maxa【攻击最高】|
							mind 【防御最低】 | maxd【防御最高】|
							mins 【速度最低】 | maxs【速度最高】


 */

?>