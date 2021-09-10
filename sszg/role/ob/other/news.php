<?php
namespace App\myclass\sszg\role\ob\other;
// 新闻信息播报
class news{

    public function beforeAttack($info,$self,$ob_key){
	   	$qipan=$self->qipan;

	   	$role=$qipan->getRole($info[0]['releaser']);

	   	echo $role->getAttrString('name').'使用了'.$role->{$role->action['action_type']}['name'].'<br>';

	   	return $info;
    }

    public function afterUnderattack($info,$self,$ob_key){

    	$qipan=$self->qipan;

	   	$role=$qipan->getRole($info['target']);

	   	echo $role->getAttrString('name').'&nbsp;:';
	   	
	   	foreach ($info['attack_info'] as $key => $value) {

	   		if($value['hurt'][0]!=0){

	   			$type='';
	   			if($value['type']=='wushang'){
					// $type='<span style="color:#FFB800">物理</span>';
					$type='物理';
					$color='#FFB800';
	   			}
	   			if($value['type']=='fashang'){
					// $type='<span style="color:#FFB800">物理</span>';
					$type='法术';
					$color='#1E9FFF';
	   			}
	   			if($value['type']=='zhenshang'){
					$type='<span style="color:#666">真实</span>';
					$color='#2F4056';
	   			}

	   			if(isset($value['hurt_info']['up']['baoshang'])){
	   				
	   				if ($value['hurt_info']['up']['baoshang']['shengxiao']==1) {
	   					// $type.='暴击';
	   					$color='#FF5722';
	   				}
	   			}

	   			echo '&nbsp;受到'.$type.'伤害HP:<span style="color:'.$color.'">'.$value['hurt'][0].'</span>';
	   		}
	   		
	   	}

	   	echo '剩余血量：'.$role->getAttrValue('h').'<br>';

	   	return $info;
   }
	
}