<?php
namespace App\myclass\sszg\role;

//角色通用基本方法
trait bace{

	//血量改变
	public function change_h($value){

		if($this->role['h']+$value<=0){
			$value=-$this->role['h'];
		}

		$this->role['h']=$this->role['h']+$value;

		if($value<0){
			echo $this->role['name'].'受到伤害hp'.$value.'<br>';
		}

		return $value;
	}

	//获取属性值 $rang指定获取范围
	public function getAttrValue($attr,$rang=[]){
		$name=$attr;
		
		//buff属性
		$buff=[];
			
			foreach ($this->role['buff'] as $k => $v) {
				$value=0;
				if(isset($v['attrchange'][$attr])){
					//固定值
					$val=isset($v['attrchange'][$attr]['value'])?$v['attrchange'][$attr]['value']:0;
					//基础百分比值
					$p=isset($v['attrchange'][$attr]['p'])?$v['attrchange'][$attr]['p']*$this->role[$name]/100:0;
					$value=($val+$p)*$v['ceng'];
				}
				
				if($v['diejia']=='none'){
					$buff[$v['name']]=isset($buff[$v['name']])?($buff[$v['name']]>$value?$buff[$v['name']]:$value):$value;
				}else{
					$buff[]=$value;
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

	// 执行伤害
	private function hurt($arr){
		
		foreach ($arr as $k => $v) {
			$arr[$k]['hurt'] = $this->change_h(-$v['value']);
		}

		return $arr;
	}
	
}



?>