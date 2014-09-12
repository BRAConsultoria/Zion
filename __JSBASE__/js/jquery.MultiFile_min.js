if(window.jQuery)(function($){$.extend($,{MultiFile:function(o){return $("input:file.multi").MultiFile(o)}});$.extend($.MultiFile,{options:{accept:"",html_elem_prepend:"",html_elem_append:"",max:-1,error:function(s){if($.blockUI){$.blockUI({message:s.replace(/\n/gi,"<br/>"),css:{border:"none",padding:"15px",size:"12.0pt",backgroundColor:"#900",color:"#fff",opacity:".8","-webkit-border-radius":"10px","-moz-border-radius":"10px"}});window.setTimeout($.unblockUI,2E3)}else alert(s)},namePattern:"$name", STRING:{remove:"remove",denied:"You cannot select a $ext file.\nTry again...",selected:"File selected: $file",duplicate:"This file has already been selected:\n$file"}}});$.extend($.MultiFile,{disableEmpty:function(klass){var o=[];$("input:file").each(function(){if($(this).val()=="")o[o.length]=this});return $(o).each(function(){this.disabled=true}).addClass(klass||"mfD")},reEnableEmpty:function(klass){klass=klass||"mfD";return $("input:file."+klass).removeClass(klass).each(function(){this.disabled= false})},autoIntercept:["submit","ajaxSubmit","validate"],intercepted:{},intercept:function(methods,context,args){var method,value;args=args||[];if(args.constructor.toString().indexOf("Array")<0)args=[args];if(typeof methods=="function"){$.MultiFile.disableEmpty();value=methods.apply(context||window,args);$.MultiFile.reEnableEmpty();return value}if(methods.constructor.toString().indexOf("Array")<0)methods=[methods];for(var i=0;i<methods.length;i++){method=methods[i]+"";if(method)(function(method){$.MultiFile.intercepted[method]= $.fn[method]||function(){};$.fn[method]=function(){$.MultiFile.disableEmpty();value=$.MultiFile.intercepted[method].apply(this,arguments);$.MultiFile.reEnableEmpty();return value}})(method)}}});$.extend($.fn,{reset:function(){return this.each(function(){try{this.reset()}catch(e){}})},MultiFile:function(options){if($.MultiFile.autoIntercept){$.MultiFile.intercept($.MultiFile.autoIntercept);$.MultiFile.autoIntercept=null}return $(this).each(function(group_count){if(this._MultiFile)return;this._MultiFile= true;window.MultiFile=(window.MultiFile||0)+1;group_count=window.MultiFile;var MF={e:this,E:$(this),clone:$(this).clone()};if(typeof options=="number")options={max:options};if(typeof options=="string")options={accept:options};var o=$.extend({},$.MultiFile.options,options||{},($.meta?MF.E.data():$.metadata?MF.E.metadata():null)||{});if(!(o.max>0)){o.max=MF.E.attr("maxlength");if(!(o.max>0)){o.max=(String(MF.e.className.match(/\b(max|limit)\-([0-9]+)\b/gi)||[""]).match(/[0-9]+/gi)||[""])[0];if(!(o.max> 0))o.max=-1;else o.max=String(o.max).match(/[0-9]+/gi)[0]}}o.max=new Number(o.max);o.accept=o.accept||MF.E.attr("accept")||"";if(!o.accept){o.accept=MF.e.className.match(/\b(accept\-[\w\|]+)\b/gi)||"";o.accept=(new String(o.accept)).replace(/^(accept|ext)\-/i,"")}o.html_elem_prepend=o.html_elem_prepend||"";o.html_elem_append=o.html_elem_append||"";$.extend(MF,o||{});MF.STRING=$.extend({},$.MultiFile.options.STRING,MF.STRING);$.extend(MF,{n:0,slaves:[],files:[],instanceKey:MF.e.id||"MultiFile"+String(group_count), generateID:function(z){return MF.instanceKey+(z>0?"_F"+String(z):"")},trigger:function(event,element){var handler=MF[event],value=$(element).attr("value");if(handler){var returnValue=handler(element,value,MF);if(returnValue!=null)return returnValue}return true}});if(String(MF.accept).length>1)MF.rxAccept=new RegExp("\\.("+(MF.accept?MF.accept:"")+")$","gi");MF.wrapID=MF.instanceKey+"_wrap";MF.E.wrap('<div id="'+MF.wrapID+'"></div>');MF.wrapper=$("#"+MF.wrapID+"");MF.e.name=MF.e.name||"file"+group_count+ "[]";MF.wrapper.append('<span id="'+MF.wrapID+'_labels"></span>');MF.labels=$("#"+MF.wrapID+"_labels");MF.addSlave=function(slave,slave_count){MF.n++;slave.MF=MF;slave.i=slave_count;if(slave.i>0)slave.id=slave.name=null;slave.id=slave.id||MF.generateID(slave.i);slave.name=String(MF.namePattern.replace(/\$name/gi,MF.E.attr("name")).replace(/\$id/gi,MF.E.attr("id")).replace(/\$g/gi,group_count>0?group_count:"").replace(/\$i/gi,slave_count>0?slave_count:""));$(slave).val("").attr("value","")[0].value= "";if(MF.max>0&&MF.n-1>MF.max)slave.disabled=true;MF.current=MF.slaves[slave.i]=slave;slave=$(slave);$(slave).change(function(){$(this).blur();if(!MF.trigger("onFileSelect",this,MF))return false;var ERROR="",v=String(this.value||"");if(MF.accept&&v&&!v.match(MF.rxAccept))ERROR=MF.STRING.denied.replace("$ext",String(v.match(/\.\w{1,4}$/gi)));for(var f in MF.slaves)if(MF.slaves[f]&&MF.slaves[f]!=this)if(MF.slaves[f].value==v)ERROR=MF.STRING.duplicate.replace("$file",v.match(/[^\/\\]+$/gi));var newEle= $(MF.clone).clone();newEle.addClass("MultiFile");if(ERROR!=""){MF.error(ERROR);MF.n--;MF.addSlave(newEle[0],this.i);slave.parent().prepend(newEle);slave.remove();return false}$(this).css({position:"absolute",top:"-3000px"});MF.labels.before(newEle);MF.addToList(this);MF.addSlave(newEle[0],this.i+1);if(!MF.trigger("afterFileSelect",this,MF))return false})};MF.addToList=function(slave){if(!MF.trigger("onFileAppend",slave,MF))return false;var r=$('<div id="MF_File'+MF.wrapID+'" class="MFJQ_File"></div>'), v=String(slave.value||""),a=$('<span class="file" title="'+MF.STRING.selected.replace("$file",v)+'">'+v.match(/[^\/\\]+$/gi)[0]+"</span>"),b=$('<a href="#'+MF.wrapID+'">'+MF.STRING.remove+"</a>");r.append(MF.html_elem_prepend);r.append("[",b,"]&nbsp;",a);r.append(MF.html_elem_append);MF.labels.append(r);b.click(function(){if(!MF.trigger("onFileRemove",slave,MF))return false;MF.n--;MF.current.disabled=false;MF.slaves[slave.i]=null;$(slave).remove();$(this).parent().remove();$(MF.current).css({position:"", top:""});$(MF.current).reset().val("").attr("value","")[0].value="";if(!MF.trigger("afterFileRemove",slave,MF))return false;return false});if(!MF.trigger("afterFileAppend",slave,MF))return false};if(!MF.MF)MF.addSlave(MF.e,0);MF.n++})}});$(function(){$.MultiFile()})})(jQuery);