/**
 * 
 * 全局异步操作核心函数
 * 
 */
$(function () {
    $("a.Jcore").click(function(){
    	
    });
});
$(document).ready(function(){
	//获取A标签的点击事件
	$("a.Jcore").click(function(e){
		// 获取a标签的target属性
		if($(this).attr("target")!=undefined){
			var target = $(this).attr("target");
			var linkUrl=$(this).attr("link");
			// 判断target的值，（dialog/ajaxTodo/batchAjaxTodo）
			if(target=="dialog"){
				parent.layer.open({
			            type: 2,
			            title: $(this).attr("title"),
			            area: [$(this).attr("width"), $(this).attr("height")],
			            content: linkUrl
			        });
			}else if(target=="ajaxTodo"){
				parent.layer.confirm($(this).attr("title"), {
				    btn: ['确定','取消'], //按钮
				    offset: '0px'
				}, function(){
					$.ajax({
						type:'POST',
						url:linkUrl,
						dataType:"json",
						cache: false,
						success: ajaxTodoCallBack,
						error: function(XMLHttpRequest, textStatus, errorThrown){
							layer.msg(textStatus);
						}
					});
				}, function(){
					
				});
			}else if(target=="batchAjaxTodo"){
				
				var pausedCause = '';
				$("input:checkbox[name=chk_list]:checked").each(function(){
					pausedCause += this.value + ',';
				});
				pausedCause = pausedCause.substring(0,pausedCause.length-1);
				if(pausedCause == ''){
					layer.msg("未选中任何信息");
				}else{
					layer.confirm($(this).attr("title"), {
					    btn: ['确定','取消'], //按钮
					    offset: '0px'
					}, function(){
						$.ajax({
							type:'POST',
							url:linkUrl.indexOf("?")==-1?linkUrl+"?ids="+pausedCause:linkUrl+"&ids="+pausedCause,
							dataType:"json",
							cache: false,
							success: ajaxTodoCallBack,
							error: function(XMLHttpRequest, textStatus, errorThrown){
								layer.msg(textStatus);
							}
						});
								
					}, function(){
						
					});
				}
			}
		}
	});
	
	function ajaxTodoCallBack(json){
		if(json.statusCode==300){
			layer.msg(json.message, {
				time: 2000 //20s后自动关闭
			});
			parent.layer.close(parent.layer.getFrameIndex(window.name));
			
		}else{
			navTabReLoad(json);
		}
		
	}
	
	$("button.cancel").click(function(){
		var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
		parent.layer.close(index); //再执行关闭 
		return false;
	});
	
		/** 全选处理 **/
	$("#chk_all").click(function(){
	    $('input[name="chk_list"]').prop("checked",this.checked);
	});
	
	/** 单选处理 **/
	var $subBox = $("input[name='chk_list']");
	$subBox.click(function(){
	    $("#chk_all").prop("checked",$subBox.length == $("input[name='chk_list']:checked").length ? true : false);
	});
	
	
});

var lodInd;
function layPage(){
	laypage({
	    // 容器。值支持id名、原生dom对象，jquery对象,
	    cont: 'contentPages',              
	    // 总页数
	    pages: '${pageCount}',              
	    // 当前页
	    curr: '${currentPage}',    
	    skip: true, //是否开启跳页
	    // 皮肤颜色
	    skin:'#4DA7F9',                     
	    // 第一页别名文本内容
	    first:'首页',                        
	    // 最后一页别名
	    last: "尾页",                        
	    // 触发分页后的回调
	    jump: function(obj, first){    

	    	// 保存页码记录
	    	$(".pageNum").val(obj.curr);
		         
	        var index = null;
	        
	        // 一定要加此判断，否则初始时会无限刷新
	        if(!first){
	        	index = layer.open({type: 3});
	        	$("#pagerForm").submit();
	        }
	    }
	});
}




/**
 * 普通ajax表单提交
 * @param {Object} form
 * @param {Object} callback
 * @param {String} confirmMsg 提示确认信息
 */
function validateCallback(form, callback, confirmMsg) {
	 lodInd = layer.load(0, {shade: 0.2}); //0代表加载的风格，支持0-2
	var $form = $(form);
	if($("#ibox_con").length>0){
		$("#ibox_con").val($(".note-editable").html());
	}
	
	$.ajax({
		type: form.method || 'POST',
		url:$form.attr("action"),
		data:$form.serializeArray(),
		dataType:"json",
		cache: false,
		success: callback,
		error: function(XMLHttpRequest, textStatus, errorThrown){
			layer.msg(textStatus);
		}
	});
	return false;
}


/**
 * 带文件上传的ajax表单提交
 * @param {Object} form
 * @param {Object} callback
 */
function iframeCallback(form, callback){
	var $form = $(form), $iframe = $("#callbackframe");
	if ($iframe.size() == 0) {
		$iframe = $("<iframe id='callbackframe' name='callbackframe' src='about:blank' style='display:none'></iframe>").appendTo("body");
	}
	if(!form.ajax) {
		$form.append('<input type="hidden" name="ajax" value="1" />');
	}
	form.target = "callbackframe";

	_iframeResponse($iframe[0], callback || DWZ.ajaxDone);
}
function _iframeResponse(iframe, callback){
	var $iframe = $(iframe), $document = $(document);
	
	$document.trigger("ajaxStart");
	
	$iframe.bind("load", function(event){
		$iframe.unbind("load");
		$document.trigger("ajaxStop");
		
		if (iframe.src == "javascript:'%3Chtml%3E%3C/html%3E';" || // For Safari
			iframe.src == "javascript:'<html></html>';") { // For FF, IE
			return;
		}

		var doc = iframe.contentDocument || iframe.document;

		// fixing Opera 9.26,10.00
		if (doc.readyState && doc.readyState != 'complete') return; 
		// fixing Opera 9.64
		if (doc.body && doc.body.innerHTML == "false") return;
	   
		var response;
		
		if (doc.XMLDocument) {
			// response is a xml document Internet Explorer property
			response = doc.XMLDocument;
		} else if (doc.body){
			try{
				response = $iframe.contents().find("body").text();
				response = jQuery.parseJSON(response);
			} catch (e){ // response is html document or plain text
				response = doc.body.innerHTML;
			}
		} else {
			// response is a xml document
			response = doc;
		}
		
		callback(response);
		//$.pdialog.closeCurrent();
//		navTab.reload();
		
	});
}



function getCurrIframe(){
	var index=0;
	var frames=parent.$("iframe");
	for(var i=0;i<frames.length;i++){
		if($(frames[i]).css("display")!="none"){
			index=i;
			break;
		}
	}
	var pagerForm = $(parent.frames[""+parent.$("iframe").eq(index).attr("name")+""].document).find("#pagerForm");
	
	return pagerForm;
	
}

function navTabAjaxDone(json){
	if(json.statusCode==300){
		parent.layer.msg(json.message, {
			time: 2000 //20s后自动关闭
		});
		//parent.layer.close(lodInd);
		layer.close(lodInd);
		
	}else if(json.statusCode=='sss'){
		// dialog登录成功，刷新页面
		parent.location.reload();
	}else{
		navTabReLoad(json);
	}
	
}
function navTabReLoad(json){
	var pagerForm = getCurrIframe();
	$(pagerForm).submit();
	parent.layer.msg(json.message, {
		time: 2000 //20s后自动关闭
	});
	parent.layer.close(parent.layer.getFrameIndex(window.name));
}

