<?php
namespace App\myclass\sszg\buff;

/**
* buff 释放类
*/
class buff
{
	public $buff=[
		'chenmo'=>[
			'obj'=>'App\myclass\sszg\buff\chenmo',//对象
			[
				'oblevel'=>'beforeAttack',//监听阶段
				'func'=>'beforeAttack',//对应buff类方法
			],
		],
		// 'xuanyun'=>[
		// 	'obj'=>'App\myclass\sszg\buff\xuanyun',//对象
		// 	[
		// 		'oblevel'=>'beforeAttack',//监听阶段
		// 		'func'=>'beforeAttack',//对应buff类方法
		// 	],
		// ],
	];

	//获取buff的统一格式
	public function getBuffFormat($arr=[]){

		$buff=[    
			'id'=>0,//唯一标识
			'releaser'=>'roleid',//释放者id
	        'name'=>'',//buff名 同名buff一般仅生效最高效果
	        'bufftype'=>'buff',//增益 减益
	        'type'=>'attrup',// attrup|attrdown|other|{'chenmo','xuanyun'}|''   属性提升|属性降低|其他(或复合型)|其他类型
	        'turn'=>1,//持续回合
	        'ceng'=>1,//层数
	        'diejia'=>'none',//叠加类型 name|ceng|none  同名叠加|层数叠加|无叠加
	        'attrchange'=>[],//属性改变效果 属性增益或属性减益
	        'other'=>[
            	// 'noqusan',//不可驱散
            	
	        ],
        ];

		return array_merge($buff,$arr);
	}

	public $buff_type=[
		'buff'=>[
		],
		'debuff'=>[
			'chenmo'
		],
		'kongzhi'=>[
			'chenmo'
		]
	];


	//扩展一个buff
	public function addBuff($buffname,$buffinfo){
		$this->buff[$buffname]=$buffinfo;
	}

	//获取buff信息
	public function getBuffInfo($buffid){
		if(isset($this->buff[$buffid])){
			if(is_object($this->buff[$buffid]['obj'])){
				return $this->buff[$buffid];
			}else{
				$this->buff[$buffid]['obj']=new $this->buff[$buffid]['obj'];
				return $this->buff[$buffid];
			}
		}
	}

	//给角色添加buff监听
	public function roleGetBuffOb($role,$buffname){
		$buff=$this->getBuffInfo($buffname);
		$obj=$buff['obj'];
		unset($buff['obj']);
		foreach ($buff as $k => $v) {
			$role->addOb($v['oblevel'],$buffname,$obj,$v['func']);
		}
	}
}
?>