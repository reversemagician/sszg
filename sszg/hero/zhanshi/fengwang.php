<?php
namespace App\myclass\sszg\hero\zhanshi;

use App\myclass\sszg\role\role;
use App\myclass\sszg\skill\putong;//普通攻击
/**
 * 风王
 */
class fengwang extends role
{
	use putong;
	
	public $skill1=[ 
			'name'=>'<span style="color:#5FB878">狂乱突袭</span>',
			'id'=>'skill_kltx',
			'fashiup'=>20,
			'p'=>198,
			'target'=>2,
			'type'=>'wuliattack',
			
		];

	public $skill2=[
			'name'=>'<span style="color:#009688">致命审判</span>',
			'id'=>'skill_zmsp',
			'p'=>350,
			'target'=>1,
			'type'=>'wuliattack',
		];

	public $skill4=[
			'shengxiao'=>true,//被动是否生效
			'name'=>'风王领域',
			'id'=>'skill_fwly',
			'xiaoguo1'=>[
				'baoji'=>20,
				'shanghai'=>20,
			],
			'xiaoguo2'=>[
				'p'=>85,
			]
		];

	public $skill_extend_id=0;//标记
	
	//一技能目标
	public function skill1Target(){
		return $this->qipan->useTool('target','rand_zhonghou',[$this,['number'=>2]]);
	}

	//二技能目标
	public function skill2Target(){

		$info=[
	 			'rang'=>['enemy','life'],
	 			'where'=>'min_.h',
	 			'number'=>1,
	 			'remove'=>[],
	 		];

	 	$target=$this->qipan->useTool('target','getTargetId',[$info,$this]);

	 	return $target;
	}

	
	//1技能
	public function skill1(){
		//技能信息
		$skill=$this->skill1;

		$targets= $this->getTarget('skill1');
		if(empty($targets) ){ return false;}

		$info=[//行动信息
			'target'=>$targets,
			'type'=>$skill['type'],	//行动类型 物理攻击
			'other'=>[],//其他信息
			'attack_info'=>[ //攻击信息
				[
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
				]
				
			],
			'buff_info'=>[
				[
					'type'=>'buff',
					'is'=>45,//概率
					// 'buff'=>$this->qipan->getBuff([
					// 	'name'=>'chenmo',
					// 	'releaser'=>$this->getAttrString('id'),
	    //                 'bufftype'=>'debuff',
	    //                 'type'=>'chenmo',
	    //                 'turn'=>1])
						
				],
			]

		];

		//挂载目标是法师则增加伤害
		$this->loadingUnloadOb('beforeAttacking',$this,'skill1Extend',0,'afterAttack');

		//攻击
		$result = $this->attack($info);

		// print_r($result);die;
		
	}

	public function skill2($value='')
	{
		$skill=$this->skill2;

		$target= $this->getTarget('skill2');
		$info=[//行动信息
			'target'=>$target,
			'type'=>$skill['type'],	//行动类型 物理攻击
			'other'=>[],//其他信息
			'attack_info'=>[ //攻击信息
				[
					'type'=>'wushang',//物理伤害
					'bacevalue'=>$this->getAttrValue('a')*$skill['p']/100,//key为value时为固定值伤害
					'valuechange'=>[
					],
					'attributechange'=>[],
					'other'=>['fengwang_hurt_back']
				],
			]

		];

		//目标血量第于30% 伤害增加
		$target=$this->qipan->getRole($target[array_rand($target)]);
		if($target->getAttrValue('h')/$target->getAttrValue('h_max')<=0.3){
			$info['attack_info'][0]['valuechange']['h_small']=30;
		}

		$this->attack($info);
	}

	//挂载目标是法师则增加伤害 目标为法师的话伤害提升
	protected function skill1Extend($info,$self,$ob_key){
		$qipan =$this->qipan;
		
		$target_zhiye =$qipan->getRole($info['target'])->getAttrString('zhiye');
		
		if ($target_zhiye=='fashi') {
			if(isset($info['attack_info'][0]['valuechange']['fashiup'])){
				$info['attack_info'][0]['valuechange']['fashiup']+=$this->skill1['fashiup'];
			}else{
				$info['attack_info'][0]['valuechange']['fashiup']=$this->skill1['fashiup'];
			}
		}

		return $info;
	}

	//被动技能 需要挂载到beforeAttack
	public function skill4($attack_info){
		$skill=$this->skill4;

		if($skill['shengxiao']){

			//生效的被动效果 效果1或效果2
			$xiaoguo=1;

			$actioned_role=$this->qipan->getActionedRole();

			if(empty($actioned_role)){$xiaoguo=1;}else{
				$team=substr($this->getAttrString('id'),-1,1);
				foreach ($actioned_role as $k => $v) {
					if(substr($v,-1,1)==$team){
						$xiaoguo=2;
					};
				}
			}

			if($xiaoguo==1){
				//效果一提升伤害和暴击率
				
				foreach ($attack_info as $k => $v) {
					if (isset($attack_info[$k]['attack_info'][0]['valuechange']['shanghai'])) {
						$attack_info[$k]['attack_info'][0]['valuechange']['shanghai']+=$skill['xiaoguo1']['shanghai'];
					}else{
						$attack_info[$k]['attack_info'][0]['valuechange']['shanghai']=$skill['xiaoguo1']['shanghai'];
					}

					if (isset($attack_info[$k]['attack_info'][0]['attributechange']['baoji'])) {
						$attack_info[$k]['attack_info'][0]['attributechange']['baoji']+=$skill['xiaoguo1']['baoji'];
					}else{
						$attack_info[$k]['attack_info'][0]['attributechange']['baoji']=$skill['xiaoguo1']['baoji'];
					}
				}
				
				
			}elseif ($xiaoguo==2) {
				//效果二添加一个额外的目标

				$remove=[];
				foreach ($attack_info as $key => $value) {
					$remove[]=$value['target'];
				}
				$info=[
		 			'rang'=>['enemy','life'],
		 			'where'=>'rand',
		 			'number'=>1,
		 			'remove'=>$remove,
		 		];

		 		$target=$this->qipan->useTool('target','getTargetId',[$info,$this]);

		 		if (!empty($target)) {
		 			$pack=$attack_info[array_rand($attack_info)];
		 			$pack['attack_info'][0]['bacevalue']=$pack['attack_info'][0]['bacevalue']*$skill['xiaoguo2']['p']/100;
		 			$pack['target']=$target[array_rand($target)];
		 			$attack_info[]=$pack;
		 		}
			}
		}

		return $attack_info;
	}

	//执行被动技能的挂载 使用备用扩展方法来执行 
	protected function extendInti(){
		
		//挂载被动技能
		$this->addOb('beforeAttack' ,$this->getAttrString('id').'skill4', $this,'skill4');

	}
}
?>