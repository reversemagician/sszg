<?php 
namespace App\myclass\sszg\buff;
/**
* 沉默
*/
class chenmo
{
	//监听阶段
	public function beforeAttack($info,$qipan){
		echo '被沉默了<br>';

		return $info;
	}
	
}
?>