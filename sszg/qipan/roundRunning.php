<?php
namespace App\myclass\sszg\qipan;

/**
 * 回合运行器
 */
trait roundRunning
{
	private $game_status=1;//0尚未开始 1进行中 2结束 

	private $round_info=[//回合信息
		'round'=>1,//当期回合数
		'round_max'=>20,//
		'round_level'=>0,//当前回合阶段 0是回合开始阶段 1精灵阶段 2英雄行动阶段 3回合结束阶段
		'action_role_id'=>1,//英雄行动阶段 正在行动的英雄的id

	];

	// 游戏开始
	public function gameBegin(){
		$this->round_loop();
		$this->gameEnd();
	}

	// 声明游戏结束
	public function game_over(){
		$this->game_status=2;
	}

	// 获取回合
	public function getRound(){
		return $this->round_info['round'];
	}

	// 游戏结束
	private function gameEnd(){
		
		echo "战斗结束,正在结算数据<br>";

		//数据结算
		$this->dataWork();
	}

	//多回合循环运行
	private function round_loop(){
		
		if( $this->round_info['round']==($this->round_info['round_max']+1)||
			$this->is_game_over()){
			//回合循环结束
			return false;
		}else{

			//单回合开始
			$this->roundBegin();
		}
		//循环自身
		$this->round_loop();
	}
	
	//单回合开始
	private function roundBegin(){
		echo "第{$this->round_info['round']}回合开始：<br>";
		$this->round_level_begin();

	}

	//单回合各个阶段开始
	private function round_level_begin(){
		$this->round_level_running();
		return $this->roundEnd();
		
	}
	// 回合结束
	private function roundEnd(){
		echo "第{$this->round_info['round']}回合结束<br><br>";	
		if ($this->is_game_over()) {return false;};
		$this->round_info['round']++;
	}
	
	//单个回合各阶段
	private function round_level_running(){
		
		$this->round_level0();if ($this->is_game_over()) {return false;};
		$this->round_info['round_level']=1;
		$this->round_level1();if ($this->is_game_over()) {return false;}
		$this->round_info['round_level']=2;
		$this->round_level2();if ($this->is_game_over()) {return false;}
		$this->round_info['round_level']=3;
		$this->round_level3();if ($this->is_game_over()) {return false;}
		$this->round_info['round_level']=0;
		return false;
	}

	private function round_level0(){
		// echo '回合开始阶段：；<br>';
	}
	private function round_level1(){
		// echo '精灵阶段：；<br>';
	}
	private function round_level2(){
		echo '英雄行动阶段：<br>';
		$this->roleAction();
	}
	private function round_level3(){
		// echo '回合结束阶段：；<br>';
	}

	public function is_game_over(){

		if($this->round_info['round_max']<$this->round_info['round']){
			$this->game_status=2;
			return true;
		}

		$info=[
	 			'rang'=>['life','enemy'],
	 			'where'=>'rand',
	 			'number'=>100,
	 			'remove'=>[],
	 			'self_team'=>1,
	 		];

		$life =$this->useTool('target','getTarget',[$info,$this]);

		if (empty($life)) {
			$this->game_status=2;
			return true;
		}
		$info['self_team']=0;

		$life =$this->useTool('target','getTarget',[$info,$this]);
		if (empty($life)) {
			$this->game_status=2;
			return true;
		}

		return false;
	}

	// 战斗结算
	private function dataWork(){

	}
}
?>