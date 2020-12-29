<?php
namespace App\myclass\sszg;


class fengwang implements role{
	use ob\ob;
	public $role=[];
	public $qipan='';
	public $skill=[
		'skill1'=>[
			'name'=>'风王的爪子',
			'fashiup'=>20,
			'p'=>198,
			'target'=>2,
		],
		'putong'=>[
			'name'=>'普通攻击',
			'p'=>100,
		],
	];

	//初始化时执行的自身方法
	private $inti=[

		'obInti',//ob类初始化
	
	];

	public function __construct($role_info){
		$this->role=$role_info;
		$this->inti();
	}

	//初始化
	private function inti(){

		foreach ($this->inti as $k => $v) {
			call_user_func_array([$this,$v],[]);
		}
	}



	//加入棋盘
	public function qipan($qipan){
		$this->qipan=$qipan;
	}

	

	//回合开始
	public function round(){
		echo $this->role['name'].'的回合开始了。<br>';
		$this->statementUseSkill();
		//开始行动
		$this->action();
		$this->roundEnd();
	}

	public function roundEnd(){
		echo $this->role['name'].'的回合结束。<br>';
	}

	//行动阶段
	public function action(){
		
		$action_type='skill1';

		//行动装饰
		$action_type=$this->beforeAction($action_type,$this,$this->qipan);
		if($action_type==null){return false;}


		//释放一技能
		if($action_type=='skill1'){
			$info =$this->skill1($this->skill['skill1']);
		}

		//行动装饰
		$this->afterAction($action_type,$this);
	}

	// 使用技能
	public function useSkill1(){
		
	}

	// 技能1获取目标
	public function gettarget(){
		// 获取目标
		$target_info=[//优先中后排2名随机敌人
			'rang'=>['zhong','hou','life','enemy'],
			'where'=>'rand',
			'number'=>2,
			'self_team'=>$this->getAttrString('team'),
		];
		//获取目标
		$target=$this->qipan->useTool('target','getTargetId',[$target_info,$this->qipan]);

		if(empty($target)){

			$target_info=[
				'rang'=>['life','enemy'],
				'where'=>'rand',
				'number'=>2,
				'self_team'=>$this->getAttrString('team'),
			];

			$target=$this->qipan->useTool('target','getTargetId',[$target_info,$this->qipan]);

		}elseif(count($target)==1){
			
			$target_info=[//后排目标不足选择前排1个随机目标
				'rang'=>['qian','life','enemy'],
				'where'=>'rand',
				'number'=>1,
				'self_team'=>$this->getAttrString('team'),
			];

			//获取并合并目标
			array_merge($target,$this->qipan->useTool('target','getTargetId',[$target_info,$this->qipan]));
		}
	}
	
	//声明使用技能
	public function statementUseSkill(){
	
		//加成信息
		$attack_info=[
			'bacevalue'=>[$this->role['id'].'.a'=>$this->skill['putong']['p']],
			'target'=>$this->getTarget(),
		];
	}


	//1技能
	public function skill1($skill_info=[]){
		//技能信息
		$skill=[
			'fashiup'=>isset($skill_info['fashiup'])?$skill_info['fashiup']:20,
			'p'=>isset($skill_info['p'])?$skill_info['p']:198,
		];

		$target=$this->qipan->getRole('yemeng1');
		$skill_info=[//行动信息
			'releaser'=>$this->role['id'],
			'target'=>$target->getAttrString('id'),
			'action_info'=>[
				'id'=>$this->qipan->getOnlyId(),//通用出手标识
				'type'=>'wuliattack',//行动类型 物理攻击
			],
			'other'=>['zhuiji',],//其他信息
			'attack_info'=>[ //攻击信息
				'main'=>[
					'type'=>'wushang',//物理伤害
					'bacevalue'=>[$this->role['id'].'.a'=>$skill['p']],//key为value时为固定值伤害
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
						
				]
			]


                    
			
		];

		//攻击法师增加额外
		if($target->role['zhiye']=='法师'){
			$skill_info['attack_info']['main']['valuechange']['fashiup']=$skill['fashiup'];
		}

		//攻击接口
		$this->attack($skill_info,$target);
	}

	//普通攻击数据
	public function getAttacking(){
		$info=[];



		return $info;
	}

	//进攻
	private function attack($info,$target){

		//攻击装饰 监听$info
		$this->beforeAttack($info);

		//调用目标的 受到攻击接口
		$result= $target->underattack($info);

		//攻击结束装饰
		$this->afterAttack($info,$result,$this->qipan);
	}

	//受到攻击
	public function underattack($attack_info){


		//攻击包类型
		$attack_rang=['wushang','fashang','zhenshang'];

		//buff包类型
		$buff_rang=['buff','debuff'];
		
		echo $this->role['name'].'受到的攻击<br>';

		$attacker=$this->qipan->getRole($attack_info['releaser']);
		

		foreach ($attack_info['attack_info'] as $k => $v) {

			//攻击处理
			if(in_array($v['type'],$attack_rang)){
				$info = $this->underAttackWork($v,$attacker);

				print_r($info);die;
			}


			//buff处理
			if(in_array($v['type'],$buff_rang)){
				$this->attackBuff($v,$attacker);//buff判定
			}
			
		}

		
	}

	//
	public function attackBuff($info){
		if($info['is']>=100){
			$this->buff($info);
		}else{
			$info['is']=$info['is']<0?0:$info['is'];
			$is =$this->qipan->useTool('ordinary','probability',[['yes'=>$info['is'],'no'=>100-$info['is']]]);
			if($is=='yes'){
				$this->buff($info['buff']);
			}
		}	
	}

	//获得buff $other其他信息
	public function buff($buff,$other=[]){

		$this->addBuff($buff);

	}

	//获得Buff
	public function addBuff($buff){
		$this->buff[]=$buff;
	}

	//驱散buff  $key $key=key|id|name 
	public function qusanBuff($value,$key='id'){
		if($key=='id'){
			foreach ($this->buff as $k => $v) {
				// if(){}
			}
		}
	} 



	//受到攻击 攻击信息结算
	public function underAttackWork($attack_info,$attacker){

		
		$arr=[];//伤害加成结果
		
		
			$arr['bace']=$this->attack_bace($attack_info);//基础伤害

			$arr['up']=[];//伤害加成、减成
			$arr['down']=[];//防御结算


			
			//监听 受攻击伤害加成、减成
			$this->underAttackWorking($attacker,$attack_info,$arr['up']);
			echo "555555";die;

			//监听 防御结算
			$arr['down']=$this->qipan->useTool('defenseWork','getResult',[$this,$attack_info]);;//伤害减免信息

				
		return $arr;

	}	


	//攻击基本伤害
	public function attack_bace($info){

		$bace=0;
		foreach ($info['bacevalue'] as $k => $v) {
			$one = explode('.',$k);

			$hero=$this->qipan->getRole($one[0]);// $one[0]是英雄的识别id

			
			$value=$hero->getAttrValue($one[1]);//角色值
			$upvalue=isset($info['attributechange'][$one[1]])?$info['attributechange'][$one[1]]:0;//附带属性提升值


			$bace=$bace+($value+$upvalue)*$v/100;
		}
		return $bace;
	}


	//血量改变
	public function change_h($value){
		$this->role['h']=$this->role['h']+$value;
		if($value<0){
			echo $this->role['name'].'受到伤害hp'.$value.'<br>';
		}
		
	}

	//获取属性值 $rang指定获取范围
	public function getAttrValue($attr,$rang=[]){
		$name=$attr;
		
		//buff属性
		$buff=[];
			
			foreach ($this->role['buff'] as $k => $v) {
				$value=0;
				if(isset($v['attrchange'][$attr])){
					//固定值
					$val=isset($v['attrchange'][$attr]['value'])?$v['attrchange'][$attr]['value']:0;
					//基础百分比值
					$p=isset($v['attrchange'][$attr]['p'])?$v['attrchange'][$attr]['p']*$this->role[$name]/100:0;
					$value=($val+$p)*$v['ceng'];
				}
				
				if($v['diejia']=='none'){
					$buff[$v['name']]=isset($buff[$v['name']])?($buff[$v['name']]>$value?$buff[$v['name']]:$value):$value;
				}else{
					$buff[]=$value;
				}
			}


		//临时属性
		$linshi=[];
		foreach ($this->role['linshiattr'] as $k=> $v) {
			if($v['type']==$name){
				$linshivalue=$v['value']+$v['value_p']*$this->role[$name]/100;
				$linshi[]=$linshivalue;
			}
		}


		if(empty($rang)){
			// 返回总属性
			return array_sum($buff)+array_sum($linshi)+(isset($this->role[$name])?$this->role[$name]:0);
		}else{
			$result =0;
			$result+=in_array('buff',$rang)?array_sum($buff):0;
			$result+=in_array('linshi',$rang)?array_sum($linshi):0;
			$result+=in_array('bace',$rang)?(isset($this->role[$name])?$this->role[$name]:0):0;
			return $result;
		}

	}

	// 获取字符串属性
	public function getAttrString($name){
		return isset($this->role[$name])?$this->role[$name]:null;
	}
	
}



?>