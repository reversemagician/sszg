<?php
namespace App\myclass\sszg;

class fengwang implements role{
	public $role=[];
	public $xibie=[];

	public function __construct($role_info,$xibie){
		$this->role=$role_info;
		$this->xibie=$xibie;
	}

	public function skill1(){
		$skill=[];
		$attack_info=[
			'attacker'=>$this->role,
		];
	}

	//攻击
	public function attack($target){
		$attack_info=[
			'type'=>'wuli',
			'attacker'=>$this->role,
			'p'=>198,//伤害系数
		];
		//系数加成
		$value=$attack_info['attacker']['a']*$attack_info['p']/100;
		$attack_info['bacevalue']=$value;//基础伤害

		//计算暴击
		$is_baoji = $this->random(['baoji'=>$attack_info['attacker']['baoji']/100,'bubaoji'=>1-$attack_info['attacker']['baoji']/100])=='baoji'?true:false;
		$value =$is_baoji?$value*$attack_info['attacker']['baoshang']/100:$value;

		$attack_info['valueup'][]=[//加成项
			'type'=>'baojiup',//加成类型
			'is'=>$is_baoji,//是否生效
			'p'=>$attack_info['attacker']['baoshang'],
		];

		//攻击法师增加伤害
		if($target->role['zhiye']=='法师'){
			$value=$value*1.2;

			$attack_info['valueup'][]=[
				'type'=>'zhiyeup',
				'is'=>true,//是否生效
				'p'=>1.2*100,
			];
		}
		


		//计算系别
		if(in_array($attack_info['type'], $this->xibie['type'])||in_array($target->role['xibie'],$this->xibie[$attack_info['attacker']['xibie']])){
			$attack_info['attacker']['mingzhong']=$attack_info['attacker']['mingzhong']+$this->xibie['xiaoguo']['mingzhongUP'];
			$value=$value+$value*$this->xibie['xiaoguo']['shanghaiUP']/100;

			$attack_info['valueup'][]=[
				'type'=>'xibieup',
				'is'=>true,//是否生效
				'p'=>$this->xibie['xiaoguo']['shanghaiUP']+100,
			];
		}

		//物理伤害加成
		if($attack_info['type']=='wuli'){
			$value=$value*(1+$this->role['wushang']/100);

			$attack_info['valueup'][]=[
				'type'=>'wuliup',
				'is'=>true,//是否生效
				'p'=>$this->role['wushang']+100,
			];
		}
		//法术伤害加成
		if($attack_info['type']=='fashu'){
			$value=$value*(1+$this->role['fashang']/100);

			$attack_info['valueup'][]=[
				'type'=>'fashuup',
				'is'=>true,//是否生效
				'p'=>$this->role['fashang']+100,
			];
		}
		$attack_info['value']=$value;
		print_r($attack_info);die;

		echo $attack_info['attacker']['name'].'攻击了'.$target->role['name'].'<br>';
		echo "造成伤害".$value.'<br>';
		echo isset($is_baoji)?$is_baoji:'';

		//调用目标的 受到攻击接口
		// $target[0]->underAttack($attack_info);
	}

	//受到攻击
	public function underAttack($attack_info){

		echo "string";
	}


	/**
	 * 概率命中
	 *
	 * @param array $ps array('a'=>0.5,'b'=>5.2,'c'=>0.4)
	 * @return string 返回上面数组的key
	 */
	public function random($ps){
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