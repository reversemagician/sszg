<?php
namespace App\myclass\sszg\role\ob;
// 系别克制 攻击装饰
class xibie{

	private $kezhi=[
        'shuxingUp'=>[
            'shanghai'=>25,//伤害加成属性
            'mingzhong'=>15,//命中属性提升
        ],//克制效果 

        'shui'=>['huo'],
        'feng'=>['shui'],
        'huo'=>['feng'],
        'guang'=>['an'],
        'an'=>['guang'],

        'rang'=>[//生效范围
    		'wuliattack','mofaattack'
    	]

    ];

   
    // $info 攻击信息
	public function getResult($info,$qipan){

		$releaser = $qipan->getRole($info['releaser']);
		$target = $qipan->getRole($info['target']);
		if(in_array($target->getAttrString('xibie'),$this->kezhi[$releaser->getAttrString('xibie')])){//检测系别克制

			if(in_array($info['type'],$this->kezhi['rang'])){

				//克制附带属性提升
				foreach ($this->kezhi['shuxingUp'] as $k => $v) {
					if (isset($info['attack_info']['main']['attributechange'][$k])) {
						$info['attack_info']['main']['attributechange'][$k]=$info['attack_info']['main']['attributechange'][$k]+$v;
					}else{
						$info['attack_info']['main']['attributechange'][$k]=$v;
					}
				}
			}
		}

		return $info;
	}

	
}