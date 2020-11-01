<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\myclass\sszg\sszg;
use App\myclass\sszg\fengwang;
use App\myclass\sszg\qipan;
use App\myclass\sszg\attack;
use DB;

class SszgController extends Controller
{
	public function list(){
    
        $rolebuff=[
            'abuff'=>[//攻击buff
                [    

                    'name'=>'aup',//buff名
                    'buffid'=>'aup',//同类型id相同仅生效一个效果最高的buff
                    'pid'=>'',//父级id
                    'type'=>'buff',//增益 减益
                    'is_qusan'=>true,//可驱散
                    'turn'=>1,//持续回合
                    'value'=>0,//固定值
                    'value_p'=>10,//百分比
                ],
                [    

                    'name'=>'aup',//buff名
                    'buffid'=>'aup1',//同类型id相同仅生效一个效果最高的buff
                    'pid'=>'',//父级id
                    'type'=>'buff',//增益 减益
                    'is_qusan'=>true,//可驱散
                    'turn'=>1,//持续回合
                    'value'=>0,//固定值
                    'value_p'=>10,//百分比
                ]
            ]
        ];

        //临时属性栏
        $linshiattr=[
            [
                'type'=>'a',//属性
                'value_p'=>20,//百分比
                'value'=>0,//固定值
                'end'=>6,//生效到什么阶段
            ],
            [
                'type'=>'baoji',//属性
                'value_p'=>0,//百分比
                'value'=>10,//固定值
                'end'=>6,//生效到什么阶段
            ],
        ];

        $xibie_kezhi=[
            'xiaoguo'=>[
                'shanghaiUP'=>25,
                'mingzhongUP'=>15,
            ],//克制效果
            'shui'=>['huo'],
            'feng'=>['shui'],
            'huo'=>['feng'],
            'guang'=>['an'],
            'an'=>['guang'],
            'type'=>['wuli','mofa'],
        ];

        $u0=[
            [   
                'id'=>'fengwang0',
                'name'=>'风王',
                'position'=>13,//行列
                'h_max'=>1000000,
                'h'=>442705,//生命值
                'a'=>84673,//攻击力
                'd'=>1727,//防御力
                's'=>1967,//速度
                'mingzhong'=>100,//命中
                'baoji'=>50,//暴击
                'baoshang'=>150,//暴伤
                'wushang'=>10,//物理伤害加成
                'fashang'=>10,//法术伤害加成
                'kangbao'=>20,//抗暴
                'zhiye'=>'战士',//职业
                'xibie'=>'feng',//系别
                'buff'=>$rolebuff,//buff
                'linshiattr'=>$linshiattr//临时属性
            ],
            [
                'id'=>'jiushen0',
                'name'=>'酒神',
                'position'=>23,//行列
                'h_max'=>1000000,
                'h'=>385287,//生命值
                'a'=>33316,//攻击力
                'd'=>1770,//防御力
                's'=>1379,//速度
                'mingzhong'=>100,//命中
                'baoji'=>100,//暴击
                'baoshang'=>150,//暴伤
                'wushang'=>10,//物理伤害加成
                'fashang'=>10,//法术伤害加成
                'kangbao'=>20,//抗暴
                'zhiye'=>'辅助',//职业
                'xibie'=>'shui',//系别
                'buff'=>[],//buff
                'linshiattr'=>[]//临时属性
            ],
            [
                'id'=>'neza0',
                'name'=>'哪吒',
                'position'=>33,//行列
                'h_max'=>1000000,
                'h'=>110116,//生命值
                'a'=>100116,//攻击力
                'd'=>1873,//防御力
                's'=>1788,//速度
                'mingzhong'=>100,//命中
                'baoji'=>100,//暴击
                'baoshang'=>150,//暴伤
                'wushang'=>10,//物理伤害加成
                'fashang'=>10,//法术伤害加成
                'kangbao'=>20,//抗暴
                'zhiye'=>'战士',//职业
                'xibie'=>'shui',//系别
                'buff'=>[],//buff
                'linshiattr'=>[]//临时属性
            ],
            [
                'id'=>'lafei0',
                'name'=>'拉斐尔',
                'position'=>22,//行列
                'h_max'=>1000000,
                'h'=>532871,//生命值
                'a'=>66165,//攻击力
                'd'=>2242,//防御力
                's'=>2211,//速度
                'mingzhong'=>100,//命中
                'baoji'=>100,//暴击
                'baoshang'=>150,//暴伤
                'wushang'=>10,//物理伤害加成
                'fashang'=>10,//法术伤害加成
                'kangbao'=>20,//抗暴
                'zhiye'=>'辅助',//职业
                'xibie'=>'guang',//系别
                'buff'=>[],//buff
                'linshiattr'=>[]//临时属性
            ],
            [
                'id'=>'haimu0',
                'name'=>'海姆',
                'position'=>21,//行列
                'h_max'=>1000000,
                'h'=>1611917,//生命值
                'a'=>69764,//攻击力
                'd'=>3184,//防御力
                's'=>2016,//速度
                'mingzhong'=>100,//命中
                'baoji'=>100,//暴击
                'baoshang'=>150,//暴伤
                'wushang'=>10,//物理伤害加成
                'fashang'=>10,//法术伤害加成
                'kangbao'=>20,//抗暴
                'zhiye'=>'肉盾',//职业
                'xibie'=>'huo',//系别
                'buff'=>[],//buff
                'linshiattr'=>[]//临时属性
            ]
        ];
        $u1=[
            [
                'id'=>'xiongmao1',
                'name'=>'熊猫',
                'position'=>11,//行列
                'h_max'=>1000000,
                'h'=>1173333,//生命值
                'a'=>76856,//攻击力
                'd'=>2828,//防御力
                's'=>1987,//速度
                'mingzhong'=>100,//命中
                'baoji'=>100,//暴击
                'baoshang'=>150,//暴伤
                'wushang'=>10,//物理伤害加成
                'fashang'=>10,//法术伤害加成
                'kangbao'=>20,//抗暴
                'zhiye'=>'肉盾',//职业
                'xibie'=>'feng',//系别
                'buff'=>[],//buff
                'linshiattr'=>[]//临时属性

            ],
            [
                'id'=>'pan1',
                'name'=>'潘',
                'position'=>11,//行列
                'h_max'=>1000000,
                'h'=>1013385,//生命值
                'a'=>93269,//攻击力
                'd'=>2248,//防御力
                's'=>1920,//速度
                'mingzhong'=>100,//命中
                'baoji'=>100,//暴击
                'baoshang'=>150,//暴伤
                'wushang'=>10,//物理伤害加成
                'fashang'=>10,//法术伤害加成
                'kangbao'=>20,//抗暴
                'zhiye'=>'辅助',//职业
                'xibie'=>'feng',//系别
                'buff'=>[],//buff
                'linshiattr'=>[]//临时属性
            ],
            [
                'id'=>'taitan1',
                'name'=>'泰坦',
                'position'=>11,//行列
                'h_max'=>1000000,
                'h'=>743057,//生命值
                'a'=>51958,//攻击力
                'd'=>3056,//防御力
                's'=>1802,//速度
                'mingzhong'=>100,//命中
                'baoji'=>100,//暴击
                'baoshang'=>150,//暴伤
                'wushang'=>10,//物理伤害加成
                'fashang'=>10,//法术伤害加成
                'kangbao'=>20,//抗暴
                'zhiye'=>'肉盾',//职业
                'xibie'=>'shui',//系别
                'buff'=>[],//buff
                'linshiattr'=>[]//临时属性
            ],
            [
                'id'=>'fengwang1',
                'name'=>'风王',
                'position'=>11,//行列
                'h_max'=>1000000,
                'h'=>538986,//生命值
                'a'=>108511,//攻击力
                'd'=>1882,//防御力
                's'=>1778,//速度
                'mingzhong'=>100,//命中
                'baoji'=>100,//暴击
                'baoshang'=>150,//暴伤
                'wushang'=>10,//物理伤害加成
                'fashang'=>10,//法术伤害加成
                'kangbao'=>20,//抗暴
                'zhiye'=>'战士',//职业
                'xibie'=>'feng',//系别
                'buff'=>[],//buff
                'linshiattr'=>[]//临时属性
            ],
            [
                'id'=>'yemeng1',
                'name'=>'耶梦加得',
                'position'=>11,//行列
                'h_max'=>1000000,
                'h'=>695546,//生命值
                'a'=>111816,//攻击力
                'd'=>2046,//防御力
                's'=>1852,//速度
                'mingzhong'=>100,//命中
                'baoji'=>100,//暴击
                'baoshang'=>150,//暴伤
                'wushang'=>10,//物理伤害加成
                'fashang'=>10,//法术伤害加成
                'kangbao'=>20,//抗暴
                'zhiye'=>'法师',//职业
                'xibie'=>'shui',//系别
                'buff'=>[],//buff
                'linshiattr'=>[]//临时属性
            ]
        ];

        $qipan=new qipan; //棋盘
        $qipan->xibie($xibie_kezhi);

        $fengwang=new fengwang($u0[0]);
        $fengwang->qipan($qipan);
        $yemeng1=new fengwang($u1[4]);
        $yemeng1->qipan($qipan);

        $qipan->addRole($fengwang);
        $qipan->addRole($yemeng1);

        $fengwang->round();//风王的回合
        // probability 概率的英文

        

    }
}
