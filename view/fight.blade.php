<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>mygame后台</title>
  <link rel="stylesheet" href="../public/layui-v2.4.3/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="../public/layui-v2.4.3/layuiconfig/admin.css" media="all">
  
  <script src="{{URL::secure('/public/layui-v2.4.3/layui/layui.js')}}"></script>
  <script src="{{URL::secure('/public/jquery-2.1.4.js')}}"></script>
</head>
<body class="layui-layout-body">
<style>
html {
    background-color: #fff;
}
.ttt{
	display: inline-block;
	margin: 0px;
	/*margin-bottom: -4px;*/
	border-top-width: 0;
    padding: 10px;
    line-height: 20px;
    background-color: #3F3F3F;
    color: #eee;
    font-size: 12px;
    min-width: 240px;
    max-width: 300px;
    min-height:  350px;
    word-break:break-all;
    border-radius: 2px 2px;
}

.qipan{
	/*background-color: #999;*/
	width: 900px;
	margin: auto;
}
.vs{
	font-size: 70px;
	text-align: center;
	margin-top: 110px;

}
.vs_title_name{
	margin-left: 30px;
	font-size: 18px;
	height: 24px ;
	text-align: center;
}
.vs_title{
	font-size: 18px;
	height: 24px ;
	text-align: center;
}
.left,.right{
	width: 40%;
	height: 100%;
	display: inline-block;
	/*background: #ccc*/
}
.center{
	width: 20%;
	float: left;
}
.left{float: left;}
.right{
	float: right;
}
.role{
	border-radius: 5px 5px;
	border: 2px #999 solid ;
	background-color: #eee;
	width: 60px;
	height: 60px;
	margin: 20px 20px;
	display: inline-block;
	line-height: 100%;
	position:relative;
}
.role_box{
	width: 90%;
	height: 90%;
	border-radius: 5px 5px;
	border: 2px #999 solid ;
	background-color: #eee;
	margin: auto;
	margin-top: 1px;
}
.hero_name{
	float: left;
	width: 100%;
	height: 100%;
	line-height: 45px;
	text-align: center;
	font-size: 14px;

}
.role_color_feng{
	/*color: #009688;*/
	/*border: 2px #009688 solid ;*/

}
.hurt{
	position: absolute;
	width: 200px;
	height: 12px;
	line-height: 12px;
	text-align:center;
	font-size: 12px;
	left: -70px;
}

.hp_max{
	width: 100%;
	height: 8px;
	float: left;
	background-color: #999; 
	position: relative;
	bottom: 6px;
	border-radius: 2px 2px 2px 2px;
}
.hp{
	width: 100%;
	height: 8px;
	float: left;
	background-color: #5FB878; 
	position: relative;
	bottom: 14px;
	border-radius: 2px 0px 0px 2px;
}

/*特效*/
.texiao_skill_zmsp{
	width: 120px;
	position:absolute;
	top:-21px;
	left: -25px;
	z-index: 20;
}
.texiao_underattack0{
	width: 50px;
	position:absolute;
	top:15px;
	left: 0px;
	z-index: 20;
}
.texiao_underattack1{
	width: 50px;
	position:absolute;
	top:1px;
	left: 2px;
	z-index: 20;
}

.texiao_position{
	width: 360px;
	position:absolute;
	top:-21px;
	left: 0px;
	z-index: 20;
}
.texiao_skill_kltx{
	width: 300px;
	position:absolute;
	top:-21px;
	left: 0px;
	z-index: 100;
}
.img_texiao_fire{
	
}
</style>

<div class="">
	<meta name="csrf-token" content="{{ csrf_token() }}">
    <div style="width: 2%;float: left;">&nbsp;</div>
    <div style="width:96%;float: left;">
    
		<div  style="margin-bottom:10px;margin-top:10px;ine-height: 22px;">
		
			<!-- <span>战斗画面</span> -->
			<div style="display: inline-block;width: 40%">
				<span class="vs_title_name"></span>	
		    	<span class="vs_title"></span>
	    	</div>
	    	
			<span style="display: inline-block;width: 19%;font-size: 18px;text-align: center;">第 <span id="round_number">1</span> 回合</span>

			<div style="display: inline-block;width: 40%">
	    		
	    	
				
				
				<div style="width: 200px;height: 20px;display: inline-block;">
					<input id="play_speed" type="range" min="1" max="30"  value="10">
					<div id="play_speed_title" style="width: 50px;height: 15px;display: inline-block;font-size: 16px;">x 1.0</div>
				</div>
				<button id="start" class="edit layui-btn layui-btn-sm layui-btn-normal">开始战斗</button>
				<button id="texiao_play" class="edit layui-btn layui-btn-sm layui-btn-normal">texiao</button>
				<button id="get" class="edit layui-btn layui-btn-sm layui-btn-normal"><i class="layui-icon layui-icon-add-circle" style="font-size: 30px;"></i>get</button>

			</div>
		</div>


	    <div class="qipan">
	    	<div class="left">
	    		<div team="left" class="role left13 ">
	    			<div class="role_box role_color_feng">
	    				<span class="hero_name "></span>
	    			</div>
	    		</div>
	    		<div team="left" class="role left12" id="role1">
	    			<div class="role_box role_color_feng">
	    				<span class="hero_name "></span>
	    				<!-- <img src="https://127.0.0.1/laravelmygame/public/images/admin/sszg/daoguang0/4.png" class="texiao_underattack" alt=""> -->
	    			</div>
	    		</div>
	    		<div team="left" class="role left11">
	    			<div class="role_box role_color_feng">
	    				<span class="hero_name "></span>
	    			</div>
	    		</div>
	    		<div team="left" class="role left23">
	    			<div class="role_box role_color_feng">
	    				<span class="hero_name "></span>
	    			</div>
	    		</div>
	    		<div team="left" class="role left22">
	    			<div class="role_box role_color_feng">
	    				<span class="hero_name "></span>
	    			</div>
	    		</div>
	    		<div team="left" class="role left21">
	    			<div class="role_box role_color_feng">
	    				<span class="hero_name "></span>
	    			</div>
	    		</div>
	    		<div team="left" class="role left33">
	    			<div class="role_box role_color_feng">
	    				<span class="hero_name "></span>
	    			</div>
	    		</div>
	    		<div team="left" class="role left32">
	    			<div class="role_box role_color_feng">
	    				<span class="hero_name "></span>
	    			</div>
	    		</div>
	    		<div team="left" class="role left31">
	    			<div class="role_box role_color_feng">
	    				<span class="hero_name "></span>
	    			</div>
	    		</div>

	    	</div>
	    	<div class="center">
	    		<div class="vs">vs</div>	
	    	</div>
	    	<div class="right">
	    		<div team="right" class="role right11">
	    			<div class="role_box role_color_feng">
	    				<span class="hero_name "></span>
	    			</div>
	    		</div>
	    		<div team="right" class="role right12">
	    			<div class="role_box role_color_feng">
	    				<span class="hero_name "></span>
	    			</div>
	    		</div>
	    		<div team="right" class="role right13">
	    			<div class="role_box role_color_feng">
	    				<span class="hero_name "></span>
	    			</div>
	    		</div>
	    		<div team="right" class="role right21">
	    			<div class="role_box role_color_feng">
	    				<span class="hero_name "></span>
	    			</div>
	    		</div>
	    		<div team="right" class="role right22">
	    			<div class="role_box role_color_feng">
	    				<span class="hero_name "></span>
	    			</div>
	    		</div>
	    		<div team="right" class="role right23">
	    			<div class="role_box role_color_feng">
	    				<span class="hero_name "></span>
	    			</div>
	    		</div>
	    		<div team="right" class="role right31">
	    			<div class="role_box role_color_feng">
	    				<span class="hero_name "></span>
	    			</div>
	    		</div>
	    		<div team="right" class="role right32">
	    			
	    			<div class="role_box role_color_feng">
	    				<span class="hero_name "></span>
	    			</div>
	    		</div>
	    		<div team="right" class="role right33">
	    			<div class="role_box role_color_feng">
	    				<span class="hero_name "></span>
	    			</div>
	    		</div>
	    	</div>
	    	

	    </div>
    </div>

</div>
<input type="hidden" id="images_root" value="{{URL::secure('/images/admin/sszg')}}">

<div class="texiao_box">
	
</div>
<script>
layui.use('layer', function(){
  var layer = layui.layer;

	var width =$(document).width()>1000?950:$(document).width()-50;
	var height =$(document).height()>700?650:$(document).height()-50;
        width=width+'px';height=height+'px';
      
	
	// layer.open({
	//   	title:'ffff',
	// 	type: 1,
	// 	area: ['auto', 'auto'],
	// 	fixed: false, //不固定
	// 	maxmin: false,
	// 	id: 'layer',
	// 	shadeClose: true,
	// 	content: '<div class="ttt">99999</div>',
	// });
});
</script>
<script src="{{URL::secure('/public/anime/anime.min.js')}}"></script>

<script src="{{URL::secure('/js/admin/sszg/role_anime.js')}}"></script>
<script src="{{URL::secure('/js/admin/sszg/fight.js')}}"></script>
<script src="{{URL::secure('/js/admin/sszg/texiao.js')}}"></script>
<script>

function roleRound(){

	var role='.left11';
	procee=procees['attack'];

	//开始流程
	cue_process(role);
}

var can_play=false;
var play_new=false;
var clicked=false;
$('#start').click(function() {
	if(can_play){
		can_play=false;
		play_new=true;
		round();
	}
	
})

function round(){
	$('#start').text('战斗中..');
	cue_process();
}


$('#get').click(function () {
	getFightInfo();
});

(function () {
	getFightInfo();
	
})()


var fight=[];
//获取对战数据 并初始化棋盘
function getFightInfo(){
	if(play_new){
		return false;
	}
	can_play=true;

	$.ajax({
	  	url: 'sszg_fight_info',
	  	type:'post',
	  	dataType:'json',
	  	data:{},
	  	headers: {
        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
   	 	},
	  	success: function (info){
	  		
	  		fight_info=info.info;
	  		init_roles(info.roles);
	  		$('#round_number').text(1);
	  		$('#start').text('开始战斗');
	  		if (!clicked) {
	  			clicked=true;
	  			// $('#start').trigger("click");
	  		}
	  	}

	});
}

//初始化角色
function init_roles(roles){
	$('.hp_max').remove();
	$('.hp').remove();
	$('.role').attr('id','');
	$('.role').attr('id','');
	$('.hero_name').text('');
	for(var i = roles.length - 1; i >= 0; i--) {
		if (i==0) {
			var text='left';
		}
		if (i==1) {
			var text='right';
		}
		
		$.each(roles[i], function(i, val) {
			var hp ='<div class="hp_max"></div><div class="hp" hp="'+val['h_max']+'" hp_max="'+val['h_max']+'"></div>';
		 	$('.'+text+val['position']).attr('id',val['id']);
			$('.'+text+val['position']).find('.hero_name ').text(val['name']);
			$('.'+text+val['position']).find('.hero_name ').after(hp);
			
	    				

		 });
	}
}


</script>
<div></div>
</body>
</html>