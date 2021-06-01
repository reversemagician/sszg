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
			'fashiup'=>20,
			'p'=>198,
			'target'=>2,
			'type'=>'wuliattack',
		];

	public $skill2=[
			'name'=>'<span style="color:#009688">致命审判</span>',
			'p'=>350,
			'target'=>1,
			'type'=>'wuliattack',
		];

	public $skill4=[
			'shengxiao'=>true,//被动是否生效
			'name'=>'风王被动',
			'xiaoguo1'=>[
				'baoji'=>20,
				'shanghai'=>20,
			],
			'xiaoguo2'=>[
				'p'=>85,
			]
		];

	protected $inti=[
		'obInti',//ob类初始化
		'loadingSkill4'//挂载被动技能
	];
	
	//一技能目标
	public function skill1Target(){
		return $this->qipan->useTool('target','getTargetByCommon',['enemy_rand_zhonghou',$this,['number'=>2]]);
	}

	//二技能目标
	public function skill2Target(){

		$info=[
	 			'rang'=>['enemy','life'],
	 			'where'=>'min_.h',
	 			'number'=>1,
	 			'remove'=>[],
	 			'self_team'=>$this->role['team'],
	 		];

	 	$target=$this->qipan->useTool('target','getTarget',[$info,$this->qipan]);

	 	return $target[array_rand($target)];
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
			]

		];

		//攻击法师增加额外
		// if($target->role['zhiye']=='法师'){
		// 	$info['attack_info']['main']['valuechange']['fashiup']=$skill['fashiup'];
		// }

		// $infos=[$info,$info];
		// $infos[0]['target']='taitan1';
		// $infos[1]['target']='yemeng1';
		

		//攻击
		$result = $this->attack($info);
		// die;
	}

	public function skill2($value='')
	{
		$skill=$this->skill2;

		$target= $this->getTarget('skill2');

		$info=[//行动信息
			'target'=>[$target->getAttrString('id')],
			'type'=>$skill['type'],	//行动类型 物理攻击
			'other'=>[],//其他信息
			'attack_info'=>[ //攻击信息
				'main'=>[
					'type'=>'wushang',//物理伤害
					'bacevalue'=>$this->getAttrValue('a')*$skill['p']/100,//key为value时为固定值伤害
					'valuechange'=>[
					],
					'attributechange'=>[],
					'other'=>[]
				],
			]

		];

		if($target->getAttrValue('h')/$target->getAttrValue('h_max')<=0.3){
			$info['attack_info']['main']['valuechange']['h_small']=30;
		}

		$this->attack($info);
	}

	//被动技能 需要监听beforeAttack
	public function skill4($attack_info){
		$skill=$this->skill4;

		
		if($skill['shengxiao']){

			//生效的被动效果
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
				if (isset($attack_info['attack_info']['main']['valuechange']['shanghai'])) {
					$attack_info['attack_info']['main']['valuechange']['shanghai']+=$skill['xiaoguo1']['shanghai'];
				}else{
					$attack_info['attack_info']['main']['valuechange']['shanghai']=$skill['xiaoguo1']['shanghai'];
				}

				if (isset($attack_info['attack_info']['main']['attributechange']['baoji'])) {
					$attack_info['attack_info']['main']['attributechange']['baoji']+=$skill['xiaoguo1']['baoji'];
				}else{
					$attack_info['attack_info']['main']['attributechange']['baoji']=$skill['xiaoguo1']['baoji'];
				}
				
			}elseif ($xiaoguo==2) {
				$info=[
		 			'rang'=>['enemy','life'],//范围限定//enemy敌人|teammate队友|qian前排|zhong中|hou后排|one第一行|two第二|three第三|fashi法师|zhanshi战士|fuzhu辅助|roudun肉盾|life存活|death死亡|
		 			'where'=>'rand',//筛选条件 max_*最大属性|min_*最小属性|rand随机|min_.h血量百分比最低|max_.h血量百分比最高
		 			'number'=>1,//数量
		 			'remove'=>$attack_info['target'],//排除的角色ID
		 			'self_team'=>$this->getAttrString('team'),//自身队伍 当需要判断敌人或友方时需要该值
		 		];

		 		$target=$this->qipan->useTool('target','getTargetId',[$info,$this->qipan]);
		 		if (!empty($target)) {
		 			$attack_info['target'][]=$target[array_rand($target)];
		 		}
			}

		}

		return $attack_info;
	}

	//挂载被动技能 到beforeAttack
	public function loadingSkill4(){
		$this->addOb('beforeAttack' ,$this->getAttrString('id').'skill4', $this,'skill4');
	}
}
?>