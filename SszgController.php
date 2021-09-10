<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\myclass\sszg\sszg;
use App\myclass\sszg\hero\zhanshi\fengwang as role;
use App\myclass\sszg\qipan;
use DB;
use App\myclass\rpg\role\hero\tongxiangyu as one;

class SszgController extends Controller
{

    public function role(){
        $role =new one();
    }

    public function index(){

        return view('admin/sszg/index');
    }

    public function fight(){

        return view('admin/sszg/fight');
    }

	public function fight_info(){
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
                'name'=>'0风王',
                'position'=>13,//行列
                'h_max'=>2000000,
                'h'=>2000000,//生命值
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
                'name'=>'0酒神',
                'position'=>23,//行列
                'h_max'=>2000000,
                'h'=>2000000,//生命值
                'a'=>33316,//攻击力
                'd'=>1770,//防御力
                's'=>1379,//速度
                'mingzhong'=>100,//命中
                'baoji'=>1,//暴击
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
                'name'=>'0哪吒',
                'position'=>33,//行列
                'h_max'=>2000000,
                'h'=>2000000,//生命值
                'a'=>100116,//攻击力
                'd'=>1873,//防御力
                's'=>1788,//速度
                'mingzhong'=>100,//命中
                'baoji'=>67,//暴击
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
                'name'=>'0拉斐尔',
                'position'=>22,//行列
                'h_max'=>2000000,
                'h'=>2000000,//生命值
                'a'=>66165,//攻击力
                'd'=>2242,//防御力
                's'=>2211,//速度
                'mingzhong'=>100,//命中
                'baoji'=>4,//暴击
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
                'name'=>'0海姆',
                'position'=>21,//行列
                'h_max'=>2000000,
                'h'=>2000000,//生命值
                'a'=>69764,//攻击力
                'd'=>3184,//防御力
                's'=>2016,//速度
                'mingzhong'=>100,//命中
                'baoji'=>0,//暴击
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
                'name'=>'1熊猫',
                'position'=>11,//行列
                'h_max'=>2000000,
                'h'=>2000000,//生命值
                'a'=>76856,//攻击力
                'd'=>2828,//防御力
                's'=>1987,//速度
                'mingzhong'=>100,//命中
                'baoji'=>0,//暴击
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
                'name'=>'1潘',
                'position'=>21,//行列
                'h_max'=>2000000,
                'h'=>2000000,//生命值
                'a'=>93269,//攻击力
                'd'=>2248,//防御力
                's'=>1920,//速度
                'mingzhong'=>100,//命中
                'baoji'=>0,//暴击
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
                'name'=>'1泰坦',
                'position'=>31,//行列
                'h_max'=>2000000,
                'h'=>2000000,//生命值
                'a'=>51958,//攻击力
                'd'=>3056,//防御力
                's'=>1802,//速度
                'mingzhong'=>100,//命中
                'baoji'=>0,//暴击
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
                'name'=>'1风王',
                'position'=>23,//行列
                'h_max'=>2000000,
                'h'=>2000000,//生命值
                'a'=>108511,//攻击力
                'd'=>1882,//防御力
                's'=>1778,//速度
                'mingzhong'=>100,//命中
                'baoji'=>70,//暴击
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
                'name'=>'1耶梦',
                'position'=>22,//行列
                'h_max'=>2000000,
                'h'=>2000000,//生命值
                'a'=>111816,//攻击力
                'd'=>2046,//防御力
                's'=>1852,//速度
                'mingzhong'=>100,//命中
                'baoji'=>48,//暴击
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

        $position=$this->getRandPosition();
        foreach ($u0 as $v) {
            $index=array_rand($position,1);
            $v['position']=$position[$index];
            unset($position[$index]);
            $qipan->addRole(new role($v));//角色加入棋盘
        }
        $position=$this->getRandPosition();
        foreach ($u1 as $v) {
            $index=array_rand($position,1);
            $v['position']=$position[$index];
            unset($position[$index]);
            $qipan->addRole(new role($v));//角色加入棋盘
        } 
        
        $qipan->gameBegin();//战斗开始

        $record =$qipan->getObAll('recordInfo');//数据记录对象

        $fight_info=$record->getInfo();//战斗数据
        $Roles=$record->getRoles();//角色初始信息
        // print_r($Roles);
        // print_r($fight_info);
        $info=[
            'roles'=>$Roles,
            'info'=>$fight_info,
        ];
        return json_encode($info);
    }

    private function getRandPosition($count=5)
    {
        $count=$count>=9?9:$count;
        $arr=[11,12,13,21,22,23,31,32,33];
        $keys = array_rand($arr,$count);
        $val=[];
        foreach ($keys as $v) {
            $val[]=$arr[$v];
        }
        return $val;
    }


    public function test(){
        $arr=[
            [0,0],
            [0,0],
            [0,0],
        ];

        $arr=[
            [0,0,0,0],
            [0,0,0,0],
            [0,0,0,0],
            [0,0,0,0],
            [0,0,0,0],
            [0,0,0,0],
        ];

        $arr=[
            [0,0,0],
            [0,0,0],
            [0,0,0],
        ];


        $x=count($arr[0]);
        $y=count($arr);
        if($y>$x){
            $short='x';
            $long='y';
        }else{
            $short='y';
            $long='x';
        }

        $count=0;

        for ($i=0; $i <${$long} ; $i++) { //循环长边
            for ($ii=0; $ii <${$short} ; $ii++) { //循环短边

                // 定位到当前元素

                $can0= (${$long}-$i)>=${$short}?${$short}:(${$long}-$i);//y方向的边长

                $can1= (${$short}-$ii)>=${$short}?${$short}:(${$short}-$ii);//x方向的边长
                
                //当前元素的最短边
                $can=$can1>$can0?$can0:$can1;

                //最短边长 即为 当前元素可形成的正方形数量（以当前元素为起点）
                $count+=$can;
                
            }
            
        }

        echo '共正方形' .$count;
    }
}
