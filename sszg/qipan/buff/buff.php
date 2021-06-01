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