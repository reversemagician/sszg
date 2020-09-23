<?php
namespace App\myclass\sszg;

/**
 * 闪烁之光 战斗类 Start fighting
 */
class sszg 
{

	public $udata=[];//对阵双方数据
	
	public $roundmax=30;//最大回合
	public $roundindex=1;//当前回合
	public $isend=false;//胜负已分
	public $fdata=[];//战斗过程数据

	public function __construct($udata0,$udata1)
	{
		echo "闪烁之光 战斗<br>";
		
		//初始化
		$this->inti($udata0,$udata1);
	}

	//初始化战斗数据
	public function inti($udata0,$udata1){
		
		foreach ($udata0 as $k => $v) {
			$udata0[$k]['a_s']=$v['h'];
			$udata0[$k]['d_s']=$v['d'];
			$udata0[$k]['s_s']=$v['s'];
			$udata0[$k]['baoji_s']=$v['baoji'];
			$udata0[$k]['baoshang_s']=$v['baoshang'];
			$udata0[$k]['wushang_s']=$v['wushang'];
			$udata0[$k]['fashang_s']=$v['fashang'];
			$udata0[$k]['chushou']=false;//每回合出手标记
			$udata0[$k]['death']=false;//阵亡标记
			$udata0[$k]['udata']=0;//左右方阵标记
			$udata0[$k]['buff']=[];//buff

		}
		foreach ($udata1 as $k => $v) {
			$udata1[$k]['a_s']=$v['h'];
			$udata1[$k]['d_s']=$v['d'];
			$udata1[$k]['s_s']=$v['s'];
			$udata1[$k]['baoji_s']=$v['baoji'];
			$udata1[$k]['baoshang_s']=$v['baoshang'];
			$udata1[$k]['wushang_s']=$v['wushang'];
			$udata1[$k]['fashang_s']=$v['fashang'];
			$udata1[$k]['chushou']=false;//每回合出手标记
			$udata1[$k]['death']=false;//阵亡标记
			$udata1[$k]['udata']=1;//左右方阵标记
			$udata1[$k]['buff']=[];//buff

		}
		$this->udata=array_merge($udata0,$udata1);
	}

	//主程序
	public function main(){
		$this->startFighting();
	}

	//战斗开始
	public function startFighting(){

		//循环回合
		for ($i=1; $i <= $this->roundmax; $i++) { 

			if($this->isend){break;}//一方胜出
			$this->roundindex=$i;

			echo '回合'.$i.':';
			$this->round();//开始回合

		}
	}

	//每回合
	public function round(){
		echo "战斗中。。。<br>";
		//阶段一 神器阶段
		$this->roundStage1();

		//阶段二 精灵阶段
		$this->roundStage2();

		//阶段三 主要角色战斗
		$this->roundStage3();

		//阶段三 回合结束 结算 记录数据
	}

	//回合阶段一
	public function roundStage1(){

	}

	//回合阶段二
	public function roundStage2(){

	}

	//回合阶段二
	public function roundStage3(){
		$i=$this->roundindex%4;//阶段 1 2 3 0
		for ($i=0; $i <=10 ; $i++) { 
			$new=$this->chushouRole();//获取出手的角色
			if(is_array($new)){
				
				echo $new['name'].'正在出手<br>';
				
			}else{
				// 全部行动结束
				echo '所有角色已出手，主要战斗阶段结束，阶段结算中。。。<br>';
				break;
			}

		}
		//主要战斗结束
		$this->rechushouRole();//恢复出手标记
	}

	// 战斗结束
	public function endOfBattle(){
		//数据统计 战斗结算
	}

	//获取出手角色
	public function chushouRole(){
		$chushou=['s_s'=>-1];
		$chushou_k=-1;

		foreach ($this->udata as $k => $v) {
			if($v['s_s']>=$chushou['s_s']&&!$v['chushou']){
				$chushou=$v;
				$chushou_k=$k;
			}
		}

		$maxchushou='';
		if($chushou_k!=-1){
			$maxchushou=$this->udata[$chushou_k];
			$this->udata[$chushou_k]['chushou']=true;//标记已经出手
		}
		return $maxchushou;
	}

	//恢复出手状态
	public function rechushouRole(){
		foreach ($this->udata as $k => $v) {
			$this->udata[$k]['chushou']=false;
		}
	}


	//技能运算器

	//目标选择器

	//获得buff

	//失去buff

	//角色死亡

	//角色复活
}
?>