
var region = function(option,url){
	option.province.change(function(){
	var name = option.province.val();
	var html = '';
	$.get(url.getRegionNameByName,{'name':name},function(result){
		console.log(result.length);
		for (var i = 0; i < result.length; i++) {
			html += "<option value = "+result[i].region_name+">"+result[i].region_name+"</option>";
		}
		option.city.html(html)
	})
	})

	option.city.on('change',(function(){
		var name = option.city.val();
		var html ='';
		$.get(url.getRegionNameByName,{'name':name},function(result){
		console.log(result.length);
		for (var i = 0; i < result.length; i++) {
			html += "<option value = "+result[i].region_name+">"+result[i].region_name+"</option>";
		}
		option.area.html(html)
	})
	})
	)
}


