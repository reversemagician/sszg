// (function () {
// 	alert('role_anime')
// })()

var play_speed=1;//播放速度
var gezi=60;//格子高宽
//同回合下一个角色行动
function nextRole(){
	
	//回合是否结束
	var l =Object.keys(fight_info[round_index]).length;
	if(l-1<=round_role_index){
		console.log('nextRole() 回合结束');
		
		//下一回合
		round_index++;//回合数加一
		round_role_index=0;//重置下一个角色
		procee_index=0;//重置流程标记
		$('#round_number').text(round_index);
		var count_round =Object.keys(fight_info).length;
		if(count_round <round_index){

			//重置
			round_index=1;
			round_role_index=0;
			procee_index=0;
			play_new=false;
			console.log('nextRole() 战斗结束');
			return false;
		}
		// 继续执行流程
		cue_process();

	}else{
		// 当前回合 下一个角色行动

		round_role_index++;//标记下一个角色
		procee_index=0;//重置流程标记

		// 继续执行流程
		cue_process();
	}
}

//移动到敌人前面
function moveToEnemy(){
	var element=body;
	var ele=$(body);

	$ele_position=ele.offset();
	$tar_position=$(targets[0]).offset();

	var $x=$ele_position.left>=$tar_position.left?$tar_position.left-$ele_position.left+gezi:$tar_position.left-$ele_position.left-gezi;

	var $y=$ele_position.top>=$tar_position.top?$tar_position.top-$ele_position.top:$tar_position.top-$ele_position.top;

	ele.attr('anime_x',$x);
	ele.attr('anime_y',$y);

	$('.role').each(function () {
		$(this).css(' z-index',1);
	})
	ele.css(' z-index',2);

	anime({
	  	targets: element,
	  	translateX: $x,
	  	translateY: $y,
	  	duration:1000/play_speed,
	  	easing: 'easeInOutQuart',
	  	complete:function(){
	  		cue_process();
	  	}
	});
}

//移动到敌人阵前中心
function moveToEnemyCentre(){
	var element=body;
	var ele=$(body);
	// left21 right21
	if(ele.attr('team')=='left'){
		var element_target='.right21';
	}else{
		var element_target='.left21';
	};
	$ele_position=ele.offset();
	$tar_position=$(element_target).offset();

	var $x=$ele_position.left>=$tar_position.left?$tar_position.left-$ele_position.left+gezi+10:$tar_position.left-$ele_position.left-gezi-10;

	var $y=$ele_position.top>=$tar_position.top?$tar_position.top-$ele_position.top:$tar_position.top-$ele_position.top;

	ele.attr('anime_x',$x);
	ele.attr('anime_y',$y);

	$('.role').each(function () {
		$(this).css(' z-index',1);
	})
	ele.css(' z-index',2);

	anime({
	  	targets: element,
	  	translateX: $x,
	  	translateY: $y,
	  	duration:1000/play_speed,
	  	easing: 'easeInOutQuart',
	  	complete:function(){
	  		cue_process();
	  	}
	});

}

//通用攻击初始化
function attackInit(){
	//行动角色
	body='#'+fight_info[round_index][round_role_index]['attacker']['id'];

	// 攻击目标
	targets=[];
	$.each(fight_info[round_index][round_role_index]['targets'],function (i,val) {
		targets.push('#'+val.id);
	})

	$('.vs_title').html(fight_info[round_index][round_role_index]['attacker']['skill']);
	$('.vs_title_name').html(fight_info[round_index][round_role_index]['attacker']['name']);
	setTimeout(function () {
		$('.vs_title').html('');
		$('.vs_title_name').html('');
	},1700/play_speed);
	cue_process();
}
	
//回到原来的格子位置
function back_() {
	var element=body;
	anime({
			targets: element,
			translateX: 0,
			translateY: 0,
			duration:1000/play_speed,
			easing: 'easeInOutQuart',
			complete:function(){
				cue_process()
			}
		});
}

//攻击
function attack(){
	var element=body;
	var targets_ele=targets;

	var x=parseInt($(element).attr('anime_x'));

	var team=$(element).attr('team');
	var under_attack=0;
	var translateX_=[x-10,x+15,x,x];
	

	if(team=='right'){
		translateX_=[x+10,x-15,x,x]
	}

	anime({
	  	targets: element,
	  	translateX: translateX_,
	  	duration:600/play_speed,
	  	easing: 'easeInBack',
	  	update:function(){
		  	under_attack++;
		  	if(under_attack==parseInt(19/play_speed)){
		  		for (var i = targets_ele.length - 1; i >= 0; i--) {
		  			var hurt=fight_info[round_index][round_role_index]['targets'][i]['hurt'];
		  			underAttack(targets_ele[i],hurt,7);
		  		}
		  	}
		},
	  	complete:function(){
	  		cue_process()
	  	}
	});
}

//狂乱突袭
function skill_kltx(){
	//行动主体
	var element=body;
	//目标
	var targets_ele=targets;

	var x=parseInt($(element).attr('anime_x'));
	var team=$(element).attr('team');

	var texiao_position='';//特效定位
	var texiao_direction='';//特效方向
	if(team=='left'){
		texiao_position='.right11';
		texiao_direction='right';
	}else{
		texiao_position='.left13';
		texiao_direction='left';
	};

	var delay= add_texiao(texiao_position,1,texiao_direction);

	for (var i = targets_ele.length - 1; i >= 0; i--) {
		var hurt=fight_info[round_index][round_role_index]['targets'][i]['hurt'];
		underAttack(targets_ele[i],hurt,8);
	}
	setTimeout(function(){
        cue_process();;
    }, delay);
	
}

//致命审判
function skill_zmsp(){
	//行动主体
	var element=body;
	//目标
	var targets_ele=targets;

	var x=parseInt($(element).attr('anime_x'));
	var team=$(element).attr('team');

	var texiao_direction='';//特效方向
	if(team=='left'){
		texiao_direction='right';
	}else{
		texiao_direction='left';
	};

	var delay= 0;

	for (var i = targets_ele.length - 1; i >= 0; i--) {
		var delay= add_texiao(targets_ele[i],0,texiao_direction);
	}

	setTimeout(function(){
        for (var i = targets_ele.length - 1; i >= 0; i--) {
			var hurt=fight_info[round_index][round_role_index]['targets'][i]['hurt'];
			underAttack(targets_ele[i],hurt,8);
		}
    }, delay/2);

	setTimeout(function(){
        cue_process();;
    }, delay);
	
}

var hurt_i=0;//标记
//受伤（扣血）
function hurt(element,hurt_) {
	var class_name='hurt';
	var self ='hurt'+hurt_i++;
	var t='<div class="'+class_name+' '+self+'">'+hurt_+'</div>';
	
	//血条
	var hp=$(element).find('.hp');
	var hurt_val=parseInt(hurt_.replace(/<[^<>]+>/g,""));
	hp.attr('hp',parseInt(hp.attr('hp'))+hurt_val);
	var p=parseInt(hp.attr('hp'))/parseInt(hp.attr('hp_max'))*100+'%';
	hp.css('width',p);

	$(element).prepend(t);
	anime({
		targets: '.'+class_name,
		translateY: [0,-40],
		scale: [1,1.3],
		duration:400/play_speed,
		easing: 'easeOutBack',
		complete:function(){
			$('.'+self).remove();
		}
	});
}

//受到击
function underAttack(element,hurt_,texiao_i){
	var index= Math.ceil(Math.random()*3);

	var team=$(element).attr('team');
	
	hurt(element,hurt_);//受伤扣血
	
	add_texiao(element,texiao_i,team);

	if(team=='right'){
		switch(index){ 
	        case 1: 
	            anime({
				  targets: element,
				  translateX: [gezi/10*1.5,0],
				  duration:200/play_speed,
				  easing: 'easeInBack'
				});
	        break;

	   //      case 2: 
	   //           anime({
				//   targets: element,
				//   translateX: [10,0],
				//   height:[gezi+5,gezi,gezi-2,gezi],
				//   duration:400/play_speed,
				//   easing: 'easeInBack'
				// });
	        break; 
	            default: 
	                anime({
					  targets: element,
					  translateX: [gezi/10*2,0],
					  skewX:[-2,0],
					  duration:300/play_speed,
					  easing: 'easeInBack'
					});; 
	            break; 
	    }
	}else{
		switch(index){ 
	        case 1: 
	            anime({
				  targets: element,
				  translateX: [-gezi/10*1.5,0],
				  duration:200/play_speed,
				  easing: 'easeInBack'
				});
	        break;

	   //      case 2: 
	   //           anime({
				//   targets: element,
				//   translateX: [-10,0],
				//   height:[gezi+5,gezi,gezi-2,gezi],
				//   duration:400/play_speed,
				//   easing: 'easeInBack'
				// });
	        break; 
	            default: 
	                anime({
					  targets: element,
					  translateX: [-gezi/10*2,0],
					  skewX:[2,0],
					  duration:300/play_speed,
					  easing: 'easeInBack'
					});; 
	            break; 
	    }
	}
	 
}

function roundEnd(){

	moveStyle();
	//清理动画
	cue_process();
}

$('#play_speed').change(function () {
	var val=this.value/10;
	$('#play_speed_title').text('x '+val)
	play_speed=val;
})


//清空style属性 避免z-index失效
function moveStyle(){
	$('.role').attr('style','');
}

