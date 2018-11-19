(function(){if(!window.V){var V=window.V={};V.domReady=function(fn){var ie=
/*@cc_on!@*/
false;if(window.addEventListener){document.addEventListener("DOMContentLoaded",fn,false)}else{if(ie){document.attachEvent("onreadystatechange",function(){if(document.readyState=="complete"){fn()}})}}};V.cloneObject=function(obj){if(obj&&typeof(obj)=="object"){var newObj={};for(var p in obj){newObj[p]=obj[p]}return newObj}}}})();

V.Link={
	contentHeight:0,
	init:function(){
		V.Link.setFrameHeight();
		var a=jQuery.proxy(this.matchFrameHeight,this);
		jQuery(window).on("load",a).on("resize",a)
	},
	matchFrameHeight:function(){
			window.clearInterval(this.intervalNum);this.intervalNum=window.setInterval(jQuery.proxy(this.setFrameHeight,this),200)},
	setFrameHeight:function(){
			var a=this.getDocumentHeight();
			if(a!=this.contentHeight){this.contentHeight=a;jQuery("#contentFrame").height(a)}
			else{this.contentHeight=0;window.clearInterval(this.intervalNum)}
			},
	getDocumentHeight:function(){
			var a=jQuery(window).height()-jQuery(".headerbar").height()-1;
			return a}
};V.Link.init();