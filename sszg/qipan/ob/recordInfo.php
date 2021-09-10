<?php
namespace App\myclass\sszg\qipan\ob;

/**
 * 
 */
class recordInfo 
{

	private $roles=[];//阵容
 	private $fight_info=[];//全部战斗信息
 	private $this_round_info=[];//当前角色回合信息
 	private $round=0;//当前回合

 	private $new='';//播报信息

 	public function getInfo(){
 		return $this->fight_info;
 	}

 	public function getRoles(){
 		return $this->roles;
 	}

	public function obRoundStart($info,$self,$key)
	{
		$round=$self->getRoundInfo();
		$this->round=$round['round'];
		$this->new.="第{$round['round']}回合开始：<br>";
	}

	public function obGameStart($info,$self,$key)
	{
		$this->new.='游戏开始<br>'; 
		$key='recordInfo';
		$roles =$self->getAllRoles();

		foreach ($roles as  $v) {
			$this->roles[$v->getAttrString('team')][$v->getAttrString('position')]=[
				'id'=>$v->getAttrString('id'),
				'name'=>$v->getAttrString('name'),
				'h_max'=>$v->getAttrString('h_max'),
				'position'=>$v->getAttrString('position'),
				
			];

			$v->addObAll($this,$key);
		}

	}

	public function obGameEnd($info,$self,$key)
	{
		$this->new.='游戏结束，正在结算<br>';
	}

	public function obRoundEnd($info,$self,$key)
	{
		$round=$self->getRoundInfo();
		$this->new.="第{$round['round']}回合结束<br><br><br>";
	}

	public function beforeAttack($info,$self,$ob_key){

	   	$qipan=$self->qipan;

	   	$role=$qipan->getRole($info[0]['releaser']);

	   	$this->this_round_info['attacker']=[
	   		'id'=>$role->getAttrString('id'),
	   		'name'=>$role->getAttrString('name'),
	   		'skill'=>$role->{$role->action['action_type']}['name'],
	   		'skill_id'=>$role->{$role->action['action_type']}['id'],
	   	];

	   	return $info;
    }

 	public function afterUnderattack($info,$self,$ob_key){

    	$qipan=$self->qipan;

	   	$role=$qipan->getRole($info['target']);

	   	$arr=[
	   		'name'=>$role->getAttrString('name'),
	   		'id'=>$role->getAttrString('id'),
	   	];
	   	foreach ($info['attack_info'] as $key => $value) {

	   		if($value['hurt'][0]!=0){

	   			$type='';
	   			if($value['type']=='wushang'){
					// $type='<span style="color:#FFB800">物理</span>';
					$type='物伤';
					$color='#FFB800';
	   			}
	   			if($value['type']=='fashang'){
					// $type='<span style="color:#FFB800">物理</span>';
					$type='法伤';
					$color='#1E9FFF';
	   			}
	   			if($value['type']=='zhenshang'){
					$type='<span style="color:#666">真伤</span>';
					$color='#2F4056';
	   			}

	   			if(isset($value['hurt_info']['up']['baoshang'])){
	   				
	   				if ($value['hurt_info']['up']['baoshang']['shengxiao']==1) {
	   					$type='暴击';
	   					$color='#FF5722';
	   				}
	   			}
	   			$arr['hurt']='<span style="color:'.$color.'">'.$value['hurt'][0].'</span>';
	   			
	   		}
	   		
	   	}
	   	$this->this_round_info['targets'][]=$arr;

	   	return $info;
   }

   	public function  afterRound($info){
		if(!empty($this->this_round_info)){
			$this->fight_info[$this->round][]=$this->this_round_info;
		}
   		$this->this_round_info=[];

   		return $info;
   	}

   	public function	ObRoundLevel2(){
   		$this->new.="英雄行动阶段<br>";
  	}
}
?>