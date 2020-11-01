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
					'novalueupkey'=>[],//指定不生效加成项
					'valueupkey'=>[],//必定生效加成项
					'bacevalue'=>[$this->role['id'].'.h'=>$skill['p']],//key为value时为固定值伤害
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
						// 'baoji',//必定暴击
						// 'bubaoji',//必定不暴击
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
				echo $up_arr[$k]['bace'];
				//伤害加成信息
					$up_arr[$k]['up']=[];//加成信息
					// 暴击结算信息
					$up_arr[$k]['up']=array_merge($up_arr[$k]['up'],$this->baoshang_jiacheng_jiesuan($attacker,$v));

					//物理、法术伤害加成
					$up_arr[$k]['up']=array_merge($up_arr[$k]['up'],$this->wufashang_jiacheng_jiesuan($attacker,$v));

					//其他附带伤害加成项结算
					$up_arr[$k]['up']=array_merge($up_arr[$k]['up'],$this->qita_jiacheng_jiesuan($attacker,$v));

				
				//受攻击伤害减免计算
					$up_arr[$k]['down']=[];//伤害减免信息

					//物理、法术减免
					$up_arr[$k]['up']=array_merge($up_arr[$k]['up'],$this->wufashang_jiacheng_jiesuan($attacker,$v));	
					
			}
		}
		print_r($up_arr);die;

	}



	/**
	 *其他伤害加成结算 
	 *@param array $attack_info_package 攻击者 
	 *@param array $attack_info_package 攻击信息包 
	 *@return array $result 物伤信息
	 */
	public function qita_jiacheng_jiesuan($attacker,$attack_info_package){
		$up_result=[];
		$paichu=['wushang','fashang','baoshang'];//排除已结算的项
		foreach ($attack_info_package['valuechange'] as $k => $v) {
			if(!in_array($k,$paichu)){
				$up_result[$k]=[
					'shengxiao'=>true,
					'up'=>$v['p'],
				];
			}
		}
		return $up_result;
	}

	/**
	 *物理、法术伤害加成结算 
	 *@param array $attack_info_package 攻击者 
	 *@param array $attack_info_package 攻击信息包 
	 *@return array $result 物伤信息
	 */
	public function wufashang_jiacheng_jiesuan($attacker,$attack_info_package){
			
			$up_result=[];

		//物理伤害加成
			$upname='wushang';//加成名
			$arr=[$upname];//指定默认生效伤害类型
			$isup=false;
			$isup=in_array($attack_info_package['type'],$arr)?true:$isup;
			$isup=in_array($upname,$attack_info_package['novalueupkey'])?false:$isup;//指定物理加成不生效
			$isup=in_array($upname,$attack_info_package['valueupkey'])?true:$isup;//指定物理加成生效


			if ($isup) {
				$up=0;//加成
				if(isset($attack_info_package['attributechange'][$upname])){
					$up=$attack_info_package['attributechange'][$upname]['value']+$attack_info_package['attributechange'][$upname]['p']*$attacker->getAttr($upname)/100+$attacker->getAttr($upname);
				}else{
					$up=$attacker->getAttr($upname);
				}
				//记录数据
				$up_result[$upname]=['shengxiao'=>$isup];
				$up_result[$upname]['up']=$up<0?0:$up;
			}

		//法伤伤害加成
			$upname='fashang';//加成名
			$arr=[$upname];//指定默认生效伤害类型
			$isup=false;
			$isup=in_array($attack_info_package['type'],$arr)?true:$isup;
			$isup=in_array($upname,$attack_info_package['novalueupkey'])?false:$isup;//指定物理加成不生效
			$isup=in_array($upname,$attack_info_package['valueupkey'])?true:$isup;//指定物理加成生效

			

			if ($isup) {
				
				$up=0;//加成
				if(isset($attack_info_package['attributechange'][$upname])){
					$up=$attack_info_package['attributechange'][$upname]['value']+$attack_info_package['attributechange'][$upname]['p']*$attacker->getAttr($upname)/100+$attacker->getAttr($upname);
				}else{
					$up=$attacker->getAttr($upname);
				}

				//记录数据
				$up_result[$upname]=['shengxiao'=>$isup];
				$up_result[$upname]['up']=$up<0?0:$up;
			}

		return $up_result;
	}




	/**
	 *暴击结算 
	 *@param array $attack_info_package 攻击者 
	 *@param array $attack_info_package 攻击信息包 
	 *@return array $baoji_result 暴击结算信息
	 */
	public function baoshang_jiacheng_jiesuan($attacker,$attack_info_package){

		$upname='baoshang';
		$baojiarr=['wushang','fashang'];//指定默认可暴击的伤害类型
		$isbaojiup=false;
		$isbaojiup=in_array($attack_info_package['type'],$baojiarr)?true:$isbaojiup;
		$isbaojiup=in_array($upname,$attack_info_package['novalueupkey'])?false:$isbaojiup;//判断是否指定不结算暴击
		$isbaojiup=in_array($upname,$attack_info_package['valueupkey'])?true:$isbaojiup;//判断是否指定必定结算暴击

		//暴击结果数据记录
		$baoji_result=[
			'can'=>$isbaojiup,//是否可暴击
		];
		
		if($isbaojiup){
			//判定为可暴击伤害 结算暴击

			//暴击伤害
			$baoshang=0;
			if(isset($attack_info_package['attributechange']['baoshang'])){
				$baoshang=$attack_info_package['attributechange']['baoshang']['value']+$attack_info_package['attributechange']['baoshang']['p']*$attacker->getAttr('baoshang')/100+$attacker->getAttr('baoshang');
			}else{
				$baoshang=$attacker->getAttr('baoshang');
			}

			//暴击率
			$baoji=0;
			if(isset($attack_info_package['attributechange']['baoji'])){
				$baoji=$attack_info_package['attributechange']['baoji']['value']+$v['attributechange']['baoji']['p']*$attacker->getAttr('baoji')/100+$attacker->getAttr('baoji');
			}else{
				$baoji=$attacker->getAttr('baoji');
			}

			

			//暴击命中
			$baoji=$baoji>=100?100:$baoji;
			$baoji_p=[
				'baoji'=>$baoji,
				'bubaoji'=>100-$baoji,
			];
			$is_baoji= $this->probability($baoji_p);
			//数据记录
			$baoji_result['baoji']=$baoji;//暴击率
			$baoji_result['up']=$baoshang;//暴伤
			$baoji_result['baojimingzhong']=$is_baoji=='baoji'?true:false;//暴击命中
			$baoji_result['result']=$is_baoji;//暴击结果

			//抗暴命中
			if($is_baoji=='baoji'){
				$kangbao=$this->getAttr('kangbao')>100?100:$this->getAttr('kangbao');
				$kangbao_p=[
					'bubaoji'=>$kangbao,
					'baoji'=>100-$kangbao,
				];
				$is_baoji= $this->probability($baoji_p);

				//数据记录
				$baoji_result['kangbao']=$kangbao;//抗暴率
				$baoji_result['kangbaomingzhong']=$is_baoji=='bubaoji'?true:false;//抗暴命中
				$baoji_result['result']=$is_baoji;//暴击结果
				
			}
		}

		//必定暴击和必定不暴击参数
		$baoji_result['result']=in_array('baoji',$attack_info_package['other'])?'baoji':$baoji_result['result'];
		$baoji_result['result']=in_array('bubaoji',$attack_info_package['other'])?'bubaoji':$baoji_result['result'];

		$baoji_result['shengxiao']=	$baoji_result['result']=='baoji'?true:false;		

		return ['baoshang'=>$baoji_result];//暴击结果
		
	}


	//攻击基本伤害
	public function attack_bace($info){
		$bace=0;
		foreach ($info as $k => $v) {
			$one = explode('.',$k);

			// $one[0]是英雄的识别id
			$bace+=$this->getAttr($one[1]);
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

	//获取属性值 $bace不为空代表获取基础属性
	public function getAttr($attr,$bace=''){
		$name=$attr;

		if($bace!=''){
			//只返回基础属性
			return isset($this->role[$name])?$this->role[$name]:0;
		}

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

		
		// 返回总属性
		return array_sum($buff)+array_sum($linshi)+isset($this->role[$name])?$this->role[$name]:0;
		
		

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