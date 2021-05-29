<?php
namespace App\myclass\sszg\hero\zhanshi;

use App\myclass\sszg\role\role;
/**
 * 风王
 */
class fengwang extends role
{
	
	
	public $skill1=[
			'name'=>'风王的爪子',
			'fashiup'=>20,
			'p'=>198,
			'target'=>2,
			'type'=>'wuliattack',
		];

	public $putong=[
			'name'=>'普通攻击',
			'p'=>100,
			'type'=>'wuliattack',
		];

	//一技能目标
	public function skill1Target(){
		return $this->qipan->useTool('target','getTargetByCommon',['enemy_rand_zhonghou',$this,['number'=>2]]);
	}

	//二技能目标
	public function skill2Target(){

	}

	//普通攻击目标
	public function putongTarget(){

	}

	//1技能
	public function skill1(){
		//技能信息
		$skill=$this->skill1;

		$targets= $this->getTarget('skill1');
		if(empty($targets) ){ return false;}
		
		$skill_info=[//行动信息
			'releaser'=>$this->role['id'],
			'target'=>$targets,
			'type'=>$skill['type'],	//行动类型 物理攻击
			'other'=>['zhuiji'],//其他信息
			'attack_info'=>[ //攻击信息
				'main'=>[
					'type'=>'wushang',//物理伤害
					'bacevalue'=>$this->getAttrValue('a')*$skill['p']/100,//key为value时为固定值伤害
					'valuechange'=>[//伤害加成 或伤害降低
						//'fashiup'=>20,
					],
					'attributechange'=>[//附带属性增加或减少
						'baoshang'=>10,
						'wushang'=>10,
					],
					'other'=>[//其他属性
						// 'baoshang',//指定发生暴击判定
						// 'nobaoshang',//指定不会发生暴击判定
						// 'baoji',//必定暴击
						// 'bubaoji',//必定不暴击
						// 'wushang',//指定物伤生效
						// 'nowushang',//指定物伤不生效
						// 'fashang',//指定法伤生效
						// 'nofashang',//指定法伤不生效
						// 'shanghai',//指定伤害加成不生效
						// 'noshanghai',//指定伤害加成不生效
					]
				],
				[
					'type'=>'buff',
					'is'=>45,//概率
					'buff'=>$this->qipan->getBuff([
						'name'=>'chenmo',
						'releaser'=>$this->getAttrString('id'),
	                    'bufftype'=>'debuff',
	                    'type'=>'chenmo',
	                    'turn'=>1])
						
				],
				[
					'type'=>'fashang',
					'bacevalue'=>$this->getAttrValue('a')*$skill['p']/100,//key为value时为固定值伤害
					'valuechange'=>[],
					'attributechange'=>[],
					'other'=>[]
				],
			]

		];

		//攻击法师增加额外
		// if($target->role['zhiye']=='法师'){
		// 	$skill_info['attack_info']['main']['valuechange']['fashiup']=$skill['fashiup'];
		// }

		//攻击
		$result = $this->attack($skill_info);
	}

}
?>