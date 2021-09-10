
// (function () {
// 	alert('fight')
// })()

////\\\\流程start
var procee_index=0;//流程标记
var procee=[];//当前流程
var procees=[];//全部流程集合
//普通攻击
procees['skill_ptgj']=['attackInit','moveToEnemy','attack','back_','roundEnd','nextRole'];
//狂乱突袭
procees['skill_kltx']=['attackInit','moveToEnemyCentre','skill_kltx','back_','roundEnd','nextRole'];
//致命审判
procees['skill_zmsp']=['attackInit','moveToEnemy','skill_zmsp','back_','roundEnd','nextRole'];

procee=procees['skill_ptgj'];

// 确定流程
function get_procee(procee_index){
	if(procee_index==0){
		var skill_id=fight_info[round_index][round_role_index]['attacker']['skill_id'];
		switch(skill_id)
		{
		    case 'skill_kltx':
		        procee=procees['skill_kltx'];
		        break;
		    case 'skill_ptgj':
		        procee=procees['skill_ptgj'];
		        break;
		    case 'skill_zmsp':
		        procee=procees['skill_zmsp'];
		        break;
		    default:
		        procee=procees['skill_ptgj'];
		}
	}
}

//执行流程角色行动
function cue_process(){

	if(procee.length==procee_index){
		console.log('cue_process() 流程停止');
		return false;
	}
	var count_round =Object.keys(fight_info).length;
	
	//确定流程
	get_procee(procee_index);

	//下一流程
	var next_tip=procee[procee_index];
	
	procee_index++;

	// console.log(next_tip);
	//执行下个流程
	
	eval(next_tip+'()');
}
////\\\\流程end

///战斗数据
var fight_info=[];//全部战斗数据
var round_index=1;//回合数
var round_role_index=0;//回合内角色行动标记

var targets=[];//目标
var body='';//行动主体
var move_position='';//移动定位

	
