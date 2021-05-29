<?php
namespace App\myclass\sszg\role;

//受到攻击
trait buff{

	public function isGetBuff($info){
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

	//获得buff
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

}



?>