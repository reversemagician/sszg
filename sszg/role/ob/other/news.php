<?php
namespace App\myclass\sszg\role\ob\other;
// 新闻信息播报
class news{

   public function beforeAttack($info,$qipan){
   	$role=$qipan->getRole($info['releaser']);

   	echo $role->getAttrString('name').'使用了'.$role->{$role->action['action_type']}['name'].'<br>';

   	return $info;
   }

    public function afterUnderattack($info,$qipan){
   	$role=$qipan->getRole($info['target']);

   	echo $role->getAttrString('name').'&nbsp;:';
   	
   	foreach ($info['hurt_result'] as $key => $value) {

   		if($value['hurt']!=0){

   			$type='';
   			if($value['attack_type']=='wushang'){
				$type='物理';
				$color='#FFB800';
   			}
   			if($value['attack_type']=='fashang'){
				$type='法术';
				$color='#1E9FFF';
   			}
   			if($value['attack_type']=='zhenshang'){
				$type='真实';
				$color='#2F4056';
   			}
   			if(isset($value['info']['up']['baoshang'])){
   				
   				if ($value['info']['up']['baoshang']['shengxiao']==1) {
   					$color='#FF5722';
   				}
   			}

   			echo '&nbsp;受到'.$type.'伤害HP:<span style="color:'.$color.'">'.$value['hurt'].'</span>';
   		}
   		
   	}

   	echo '剩余血量：'.$role->getAttrValue('h').'<br>';

   	return $info;
   }
	
}