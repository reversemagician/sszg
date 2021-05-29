<?php 
namespace App\myclass\sszg\qipan\tool;

 /**
 * 通用目标选择器
 */
 class target
 {
 	private $rang=[];//array
 	private $info=[];
 	private $tips=[//标记
 		'pai'=>false,//前中后排
 		'hang'=>false,//一二三行
 		'zhiye'=>false,//职业标记
 	];

 	/**
	 * 获取目标id
	 *@param array $info 获取条件信息 
	 *@param object $qipan 棋盘 
	 *@return array $ids 目标id数组
	 */
 	public function getTargetId($info,$qipan){

 		$this->workResult($info,$qipan);
 		
		$ids=[];

		foreach ($this->rang as $key => $v) {
			$ids[]=$v->getAttrString('id');
		}
		return $ids;
 	}

 	/**
	 * 获取目标对象实例
	 *@param array $info 获取条件信息
	 *@param object $qipan 棋盘 
	 *@return array $ids 目标实例数组 |null
	 */
 	public function getTarget($info,$qipan){
		/*
			$info=[
	 			'rang'=>[],//范围限定//enemy敌人|teammate队友|qian前排|zhong中|hou后排|one第一行|two第二|three第三|fashi法师|zhanshi战士|fuzhu辅助|roudun肉盾|life存活|death死亡|
	 			'where'=>'max_h',//筛选条件 max_*最大属性|min_*最小属性|rand随机|min_.h血量百分比最低|max_.h血量百分比最高
	 			'number'=>1,//数量
	 			'remove'=>[],//排除的角色ID
	 			'self_team'=>1,//自身队伍 当需要判断敌人或友方时需要该值
	 		];
 		*/

 		$this->workResult($info,$qipan);
 		
 		return $this->rang;
 	}

 	/**
	 * 获取目标 根据几种常用场景
	 *@param string $title 获取条件信息 'enemy_rand_zhonghou' 第一个单词enemy或teammate 敌人或队友
	 *@param object $role 角色自身 	 
	 *@param array $extends 扩展参数 
	 *@return object $qipan 目标实例数组 |null
	 */
	public function getTargetByCommon($title,$role,$extends=[]){
		$team=explode('_',$title)[0];//阵营
		$title=str_replace('enemy_', '', $title);
		$title=str_replace('teammate_', '', $title);
		
		$titles=[
			'rand_zhonghou',//优先中后排 随机目标 指定数量， $extends=['number'=>all];
			'attack',//通用普通攻击目标 
		];

		foreach ($titles as $k => $v) {
			if ($title==$v) {
				return $this->{$v}($team,$role,$extends);
			}
			
		}

		return [];
	}

 	// 计算结果
 	private function workResult($info,$qipan){
 		//全部角色
 		$this->rang=$qipan->getAllRoles();
 		//重置标记
 		$this->reTips();

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
 		
 		$y=count($this->rang)-$this->info['number'];

		for ($i=0; $i < $y; $i++) { 
			unset($this->rang[array_rand($this->rang)]);
			;
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
 	private function enemy(){
 		foreach ($this->rang as $k =>$v) {
 			if($this->info['self_team']==$v->getAttrString('team')){
 				unset($this->rang[$k]);
 			}
 		}
 	}
 	private function teammate(){
 		foreach ($this->rang as $k =>$v) {
 			if($this->info['self_team']!=$v->getAttrString('team')){
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
 	//职业
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
 	private function life(){
 		foreach ($this->rang as $k => $v) {
 			if($v->getAttrString('h')<=0){
 				unset($this->rang[$k]);
 			}
 		}
 	}
 	private function death(){
 		foreach ($this->rang as $k => $v) {
 			if($v->getAttrString('h')>0){
 				unset($this->rang[$k]);
 			}
 		}
 	}


//常用目标筛选方法
 	//优先中后排 随机目标 指定数量
 	private function rand_zhonghou($team,$role,$extends=[]){
 		$extends=empty($extends)?['number'=>1]:$extends;
 
 		$info=[
	 			'rang'=>['life','zhong','hou',$team],
	 			'where'=>'rand',//筛选条件 
	 			'number'=>$extends['number'],
	 			'remove'=>[],//排除的角色ID
	 			'self_team'=>$role->getAttrString('team'),
	 		];

	 	$target = $this->getTargetId($info,$role->qipan);

	 	if(count($target)==$extends['number']){
	 		return $target;
	 	}

	 	if (empty($target)) {
	 		$info=[
	 			'rang'=>['life',$team],
	 			'where'=>'rand',//筛选条件 
	 			'number'=>$extends['number'],
	 			'remove'=>[],//排除的角色ID
	 			'self_team'=>$role->getAttrString('team'),
	 		];

	 		return $this->getTargetId($info,$role->qipan);
	 	}

	 	//中后排数量不足
	 	if (count($target)<$extends['number']) {
	 		$info=[
	 			'rang'=>['life',$team,'qian'],
	 			'where'=>'rand',//筛选条件 
	 			'number'=>$extends['number']-count($target),
	 			'remove'=>[],//排除的角色ID
	 			'self_team'=>$role->getAttrString('team'),
	 		];

	 		return array_merge($target,$this->getTargetId($info,$role->qipan));
	 	}
 	}

 	//通用普通攻击
 	private function attack($team,$role,$extends=[]){
 		$position=$role->getAttrString('position');
 		$row =substr($position, 0,1);
 		$info=[
	 			'rang'=>['qian','life',$team],
	 			'where'=>'rand',
	 			'number'=>3,
	 			'remove'=>[],
	 			'self_team'=>$role->getAttrString('team'),
	 		];

	 	//同排优先级
	 	$p=[3,2,1];
	 	unset($p[array_search($row,$p)]);
	 	$p=array_merge([$row],$p);

	 	$target=$this->getTargetId($info,$role->qipan);

	 	if (empty($target)) {
	 		$info=[
	 			'rang'=>['zhong','life',$team],
	 			'where'=>'rand',
	 			'number'=>3,
	 			'remove'=>[],
	 			'self_team'=>$role->getAttrString('team'),
	 		];
	 		$target=$this->getTargetId($info,$role->qipan);

	 		if (empty($target)) {
	 			//末位后排
		 		$info=[
		 			'rang'=>['hou','life',$team],
		 			'where'=>'rand',
		 			'number'=>3,
		 			'remove'=>[],
		 			'self_team'=>$role->getAttrString('team'),
		 		];
		 		$target=$this->getTargetId($info,$role->qipan);

		 		$target_key=0;
		 		$p_key=2;
		 		foreach ($target as $k => $v) {
		 			$r=substr($v->getAttrString('position'),0,1);
		 			if($p_key>=array_search($r,$p)){
		 				$p_key=array_search($r,$p);
		 				$target_key=$k;
		 			}
		 		}
		 		return $target[$target_key];
		 		
		 	}else{
		 		//顺位中排
		 		$target_key=0;
		 		$p_key=2;
		 		foreach ($target as $k => $v) {
		 			$r=substr($v->getAttrString('position'),0,1);
		 			if($p_key>=array_search($r,$p)){
		 				$p_key=array_search($r,$p);
		 				$target_key=$k;
		 			}
		 		}
		 		return $target[$target_key];
		 	}
	 	}else{
	 		//优先前排
	 		$target_key=0;
	 		$p_key=2;
	 		foreach ($target as $k => $v) {
	 			$r=substr($v->getAttrString('position'),0,1);
	 			if($p_key>=array_search($r,$p)){
	 				$p_key=array_search($r,$p);
	 				$target_key=$k;
	 			}
	 		}
	 		return $target[$target_key];
	 	}
 	}	

 }

?>