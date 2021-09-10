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
		'round_max'=>20,//最大回合数
		'round_level'=>0,//当前回合阶段 0是回合开始阶段 1精灵阶段 2英雄行动阶段 3回合结束阶段
	];

	// 游戏开始
	public function gameBegin(){
		$this->obGameStart();
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

	public function getRoundInfo(){
		return $this->round_info;
	}

	// 游戏结束
	private function gameEnd(){
		$this->obGameEnd();
	}

	//多回合循环运行
	private function round_loop(){

		for ($i=1; $i <= $this->round_info['round_max']; $i++) { 

			if($this->is_game_over()){
				//回合循环结束
				break;
			}
			
			//单回合开始
			$this->roundBegin();

		}
	}
	
	//单回合开始
	private function roundBegin(){
		$this->obRoundStart();
		$this->round_level_begin();

	}

	//单回合各个阶段开始
	private function round_level_begin(){
		$this->round_level_running();
		return $this->roundEnd();
		
	}
	// 回合结束
	private function roundEnd(){
		$this->obRoundEnd();	
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
	}

	private function round_level0(){
		// echo '回合开始阶段：；<br>';
	}
	private function round_level1(){
		// echo '精灵阶段：；<br>';
	}
	private function round_level2(){
		$this->ObRoundLevel2();
		$this->roleAction();
	}
	private function round_level3(){
		// echo '回合结束阶段：；<br>';
	}

	//判断游戏是否结束
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
	 		];


		$life =$this->useTool('target','getTarget',[$info,$this->getRoleByWhere('fristteam0')]);

		if (empty($life)) {
			$this->game_status=2;
			return true;
		}

		$life =$this->useTool('target','getTarget',[$info,$this->getRoleByWhere('fristteam1')]);
		if (empty($life)) {
			$this->game_status=2;
			return true;
		}

		return false;
	}

}
?>