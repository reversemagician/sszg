<?php  
namespace App\myclass\sszg;


interface role{
	// public function skill1($target);//主动
	// public function skill2();//被动
	// public function skill3();//主动
	// public function skill4();//被动
	// public function stageOfThebattlebegins();//整盘战斗开始阶段
	// public function stageOfEndTheBattle();//整盘战斗结束阶段
	// public function firstStageOfTheRound();//每回合第一阶段	回合开始阶段
	// public function secondStageOfTheRound();//每回合第二阶段	神器阶段
	// public function thirdStageOfTheRound();//每回合第三阶段	精灵阶段
	// public function fourthStageOfTheRound();//每回合第四阶段	英雄行动阶段
	// public function fifthStageOfTheRound();//每回合第五阶段	回合结束阶段

	// public function action();//每回合行动阶段
	// private function attack($attack_info);//攻击行为
	// public function underAttack($attack_info);//受到攻击
	// public function treatment();//治疗行为
	// public function getTreatment();//受到治疗
	// public function death();//死亡
	// public function resurrection();//复活
	// public function getStatus();//获得状态
	// public function putStatus();//失去状态
	// public function getBuff();//获得增益状态
	// public function getDebuff();//获得减益状态
	// public function valueChange();//值改变

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