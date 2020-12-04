<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\myclass\sszg\sszg;
use App\myclass\sszg\fengwang;
use App\myclass\sszg\qipan;
use App\myclass\sszg\attack;
use App\myclass\sszg\buff\buff;
use DB;

class SszgController extends Controller
{

    private $ob=[
    'beforeAttack'=>'App\myclass\sszg\ob\xibie',
    ];
	public function list(){
        $rolebuff=[
            [
                'id'=>0,//唯一标识
                'releaser'=>'roleid',//释放者id
                'name'=>'aup',//buff名 同名buff一般仅生效最高效果
                'bufftype'=>'buff',//增益 减益
                'type'=>'attrup',// attrup|attrdown|other|{'chenmo'}   属性提升|属性降低|其他(或复合型)|其他类型
                'turn'=>1,//持续回合
                'ceng'=>1,//层数
                'diejia'=>'none',//叠加类型 name|ceng|none  同名叠加|层数叠加|无叠加
                'attrchange'=>['a'=>['p'=>10]],
                'other'=>[],
            ],
            [
                'id'=>0,//唯一标识
                'releaser'=>'roleid',//释放者id
                'name'=>'aup',//buff名 同名buff一般仅生效最高效果
                'bufftype'=>'buff',//增益 减益
                'type'=>'attrup',// attrup|attrdown|other|{'chenmo'}   属性提升|属性降低|其他(或复合型)|其他类型
                'turn'=>1,//持续回合
                'ceng'=>1,//层数
                'diejia'=>'name',//叠加类型 name|ceng|none  同名叠加|层数叠加|无叠加
                'attrchange'=>['a'=>['p'=>20]],
                'other'=>[],
            ],

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
                'value'=>0,//固定值
                'end'=>6,//生效到什么阶段
            ],
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
                'shanghai'=>22,//伤害加成
                'wushang'=>10,//物理伤害加成
                'fashang'=>10,//法术伤害加成
                'fajian'=>10,//法术减伤
                'wujian'=>10,//物理减伤
                'shangjian'=>10,//伤害减免                
                'kangbao'=>20,//抗暴
                'team'=>0,
                'status'=>1,
                'zhiye'=>'zhanshi',//职业
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
                'shanghai'=>22,//伤害加成
                'wushang'=>10,//物理伤害加成
                'fashang'=>10,//法术伤害加成
                'fajian'=>10,//法术减伤
                'wujian'=>10,//物理减伤
                'shangjian'=>10,//伤害减免
                'kangbao'=>20,//抗暴
                'team'=>0,
                'status'=>1,
                'zhiye'=>'fuzhu',//职业
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
                'shanghai'=>22,//伤害加成
                'wushang'=>10,//物理伤害加成
                'fashang'=>10,//法术伤害加成
                'fajian'=>10,//法术减伤
                'wujian'=>10,//物理减伤
                'shangjian'=>10,//伤害减免
                'kangbao'=>20,//抗暴
                'team'=>0,
                'status'=>1,
                'zhiye'=>'zhanshi',//职业
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
                'shanghai'=>22,//伤害加成
                'wushang'=>10,//物理伤害加成
                'fashang'=>10,//法术伤害加成
                'fajian'=>10,//法术减伤
                'wujian'=>10,//物理减伤
                'shangjian'=>10,//伤害减免
                'kangbao'=>20,//抗暴
                'team'=>0,
                'status'=>1,
                'zhiye'=>'fuzhu',//职业
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
                'shanghai'=>22,//伤害加成
                'wushang'=>10,//物理伤害加成
                'fashang'=>10,//法术伤害加成
                'fajian'=>10,//法术减伤
                'wujian'=>10,//物理减伤
                'shangjian'=>10,//伤害减免
                'kangbao'=>20,//抗暴
                'team'=>0,
                'status'=>1,
                'zhiye'=>'roudun',//职业
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
                'shanghai'=>22,//伤害加成
                'wushang'=>10,//物理伤害加成
                'fashang'=>10,//法术伤害加成
                'fajian'=>10,//法术减伤
                'wujian'=>10,//物理减伤
                'shangjian'=>10,//伤害减免
                'kangbao'=>20,//抗暴
                'team'=>1,
                'status'=>1,
                'zhiye'=>'roudun',//职业
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
                'shanghai'=>22,//伤害加成
                'wushang'=>10,//物理伤害加成
                'fashang'=>10,//法术伤害加成
                'fajian'=>10,//法术减伤
                'wujian'=>10,//物理减伤
                'shangjian'=>10,//伤害减免
                'kangbao'=>20,//抗暴
                'team'=>1,
                'status'=>1,
                'zhiye'=>'fuzhu',//职业
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
                'shanghai'=>22,//伤害加成
                'wushang'=>10,//物理伤害加成
                'fashang'=>10,//法术伤害加成
                'fajian'=>10,//法术减伤
                'wujian'=>10,//物理减伤
                'shangjian'=>10,//伤害减免
                'kangbao'=>20,//抗暴
                'team'=>1,
                'status'=>1,
                'zhiye'=>'roudun',//职业
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
                'shanghai'=>22,//伤害加成
                'wushang'=>10,//物理伤害加成
                'fashang'=>10,//法术伤害加成
                'fajian'=>10,//法术减伤
                'wujian'=>10,//物理减伤
                'shangjian'=>10,//伤害减免
                'kangbao'=>20,//抗暴
                'team'=>1,
                'status'=>1,
                'zhiye'=>'zhanshi',//职业
                'xibie'=>'feng',//系别
                'buff'=>[],//buff
                'linshiattr'=>[]//临时属性
            ],
            [
                'id'=>'yemeng1',
                'name'=>'耶梦加得',
                'position'=>11,//行列
                'h_max'=>1100000,
                'h'=>695546,//生命值
                'a'=>111816,//攻击力
                'd'=>2046,//防御力
                's'=>1852,//速度
                'mingzhong'=>100,//命中
                'baoji'=>100,//暴击
                'baoshang'=>150,//暴伤
                'shanghai'=>22,//伤害加成
                'wushang'=>10,//物理伤害加成
                'fashang'=>10,//法术伤害加成
                'fajian'=>10,//法术减伤
                'wujian'=>10,//物理减伤
                'shangjian'=>10,//伤害减免
                'kangbao'=>20,//抗暴
                'team'=>1,
                'status'=>1,
                'zhiye'=>'fashi',//职业
                'xibie'=>'shui',//系别
                'buff'=>[],//buff
                'linshiattr'=>[]//临时属性
            ]
        ];

        $qipan=new qipan(); //棋盘

        $fengwang=new fengwang($u0[0]);
        $yemeng1=new fengwang($u1[4]);

        $qipan->addRoles([$fengwang,$yemeng1]);//角色加入棋盘

        $buff= new buff;
        $buff->roleGetBuffOb($fengwang,'chenmo');
        $fengwang->round();//风王的回合

        
        
            $arr=[2,3,5,8,9,12,45,51,62,66];
            $val=51;
            $index=-1;
            $max=count($arr);
            $min=0;
            
            for ($i=0; $min < $max; $i++) { 
                $zhong=ceil(($max+$min)/2);
                echo $zhong;die;
                if($arr[$zhong]==$val){
                    echo 'key:'.$zhong;break;
                }elseif ($arr[$zhong]<$val) {
                    $min=$zhong;
                    $zhong=ceil(($max+$min)/2);
                }
            }
        

    }
}
