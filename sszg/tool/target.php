<?php 
namespace App\myclass\sszg\tool;

 /**
 * 通用目标选择
 */
 class target
 {
 	private $rang=[];//array
 	private $self='';//obj
 	private $info=[];
 	private $tips=[//标记
 		'pai'=>false,//前中后排
 		'hang'=>false,//一二三行
 		'zhiye'=>false,//职业标记
 	];
 	public function getTarget($self,$info,$qipan){

 		//全部角色
 		$this->rang=$qipan->getRoles('all');
 		//自身
 		$this->self=$self;
 		//重置标记
 		$this->reTips();

 		$info=[
 			'rang'=>[],//范围限定//all全部|enemy敌人|teammate队友|qian前排|zhong中|hou后排|one第一行|two第二|three第三|fashi法师|zhanshi战士|fuzhu辅助|roudun肉盾|life存活|death死亡|kongzhi控制|
 			'where'=>'max_h_max',//筛选条件 max_*最大属性|min_*最小属性|rand随机|min_.h血量百分比最低|max_.h血量百分比最高
 			'number'=>1,//数量
 			'remove'=>[],//排除的角色ID
 		];

 		$this->info=$info;

 		//排除id
 		if(isset($this->info['remove'])){
	 		$this->remove($this->info['remove']);
	 	}

 		//限定范围
 		if(isset($this->info['rang'])){

	 		foreach ($this->info['rang'] as $k => $v) {
	 			$this->rang($v);
	 		}
	 	}


	 	//筛选条件 及数量
	 	$this->where();
	 	
	 	foreach ($this->rang as $v) {
 			echo $v->getAttrString('name');
 		}

 	}

 //筛选条件及数量
 	private function where(){

 		if(count($this->rang)<=$this->info['number']){
 			return false;
 		}
 		
 		if($this->info['where']=='rand'){
 			$this->rand_();
 		}else{
 			$this->max_min();
 		}
 	}

 	private function max_min(){

 		$arr =explode('max_',$this->info['where']);

 		$type='max';
 		if(!isset($arr[1])){

 			$arr =explode('min_',$this->info['where']);
 			$type='min';
 		}
 		
 		$val=[];
 		foreach ($this->rang as $k => $v) {
 			if(in_array($arr[1],['.h'])){
 				$val[$k]=$v->getAttrValue('h')/$v->getAttrValue('h_max');
 			}else{
 				$val[$k]=$v->getAttrValue($arr[1]);
 			}
			
			
		}

		for ($i=0; $i <$this->info['number'] ; $i++) { 
			if($type=='max'){
				unset($val[array_search(max($val), $val)]);
			}else{
				unset($val[array_search(min($val), $val)]);
			}
		}
 		foreach ($val as $k => $v) {
 			unset($this->rang[$k]);
 		}

 	}


 	//筛选条件 随机 
 	private function rand_(){
 			for ($i=0; $i < (count($this->rang)-$this->info['number']); $i++) { 
 				unset($this->rang[rand(0,count($this->rang)-1)]);
 			}
 	}

 	//重置标记
 	private function reTips(){
 		foreach ($this->tips as $k=> $v) {
 			$this->tips[$k]=false;
 		}
 	}


 	//范围限定
 	private function rang($rang){
		call_user_func_array([$this,$rang],[]);
 	}


//排除特定id角色
 	private function remove($remove){
 		foreach ($this->rang as $k => $v) {
 			if(in_array($v->getAttrString('id'),$remove)){
 				unset($this->rang[$k]);
 			}
 		}
 	}

//rang
 	private function all(){}
 	private function none(){}
 	private function enemy(){
 		$team=$this->self->getAttrString('team');
 		foreach ($this->rang as $k =>$v) {
 			if($team==$v->getAttrString('team')){
 				unset($this->rang[$k]);
 			}
 		}
 	}
 	private function teammate(){
 		$team=$this->self->getAttrString('team');
 		foreach ($this->rang as $k =>$v) {
 			if($team!=$v->getAttrString('team')){
 				unset($this->rang[$k]);
 			}
 		}
 	}

 	//过滤非指定位置的角色
 	private function position($position){
 		foreach ($this->rang as $k =>$v) {
 			if(!in_array($v->getAttrString('position'),$position)){
 				unset($this->rang[$k]);
 			}
 		}
 	}

 	//前中后排
 	private function pai($title){
 		if($this->tips['pai']){
 			return null;
 		}
 		$position=[];
 		if(in_array('qian',$this->info['rang'])){
 			$position =array_merge($position,[11,21,31]);
 		}
 		if(in_array('zhong',$this->info['rang'])){
 			$position =array_merge($position,[12,22,32]);
 		}
 		if(in_array('hou',$this->info['rang'])){
 			$position =array_merge($position,[13,23,33]);
 		}
 		$this->tips['pai']=true;
 		return $position;
 	}
 	private function qian(){
 		$position =$this->pai(__FUNCTION__);
 		if($position==null){
 			return false;
 		}
 		$this->position($position);
 	}
 	private function zhong(){
 		$position =$this->pai(__FUNCTION__);
 		if($position==null){
 			return false;
 		}
 		$this->position($position);
 	}
 	private function hou(){
 		$position =$this->pai(__FUNCTION__);
 		if($position==null){
 			return false;
 		}
 		$this->position($position);
 	}
 	//行数
 	private function hang($title){
 		if($this->tips['hang']){
 			return null;
 		}
 		$position=[];
 		if(in_array('one',$this->info['rang'])){
 			$position =array_merge($position,[11,12,13]);
 		}
 		if(in_array('two',$this->info['rang'])){
 			$position =array_merge($position,[21,22,23]);
 		}
 		if(in_array('three',$this->info['rang'])){
 			$position =array_merge($position,[31,32,33]);
 		}
 		$this->tips['hang']=true;
 		return $position;
 	}
 	private function one(){
 		$position =$this->hang(__FUNCTION__);
 		if($position==null){
 			return false;
 		}
 		$this->position($position);
 	}
 	private function two(){
 		$position =$this->hang(__FUNCTION__);
 		if($position==null){
 			return false;
 		}
 		$this->position($position);
 	}
 	private function three(){
 		$position =$this->hang(__FUNCTION__);
 		if($position==null){
 			return false;
 		}
 		$this->position($position);
 	}
 	//过滤非指定职业的角色
 	private function zhiye_($zhiye){
 		foreach ($this->rang as $k =>$v) {
 			if(!in_array($v->getAttrString('zhiye'),$zhiye)){
 				unset($this->rang[$k]);
 			}
 		}
 	}
 	//zhiye
 	private function zhiye($title){
 		if($this->tips['zhiye']){
 			return null;
 		}
 		$zhiye=[
 			in_array('fashi',$this->info['rang'])?'fashi':'',
 			in_array('zhanshi',$this->info['rang'])?'zhanshi':'',
 			in_array('fuzhu',$this->info['rang'])?'fuzhu':'',
 			in_array('roudun',$this->info['rang'])?'roudun':'',
 		];
 		$this->tips['zhiye']=true;
 		return $zhiye;
 	}
 	private function fashi(){
 		$zhiye =$this->zhiye(__FUNCTION__);
 		if($zhiye==null){
 			return false;
 		}
 		$this->zhiye_($zhiye);
 	}
 	private function zhanshi(){
 		$zhiye =$this->zhiye(__FUNCTION__);
 		if($zhiye==null){
 			return false;
 		}
 		$this->zhiye_($zhiye);
 	}
 	private function fuzhu(){
 		$zhiye =$this->zhiye(__FUNCTION__);
 		if($zhiye==null){
 			return false;
 		}
 		$this->zhiye_($zhiye);
 	}
 	private function roudun(){
 		$zhiye =$this->zhiye(__FUNCTION__);
 		if($zhiye==null){
 			return false;
 		}
 		$this->zhiye_($zhiye);
 	}



 }

?>