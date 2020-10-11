<?php
namespace App\myclass\sszg;

class fengwang implements role{
	public $role=[];
	public $xibie=[];
	public $target=[];
	public $skill=[
		'skill1'=>[
			'name'=>'风王的爪子',
			'fashiup'=>20,
			'p'=>198,
			'target'=>2,
		],
	];

	public function __construct($role_info,$xibie){
		$this->role=$role_info;
		$this->xibie=$xibie;
	}

	//增加目标
	public function addTarget($target){
		$this->target=$target;
	}

	//装饰 行动之前
	public function beforeAction($action_type,$self){
		echo '小心点！'.$self->role['name'].'正在活动她的爪子,<br>';
		$self->role['name']='史芬克斯';
	}
	//装饰 行动之后
	public function afterAction($action_type,$self){

	}
	//装饰 攻击之前
	public function beforeAttack($releas_info,$target){
		echo $releas_info['releaser']->role['name'].'出手了。<br>';
	}
	//装饰 攻击之后
	public function afterAttack($releas_info,$target){
		echo $releas_info['releaser']->role['name'].'回合结束。<br>';
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


		$target=$this->target;
		$skill_info=[//行动信息
			'releaser'=>$this,
			'action_info'=>[
				'id'=>1,//行动ID
				'type'=>'wuliattack',//行动类型 物理攻击
			],
			'attack_info'=>[ //攻击信息
				'main'=>[
					'type'=>'wuli',//物理伤害
					'novalueupkey'=>[],//指定不生效加成项
					'valueupkey'=>[],//必定生效加成项
					'bacevalue'=>['a'=>$skill['p']],//数组则为系数 非数组则为固定值
					'valuechange'=>[//伤害加成 或伤害降低
						// 'fashiup'=>[
						// 	'p'=>$fashiup,//比例值
						// 	'value'=>0,//固定值
						// ]
					],
					'attributechange'=>[//附带属性增加或减少
						// 'mingzhong'=>[//命中提升
						// 	'p'=>0,
						// 	'value'=>0,//固定值
						// ]
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
			$skill_info['attack_info']['main']['valuechange']['fashiup']=['p'=>$skill['fashiup'],'value'=>0];
		}

		//攻击接口
		$this->attack($skill_info,$target);
	}

	//攻击
	private function attack($info,$target){
		//攻击装饰
		$this->beforeAttack($info,$target);

		echo $info['releaser']->role['name'].'攻击了'.$target->role['name'].'<br>';

		//调用目标的 受到攻击接口
		$target->underattack($info);

		//攻击结束装饰
		$this->afterAttack($info,$target);
	}

	//受到攻击
	public function underattack($attack_info){
		
		echo $this->role['name'].'受到来自'.$attack_info['releaser']->role['name'].'的攻击<br>';

		$this->underAttackWork($attack_info);
	}

	//受到攻击伤害结算
	public function underAttackWork($attack_info){
		unset($attack_info['releaser']);

		print_r($attack_info);die;

		//暴伤加成
		if(true){
			$upname='baojiup';//加成名
			$baojiarr=['wuli','mofa'];//指定默认可暴击的伤害类型
			$untype=['buff'];//指定非伤害信息

			foreach ($attack_info['attack_info'] as $k => $v) {

				//跳过非伤害的信息包
				if(!in_array($v['type'],$untype)){ 

					$isbaojiup=false;
					$isbaojiup=in_array($v['type'],$baojiarr)?true:$isbaojiup;
					$isbaojiup=in_array($upname,$v['novalueupkey'])?false:$isbaojiup;
					$isbaojiup=in_array($upname,$v['valueupkey'])?true:$isbaojiup;
					
					if($isbaojiup){
						//判定为可暴击伤害 计算暴伤

						if(isset($v['valuechange']['baoshangup'])){
							die;
							$attack_info['attack_info'][$k]['valuechange']['baoshangup']['p']=$attack_info['attack_info'][$k]['valuechange']['baoshangup']['p']+$attack_info['releaser']->getAttr('baoshang');
						}else{
							$attack_info['attack_info'][$k]['valuechange']['baoshangup']=[
								'p'=>$attack_info['releaser']->getAttr('baoshang'),
								'value'=>0,
							];
						}


					}
				}
				
					
			}
		}

	}

	public function change_h($value){
		$this->role['h']=$this->role['h']+$value;
		if($value<0){
			echo $this->role['name'].'受到伤害hp'.$value.'<br>';
		}
		
	}

	//获取属性
	public function getAttr($attr){
		$rang=['h_max','h','a','d','s','mingzhong','baoji','baoshang','wushang','fashang'];
		if(!in_array($attr, $rang)){
			return null;
		}
		$name=$attr;
		if(isset($this->role['buff'][$name.'buff'])){
			$up=[];
			foreach ($this->role['buff'][$name.'buff'] as $k => $v) {
				$upvalue=$v['value']+$v['value_p']*$this->role[$name]/100;
				$up[$v['buffid']]=isset($up[$v['buffid']])?($up[$v['buffid']]>$upvalue?$up[$v['buffid']]:$upvalue):$upvalue;
			}
			return array_sum($up)+$this->role[$name];

		}else{
			return $this->role[$name];
		}
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