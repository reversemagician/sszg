<?php
namespace App\myclass\sszg\skill;

/**
 * 通用普通攻击
 */
trait putong 
{
	public $putong=[
		'name'=>'普通攻击',
		'id'=>'skill_ptgj',
		'p'=>100,
		'type'=>'wuliattack',
	];

	//普通攻击目标
	public function putongTarget(){
		
		return $this->qipan->useTool('target','putong',[$this]);
		  ;
	}

	public function putong(){
		$putong=$this->putong;

		$targets= $this->getTarget('putong');

		$info=[//行动信息
			'releaser'=>$this->role['id'],
			'target'=>[$targets],
			'type'=>$putong['type'],	//行动类型 
			'other'=>[],//其他信息
			'attack_info'=>[ //攻击信息
				[
					'type'=>$putong['type']=='wuliattack'?'wushang':'fashang',
					'bacevalue'=>$this->getAttrValue('a')*$putong['p']/100,//key为value时为固定值伤害
					'valuechange'=>[],
					'attributechange'=>[],
					'other'=>[],				
				]
			]
		];

		$this->attack($info);
	}
}

?>