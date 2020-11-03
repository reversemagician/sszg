<?php
namespace App\myclass\sszg;


class fengwang implements role{
	public $role=[];
	public $qipan='';
	public $skill=[
		'skill1'=>[
			'name'=>'风王的爪子',
			'fashiup'=>20,
			'p'=>198,
			'target'=>2,
		],
	];

	public function __construct($role_info){
		$this->role=$role_info;
	}

	//棋盘
	public function qipan($qipan){
		$this->qipan=$qipan;
	}

	//装饰 行动之前
	public function beforeAction($action_type,$self){
		// echo '小心点！'.$self->role['name'].'正在活动她的爪子,<br>';
		// $self->role['name']='史芬克斯';
	}
	//装饰 行动之后
	public function afterAction($action_type,$self){

	}
	//装饰 攻击之前
	public function beforeAttack($releas_info,$target){
		// echo $releas_info['releaser']->role['name'].'出手了。<br>';
	}
	//装饰 攻击之后
	public function afterAttack($releas_info,$target){
		// echo $releas_info['releaser']->role['name'].'回合结束。<br>';
	}

	//回合开始
	public function round(){
		echo $this->role['name'].'的回合开始了。<br>';
		
		$action_type='skill1';

		//开始行动
		$this->action($action_type);

	}

	//行动
	public function action($action_type){
		
		//行动装饰
		$this->beforeAction($action_type,$this);

		//释放一技能
		if($action_type=='skill1'){
			$info =$this->skill1($this->skill['skill1']);
		}

		//行动装饰
		$this->afterAction($action_type,$this);
	}

	public function skill1($skill_info=[]){
		//技能信息
		$skill=[
			'fashiup'=>isset($skill_info['fashiup'])?$skill_info['fashiup']:20,
			'p'=>isset($skill_info['p'])?$skill_info['p']:198,
		];


		$target=$this->qipan->getRole('yemeng1');
		$skill_info=[//行动信息
			'releaser'=>$this->role['id'],
			'action_info'=>[
				'id'=>1,//行动ID
				'type'=>'wuliattack',//行动类型 物理攻击
			],
			'attack_info'=>[ //攻击信息
				'main'=>[
					'type'=>'wushang',//物理伤害
					'bacevalue'=>[$this->role['id'].'.a'=>$skill['p']],//key为value时为固定值伤害
					'valuechange'=>[//伤害加成 或伤害降低
						// 'fashiup'=>[
						// 	'p'=>$fashiup,//比例值
						// ]
					],
					'attributechange'=>[//附带属性增加或减少
						'baoshang'=>[//命中提升
							'p'=>0,
							'value'=>60,//固定值
						],
						'wushang'=>[
							'p'=>0,
							'value'=>20,
						],
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
					'is'=>0.45,//概率
					'buff'=>[
						'name'=>'chenmo',//buff名
	                    'buffid'=>'chenmo',
	                    //同类型id相同仅生效一个效果最高的buff
	                    'type'=>'debuff',//增益 减益
	                    'is_qusan'=>true,//可驱散
	                    'turn'=>1,//持续回合
	                    'value'=>0,//固定值
	                    'value_p'=>0,//百分比
					]
				]
			]
			
		];

		//攻击法师增加额外
		if($target->role['zhiye']=='法师'){
			$skill_info['attack_info']['main']['valuechange']['fashiup']=['p'=>$skill['fashiup']];
		}

		//攻击接口
		$this->attack($skill_info,$target);
	}

	//攻击
	private function attack($info,$target){
		//攻击装饰
		$this->beforeAttack($info,$target);

		echo $this->role['name'].'攻击了'.$target->role['name'].'<br>';

		//调用目标的 受到攻击接口
		$target->underattack($info);

		//攻击结束装饰
		$this->afterAttack($info,$target);
	}

	//受到攻击
	public function underattack($attack_info){
		
		echo $this->role['name'].'受到的攻击<br>';

		$this->underAttackWork($attack_info);
	}

	//受到攻击伤害结算
	public function underAttackWork($attack_info){

		// unset($attack_info['releaser']);
		// print_r($attack_info);

		

		
		//伤害加成计算 循环攻击包信息
		$untype=['buff'];//指定非伤害信息
		$up_arr=[];//伤害加成结果
		$attacker=$this->qipan->getRole($attack_info['releaser']);
		foreach ($attack_info['attack_info'] as $k => $v) {

			
			
			//跳过非伤害的信息包
			if(!in_array($v['type'],$untype)){ 

				$up_arr[$k]['bace']=$this->attack_bace($v['bacevalue']);//基础伤害

				//进攻结算
				$jiacheng=new attackWork();
				$up_arr[$k]['up']=$jiacheng->getResult($attacker,$v);
				
				//防御结算
				$up_arr[$k]['down']=[];//伤害减免信息

					//物理、法术减免
					
					
			}
		}
		print_r($up_arr);die;

	}	


	//攻击基本伤害
	public function attack_bace($info){
		$bace=0;
		foreach ($info as $k => $v) {
			$one = explode('.',$k);

			$hero=$this->qipan->getRole($one[0]);

			// $one[0]是英雄的识别id
			$bace+=$hero->getAttrValue($one[1]);
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
		// $rang=['buff','linshi','base'];

		
		//buff属性
		$buff=[];
		if(isset($this->role['buff'][$name.'buff'])){
			
			foreach ($this->role['buff'][$name.'buff'] as $k => $v) {

				$buffvalue=$v['value']+$v['value_p']*$this->role[$name]/100;
				$buff[$v['buffid']]=isset($buff[$v['buffid']])?($buff[$v['buffid']]>$buffvalue?$buff[$v['buffid']]:$buffvalue):$buffvalue;
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
	
	

	/**
	 *获取多维数组的某个值 
	 *@param array $arr 数组 
	 *@param array $key 属性名 key0.key1.key2 
	 *@return value 属性不存在则返回字符串'undefined'
	 */
	private function getArrayValue($key='',$arr=[]){
		if($key==''||empty($arr)){return '';}
		$layer=explode('.',$key);
		$vstr='$arr';
		foreach ($layer as $v) {
			$vstr.='["'.$v.'"]';
		}
		$evalstr='$result=isset('.$vstr.')?'.$vstr.':"undefined";';//undefined该值不存在
		eval($evalstr);
		return $result;
	}


	/**
	 * 概率命中
	 *
	 * @param array $ps array('a'=>0.5,'b'=>5.2,'c'=>0.4)
	 * @return string 返回上面数组的key
	 */
	private function probability($ps){
        $sum = array_sum($ps);
        $len=0;
       	foreach ($ps as $k => $v) {
       		$newlen=$this->getFloatLen($v);
       		$len =$newlen>$len?$newlen:$len;
       	}
       	$base=1;
       	for ($i=0; $i < $len; $i++) { 
       		$base=$base*10;
       	}
        $max=$sum*$base;
        $rand = mt_rand(1,$max);
        $randsum=0;
        $result='';
        foreach ($ps as $k => $v) {
        	$randsum=$base*$v+$randsum;
        	if( $rand <= $randsum ){
        		$result=$k; break;
        	}
        }
	    return $result;
	}

	//获取数字的小数长度
	private function getFloatLen($num) {
		$num =(float)$num;
		$temp = explode ( '.', $num );
		$count = 0;
		if (count( $temp ) > 1) {
			$decimal = end ( $temp );
			$count = strlen ( $decimal );
		}
		return $count;
	}
}



?>