var image_part=$('#images_root').val();//动画图片路径
var texiao_up_i=0;//递增标记

//普通帧动画参数
var texiao_info=[
	{//0
		'folder':'/daoguang0/',//*
		'title':'刀光',
		'texiao_direction':'right',//特效的方向//*
		'item_max':6,//总帧数//*
		'millisec':100,//帧间隔时间//*
		'class':'texiao_skill_zmsp',//*
		// 'audio':["../voice/admin/sszg/刀命中.mp3"],//音频
		// 'audio_item':[3],//音频播放帧
		// 'transforms':{}//详见anime.js的.set()和transforms属性
	},
	{//1
		'folder':'/daoguang1/',
		'title':'刀光',
		'texiao_direction':'left',
		'item_max':5,
		'millisec':60,
		'class':'texiao_skill_kltx',
		// 'audio':["../voice/admin/sszg/刀命中.mp3"],
		// 'audio_item':[4],
		'transforms':{rotateX:180}//
	},
	{//2
		'folder':'/魔法/mofa0/',
		'texiao_direction':'left',
		'item_max':10,
		'millisec':100,
		'class':'',
	},
	{//3
		'folder':'/魔法/mofa1/',
		'title':'魔法飞鸟',
		'texiao_direction':'left',
		'item_max':14,
		'millisec':100,
		'class':'',
	},
	{//4
		'folder':'/daoguang2/',
		'title':'剑斩击',
		'texiao_direction':'left',
		'item_max':11,
		'millisec':100,
		'class':'texiao_underattack',
	},
	{//5
		'folder':'/魔法/龙0/',
		'title':'魔法龙',
		'texiao_direction':'left',
		'item_max':8,
		'millisec':100,
		'class':'texiao_underattack',
	},
	{//6
		'folder':'/魔法/魔法水/',
		'title':'魔法水攻击',
		'texiao_direction':'left',
		'item_max':12,
		'millisec':100,
		'class':'texiao_position',
	},
	{//7
		'folder':'/受攻击/受击0/',
		'title':'受到攻击',
		'texiao_direction':'right',
		'item_max':3,
		'millisec':100,
		'class':'texiao_underattack1',
		'audio':["../voice/admin/sszg/刀命中.mp3"],
		'audio_item':[1],
	},
	{//8
		'folder':'/受攻击/受击爪/',
		'title':'受到攻击爪',
		'texiao_direction':'left',
		'item_max':3,
		'millisec':100,
		'class':'texiao_underattack0',
		'audio':["../voice/admin/sszg/刀命中.mp3"],
		'audio_item':[1],
	},
	{//9
		'folder':'/受攻击/受击1h红/',
		'title':'受击1h红',
		'texiao_direction':'left',
		'item_max':5,
		'millisec':100,
		'class':'texiao_underattack0',
		'audio':["../voice/admin/sszg/刀命中.mp3"],
		'audio_item':[1],
	},
	{//10
		'folder':'/魔法/爆炸魔法/',
		'title':'魔法水攻击',
		'texiao_direction':'left',
		'item_max':11,
		'millisec':100,
		'class':'texiao_position',
		'audio':["../voice/admin/sszg/刀命中.mp3"],
		'audio_item':[1,3,5,7,9],
	},
	{//11
		'folder':'/魔法/治愈魔法/',
		'title':'治愈魔法',
		'texiao_direction':'left',
		'item_max':9,
		'millisec':100,
		'class':'texiao_position',
	},
	{//12
		'folder':'/魔法/激射魔法/',
		'title':'激射魔法-箭雨',
		'texiao_direction':'right',
		'item_max':11,
		'millisec':100,
		'class':'texiao_position',
		'audio':["../voice/admin/sszg/刀命中.mp3"],
		'audio_item':[6,7,8,9,10],
	},
	{//13
		'folder':'/魔法/暗魔法/',
		'title':'暗魔法',
		'texiao_direction':'right',
		'item_max':12,
		'millisec':100,
		'class':'texiao_position',
	},
	{//14
		'folder':'/刀光/暗刀光/',
		'title':'暗刀光',
		'texiao_direction':'right',
		'item_max':8,
		'millisec':120,
		'class':'texiao_position',
	},
	{//15
		'folder':'/刀光/蓝火刀光/',
		'title':'蓝火刀光',
		'file_pre':'1075-',//定义文件名前缀
		'file_begin_item':1,//定义起始帧下标
		'texiao_direction':'right',
		'item_max':9,
		'millisec':100,
		'class':'texiao_position',
	},
	{//16
		'folder':'/魔法/爆裂/',
		'title':'爆裂',
		'file_pre':'图像',//定义文件名前缀
		'file_begin_item':1,//定义起始帧下标
		'texiao_direction':'right',
		'item_max':16,
		'millisec':80,
		'class':'texiao_position',
	},
	{//17
		'folder':'/魔法/莲花/',
		'title':'莲花',
		'file_pre':'3EFC90C5000',//定义文件名前缀
		'texiao_direction':'right',
		'item_max':9,
		'millisec':80,
		'class':'texiao_position',
		'transforms':{rotateX:-45}
	},
	{//18
		'folder':'/魔法/火焰冲击波/',
		'title':'火焰冲击波',
		'texiao_direction':'right',
		'item_max':14,
		'millisec':80,
		'class':'texiao_position',
		'transforms':{rotate:-45}
	},
	{//19
		'folder':'/魔法/火焰爆炸波/',
		'title':'火焰爆炸波',
		'texiao_direction':'right',
		'item_max':14,
		'millisec':80,
		'class':'texiao_position',
	},
	{//20
		'folder':'/魔法/蓝色魔法攻击球/',
		'title':'蓝色魔法攻击球',
		'file_pre':'1064-',
		'item_max':10,
		'millisec':80,
		'class':'texiao_position',
	},

];

//同图片帧动画参数
var one_img_info=[//同图片帧
	{
		'folder':'/texiao/火.png',
		'texiao_direction':'left',//特效方向
		'item_max':4,//总帧数
		'millisec':60,//帧间隔
		'class':'',//css
		'direction':'y',//方向
		'width':512,//原图片总宽
		'height':512,//原图片总高
		'box_width':300,//盒子宽
	}
];

//获取唯一标记值
function get_texiao_i() {
	return texiao_up_i++;
}

$('#texiao_play').click(function () {
	var i=parseInt(Math.random()*4);
	add_texiao('.left11',19,'left');
	add_texiao('.right11',20,'right');
})

//帧特效
function add_texiao(element,iii,direction){

	var texiao_i= iii;//动画
	var info=texiao_info[iii];
	//默认方向
	if(info['texiao_direction']!=undefined){
		info['texiao_direction']='left';
	}
	var millisec =info['millisec']/play_speed;//动画每帧间隔
	// play_speed该参数定义于其他的角色文件
	var class_='texiao'+get_texiao_i();
	var count_s=millisec * info['item_max'];//动画完成所需总时间
	var begin_item=0;//起始
	if(info['file_begin_item']!=undefined){
		begin_item=info['file_begin_item'];
	}
	
	var img='<img inter=""'+
	' texiao_index="'+iii+'"'+
	' texiao_item="'+begin_item+'" '+
	' texiao_end="" '+
	 ' class="'+class_+' '+info['class']+'" src="" >';

	//添加动画元素
	$(element).append(img);
	
	if(direction!=info['texiao_direction']){
		//水平翻转180
		anime.set('.'+class_,{rotateY:180});
	}

	//设定初始css属性 详见anime.js的.set()和transforms属性
	if(info['transforms']!=undefined){
		// info['transforms'] 是对象类型
		anime.set('.'+class_,info['transforms']);
	}
	

	//帧动画执行定时器	
	var inter = setInterval(function(class_name){
		var ele=$('.'+class_name);
		if(ele.attr('texiao_end')=='end'){
			window.clearInterval(ele.attr('inter'));
			ele.remove();
			return false;
		}

		
		
		
		var texiao =parseInt(ele.attr('texiao_index'));
		var item =parseInt(ele.attr('texiao_item'));

		var img_url=image_part+info['folder']+item+'.png';
		
		//是否有文件名前缀
		if (info['file_pre']!=undefined) { 
			img_url=image_part+info['folder']+info['file_pre']+item+'.png';
		}
		ele.attr('src',img_url);

		//播放音频
		if(info['audio_item']!=undefined){
			var index=info['audio_item'].indexOf(item);
			if(index>=0){
				if(info['audio'][index]!=undefined){
					var audio= new Audio(info['audio'][index]);
				}else{
					var audio= new Audio(info['audio'][info['audio'].length-1]);
				}
				
				audio.defaultPlaybackRate=1/play_speed;
				audio.volume=0.4;
				setTimeout(function () {
					audio.play();
				},1);
			}
		}

		if(item<info['item_max']){
			ele.attr('texiao_item',item+1);
		}else{
			ele.attr('texiao_item',0);
			ele.attr('texiao_end','end');
		};
	},millisec,class_);

	$('.'+class_).attr('inter',inter);
	return count_s;
}

//同图片帧特效
function add_texiao_one_image(element,iii){
	var info=one_img_info[iii];
	var img_url=image_part+info['folder'];
	var millisec =info['millisec']/play_speed;//帧间隔
	var class_='texiao'+get_texiao_i();
	var count_s=millisec * info['item_max'];//动画完成所需总时间
	var	box_height=info['direction']=='y'?info['height']/info['item_max']:info['height'];
		box_height=box_height*info['box_width']/info['width'];
	var img_url='<div inter=""'+
	' texiao_index="'+iii+'"'+
	' texiao_item="0" '+
	' texiao_end="" '+
	' class="'+class_+' '+info['class']+'" '+
	'style=";width: '+info['box_width']+'px;height: '+box_height+'px;;background: url('+img_url+') no-repeat;background-size:100% auto;background-position-x: 0px;background-position-y: 0px;"'+
	'></div>';
	//添加动画元素
	$(element).append(img_url);
	
	//帧动画执行定时器	
	var inter = setInterval(function(class_name){
		var ele=$('.'+class_);
		console.log(1)
		if(ele.attr('texiao_end')=='end'){
			window.clearInterval(ele.attr('inter'));
			ele.remove();
			return false;
		}
		
		var info =one_img_info[parseInt(ele.attr('texiao_index'))];
		var item =parseInt(ele.attr('texiao_item'));

		if(item<info['item_max']){
			var style = 'background-position-'+info['direction'];
			if(info['direction']=='y'){
				ele.css(style,-item*ele.height());
			}
			if(info['direction']=='x'){
				ele.css(style,-item*ele.width());
			}
			ele.attr('texiao_item',item+1);
		}else{
			ele.attr('texiao_item',0);
			ele.attr('texiao_end','end');
		};
	},millisec,class_);

	$('.'+class_).attr('inter',inter);
	return count_s;
}