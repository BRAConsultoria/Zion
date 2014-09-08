(function($){$.fn.bgIframe=jQuery.fn.bgiframe=function(s){if(!($.browser.msie&&typeof XMLHttpRequest=='function'))return this;s=$.extend({top:'auto',left:'auto',width:'auto',height:'auto',opacity:true,src:'javascript:false;'},s||{});var prop=function(n){return n&&n.constructor==Number?n+'px':n;},html='<iframe class="bgiframe"frameborder="0"tabindex="-1"src="'+s.src+'"style="display:block;position:absolute;z-index:-1;'+(s.opacity!==false?'filter:Alpha(Opacity=\'0\');':'')+'top:'+(s.top=='auto'?'expression(((parseInt(this.parentNode.currentStyle.borderTopWidth)||0)*-1)+\'px\')':prop(s.top))+';left:'+(s.left=='auto'?'expression(((parseInt(this.parentNode.currentStyle.borderLeftWidth)||0)*-1)+\'px\')':prop(s.left))+';width:'+(s.width=='auto'?'expression(this.parentNode.offsetWidth+\'px\')':prop(s.width))+';height:'+(s.height=='auto'?'expression(this.parentNode.offsetHeight+\'px\')':prop(s.height))+';"/>';return this.each(function(){if(!$('iframe.bgiframe',this)[0])this.insertBefore(document.createElement(html),this.firstChild);});};})(jQuery);

//******************

jQuery.fn._height = jQuery.fn.height;
jQuery.fn._width  = jQuery.fn.width;

jQuery.fn.height = function() {
	if ( this[0] == window )
		return self.innerHeight ||
			jQuery.boxModel && document.documentElement.clientHeight ||
			document.body.clientHeight;

	if ( this[0] == document )
		return Math.max( document.body.scrollHeight, document.body.offsetHeight );

	return this._height(arguments[0]);
};

jQuery.fn.width = function() {
	if ( this[0] == window )
		return self.innerWidth ||
			jQuery.boxModel && document.documentElement.clientWidth ||
			document.body.clientWidth;

	if ( this[0] == document )
		return Math.max( document.body.scrollWidth, document.body.offsetWidth );

	return this._width(arguments[0]);
};

jQuery.fn.innerHeight = function() {
	return this[0] == window || this[0] == document ?
		this.height() :
		this.css('display') != 'none' ?
		 	this[0].offsetHeight - (parseInt(this.css("borderTopWidth")) || 0) - (parseInt(this.css("borderBottomWidth")) || 0) :
			this.height() + (parseInt(this.css("paddingTop")) || 0) + (parseInt(this.css("paddingBottom")) || 0);
};

jQuery.fn.innerWidth = function() {
	return this[0] == window || this[0] == document ?
		this.width() :
		this.css('display') != 'none' ?
			this[0].offsetWidth - (parseInt(this.css("borderLeftWidth")) || 0) - (parseInt(this.css("borderRightWidth")) || 0) :
			this.height() + (parseInt(this.css("paddingLeft")) || 0) + (parseInt(this.css("paddingRight")) || 0);
};

jQuery.fn.outerHeight = function() {
	return this[0] == window || this[0] == document ?
		this.height() :
		this.css('display') != 'none' ?
			this[0].offsetHeight :
			this.height() + (parseInt(this.css("borderTopWidth")) || 0) + (parseInt(this.css("borderBottomWidth")) || 0)
				+ (parseInt(this.css("paddingTop")) || 0) + (parseInt(this.css("paddingBottom")) || 0);
};

jQuery.fn.outerWidth = function() {
	return this[0] == window || this[0] == document ?
		this.width() :
		this.css('display') != 'none' ?
			this[0].offsetWidth :
			this.height() + (parseInt(this.css("borderLeftWidth")) || 0) + (parseInt(this.css("borderRightWidth")) || 0)
				+ (parseInt(this.css("paddingLeft")) || 0) + (parseInt(this.css("paddingRight")) || 0);
};

jQuery.fn.scrollLeft = function() {
	if ( this[0] == window || this[0] == document )
		return self.pageXOffset ||
			jQuery.boxModel && document.documentElement.scrollLeft ||
			document.body.scrollLeft;

	return this[0].scrollLeft;
};

jQuery.fn.scrollTop = function() {
	if ( this[0] == window || this[0] == document )
		return self.pageYOffset ||
			jQuery.boxModel && document.documentElement.scrollTop ||
			document.body.scrollTop;

	return this[0].scrollTop;
};

jQuery.fn.offset = function(options, returnObject) {
	var x = 0, y = 0, elem = this[0], parent = this[0], absparent=false, relparent=false, op, sl = 0, st = 0, options = jQuery.extend({ margin: true, border: true, padding: false, scroll: true }, options || {});
	do {
		x += parent.offsetLeft || 0;
		y += parent.offsetTop  || 0;

		// Mozilla and IE do not add the border
		if (jQuery.browser.mozilla || jQuery.browser.msie) {
			// get borders
			var bt = parseInt(jQuery.css(parent, 'borderTopWidth')) || 0;
			var bl = parseInt(jQuery.css(parent, 'borderLeftWidth')) || 0;

			// add borders to offset
			x += bl;
			y += bt;

			// Mozilla removes the border if the parent has overflow property other than visible
			if (jQuery.browser.mozilla && parent != elem && jQuery.css(parent, 'overflow') != 'visible') {
				x += bl;
				y += bt;
			}
			
			// Mozilla does not include the border on body if an element isn't positioned absolute and is without an absolute parent
			if (jQuery.css(parent, 'position') == 'absolute') absparent = true;
			// IE does not include the border on the body if an element is position static and without an absolute or relative parent
			if (jQuery.css(parent, 'position') == 'relative') relparent = true;
		}

		if (options.scroll) {
			// Need to get scroll offsets in-between offsetParents
			op = parent.offsetParent;
			do {
				sl += parent.scrollLeft || 0;
				st += parent.scrollTop  || 0;

				parent = parent.parentNode;

				// Mozilla removes the border if the parent has overflow property other than visible
				if (jQuery.browser.mozilla && parent != elem && parent != op && jQuery.css(parent, 'overflow') != 'visible') {
					x += parseInt(jQuery.css(parent, 'borderLeftWidth')) || 0;
					y += parseInt(jQuery.css(parent, 'borderTopWidth')) || 0;
				}
			} while (op && parent != op);
		} else
			parent = parent.offsetParent;

		if (parent && (parent.tagName.toLowerCase() == 'body' || parent.tagName.toLowerCase() == 'html')) {
			// Safari and IE Standards Mode doesn't add the body margin for elments positioned with static or relative
			if ((jQuery.browser.safari || (jQuery.browser.msie && jQuery.boxModel)) && jQuery.css(elem, 'position') != 'absolute') {
				x += parseInt(jQuery.css(parent, 'marginLeft')) || 0;
				y += parseInt(jQuery.css(parent, 'marginTop'))  || 0;
			}
			// Mozilla does not include the border on body if an element isn't positioned absolute and is without an absolute parent
			// IE does not include the border on the body if an element is positioned static and without an absolute or relative parent
			if ( (jQuery.browser.mozilla && !absparent) || 
			     (jQuery.browser.msie && jQuery.css(elem, 'position') == 'static' && (!relparent || !absparent)) ) {
				x += parseInt(jQuery.css(parent, 'borderLeftWidth')) || 0;
				y += parseInt(jQuery.css(parent, 'borderTopWidth'))  || 0;
			}
			break; // Exit the loop
		}
	} while (parent);

	if ( !options.margin) {
		x -= parseInt(jQuery.css(elem, 'marginLeft')) || 0;
		y -= parseInt(jQuery.css(elem, 'marginTop'))  || 0;
	}

	// Safari and Opera do not add the border for the element
	if ( options.border && (jQuery.browser.safari || jQuery.browser.opera) ) {
		x += parseInt(jQuery.css(elem, 'borderLeftWidth')) || 0;
		y += parseInt(jQuery.css(elem, 'borderTopWidth'))  || 0;
	} else if ( !options.border && !(jQuery.browser.safari || jQuery.browser.opera) ) {
		x -= parseInt(jQuery.css(elem, 'borderLeftWidth')) || 0;
		y -= parseInt(jQuery.css(elem, 'borderTopWidth'))  || 0;
	}

	if ( options.padding ) {
		x += parseInt(jQuery.css(elem, 'paddingLeft')) || 0;
		y += parseInt(jQuery.css(elem, 'paddingTop'))  || 0;
	}

	// Opera thinks offset is scroll offset for display: inline elements
	if (options.scroll && jQuery.browser.opera && jQuery.css(elem, 'display') == 'inline') {
		sl -= elem.scrollLeft || 0;
		st -= elem.scrollTop  || 0;
	}

	var returnValue = options.scroll ? { top: y - st, left: x - sl, scrollTop:  st, scrollLeft: sl }
	                                 : { top: y, left: x };

	if (returnObject) { jQuery.extend(returnObject, returnValue); return this; }
	else              { return returnValue; }
};

//************

jQuery.fn.extend({
	autocomplete: function(urlOrData, options) {
		var isUrl = typeof urlOrData == "string";
		options = jQuery.extend({}, jQuery.Autocompleter.defaults, {
			url: isUrl ? urlOrData : null,
			data: isUrl ? null : urlOrData,
			delay: isUrl ? jQuery.Autocompleter.defaults.delay : 10
		}, options);
		return this.each(function() {
			new jQuery.Autocompleter(this, options);
		});
	},
	result: function(handler) {
		return this.bind("result", handler);
	},
	search: function() {
		return this.trigger("search");
	}
});

jQuery.Autocompleter = function(input, options) {

	var KEY = {
		UP: 38,
		DOWN: 40,
		DEL: 46,
		TAB: 9,
		RETURN: 13,
		ESC: 27,
		COMMA: 188
	};

	// Create jQuery object for input element
	var $input = $(input).attr("autocomplete", "off").addClass(options.inputClass);

	var timeout;
	var previousValue = "";
	var cache = jQuery.Autocompleter.Cache(options);
	var hasFocus = 0;
	var lastKeyPressCode;
	var select = jQuery.Autocompleter.Select(options, input, selectCurrent);
	
	$input.keydown(function(event) {
		// track last key pressed
		lastKeyPressCode = event.keyCode;
		switch(event.keyCode) {
		
			case KEY.UP:
				event.preventDefault();
				if ( select.visible() ) {
					select.prev();
				} else {
					onChange(0, true);
				}
				break;
				
			case KEY.DOWN:
				event.preventDefault();
				if ( select.visible() ) {
					select.next();
				} else {
					onChange(0, true);
				}
				break;
			
			// matches also semicolon
			case options.multiple && jQuery.trim(options.multipleSeparator) == "," && KEY.COMMA:
			case KEY.TAB:
			case KEY.RETURN:
				if( selectCurrent() ){
					// make sure to blur off the current field
					/*
					alert('Merdazz');
					this.nextSibling; 
					if( !options.multiple )
						$input.blur();
					event.preventDefault();*/
				}
				break;
				
			case KEY.ESC:
				select.hide();
				break;
				
			default:
				clearTimeout(timeout);
				timeout = setTimeout(onChange, options.delay);
				break;
		}
	}).keypress(function() {
		// having fun with opera - remove this binding and Opera submits the form when we select an entry via return
	}).focus(function(){
		// track whether the field has focus, we shouldn't process any
		// results if the field no longer has focus
		hasFocus++;
	}).blur(function() {
		hasFocus = 0;
		hideResults();
	}).click(function() {
		// show select when clicking in a focused field
		if ( hasFocus++ > 1 && !select.visible() ) {
			onChange(0, true);
		}
	}).bind("search", function() {
		function findValueCallback(q, data) {
			var result;
			if( data && data.length ) {
				for (var i=0; i < data.length; i++) {
					if( data[i].result.toLowerCase() == q.toLowerCase() ) {
						result = data[i];
						break;
					}
				}
			}
			$input.trigger("result", result && [result.data, result.value]);
		}
		jQuery.each(trimWords($input.val()), function(i, value) {
			request(value, findValueCallback, findValueCallback);
		});
	});
	
	hideResultsNow();
	
	function selectCurrent() {
		var selected = select.selected();
		if( !selected )
			return false;
		
		var v = selected.result;
		previousValue = v;
		
		if ( options.multiple ) {
			var words = trimWords($input.val());
			if ( words.length > 1 ) {
				v = words.slice(0, words.length - 1).join( options.multipleSeparator ) + options.multipleSeparator + v;
			}
			v += options.multipleSeparator;
		}
		
		$input.val(v);
		hideResultsNow();
		$input.trigger("result", [selected.data, selected.value]);
		return true;
	}
	
	function onChange(crap, skipPrevCheck) {
		if( lastKeyPressCode == KEY.DEL ) {
			select.hide();
			return;
		}
		
		var currentValue = $input.val();
		
		if ( !skipPrevCheck && currentValue == previousValue )
			return;
		
		previousValue = currentValue;
		
		currentValue = lastWord(currentValue);
		if ( currentValue.length >= options.minChars) {
			$input.addClass(options.loadingClass);
			if (!options.matchCase)
				currentValue = currentValue.toLowerCase();
			request(currentValue, receiveData, stopLoading);
		} else {
			stopLoading();
			select.hide();
		}
	};
	
	function trimWords(value) {
		if ( !value ) {
			return [""];
		}
		var words = value.split( jQuery.trim( options.multipleSeparator ) );
		var result = [];
		jQuery.each(words, function(i, value) {
			if ( jQuery.trim(value) )
				result[i] = jQuery.trim(value);
		});
		return result;
	}
	
	function lastWord(value) {
		if ( !options.multiple )
			return value;
		var words = trimWords(value);
		return words[words.length - 1];
	}
	
	// fills in the input box w/the first match (assumed to be the best match)
	function autoFill(q, sValue){
		// autofill in the complete box w/the first match as long as the user hasn't entered in more data
		// if the last user key pressed was backspace, don't autofill
		if( options.autoFill && (lastWord($input.val()).toLowerCase() == q.toLowerCase()) && lastKeyPressCode != 8 ) {
			// fill in the value (keep the case the user has typed)
			$input.val($input.val() + sValue.substring(lastWord(previousValue).length));
			// select the portion of the value not typed by the user (so the next character will erase)
			jQuery.Autocompleter.Selection(input, previousValue.length, previousValue.length + sValue.length);
		}
	};

	function hideResults() {
		clearTimeout(timeout);
		timeout = setTimeout(hideResultsNow, 200);
	};

	function hideResultsNow() {
		select.hide();
		clearTimeout(timeout);
		stopLoading();
		// TODO fix mustMatch...
		if (options.mustMatch) {
			if ($input.val() != previousValue) {
				//selectCurrent();
			}
		}
	};

	function receiveData(q, data) {
		if ( data && data.length && hasFocus ) {
			stopLoading();
			select.display(data, q);
			autoFill(q, data[0].value);
			select.show();
		} else {
			hideResultsNow();
		}
	};

	function request(term, success, failure) {
		if (!options.matchCase)
			term = term.toLowerCase();
		var data = cache.load(term);
		// recieve the cached data
		if (data && data.length) {
			success(term, data);
		// if an AJAX url has been supplied, try loading the data now
		} else if( (typeof options.url == "string") && (options.url.length > 0) ){
			jQuery.ajax({
				url: options.url,
				type: 'post',
				data: jQuery.extend({
					q: lastWord(term),
					limit: options.max
				}, options.extraParams),
				success: function(data)
                {
					//Substituir &#039; por apostrofo ('), &amp; por e comercial (&)
                    data = data.replace(/&#039;/gi, "'");
                    data = data.replace(/&amp;/gi, "&");

                    var parsed = options.parse && options.parse(data) || parse(data);
					cache.add(term, parsed);
					success(term, parsed);
				}
			});
		} else {
			failure(term);
		}
	}
	
	function parse(data) {
		var parsed = [];
		var rows = data.split("\n");
		for (var i=0; i < rows.length; i++) {
			var row = jQuery.trim(rows[i]);
			if (row) {
				row = row.split("|");
				parsed[parsed.length] = {
					data: row,
					value: row[0],
					result: options.formatResult && options.formatResult(row) || row[0]
				};
			}
		}
		return parsed;
	}

	function stopLoading() {
		$input.removeClass(options.loadingClass);
	}

}

jQuery.Autocompleter.defaults = {
	inputClass: "ac_input",
	resultsClass: "ac_results",
	loadingClass: "ac_loading",
	minChars: 1,
	delay: 400,
	matchCase: false,
	matchSubset: true,
	matchContains: false,
	cacheLength: 10,
	mustMatch: false,
	extraParams: {},
	selectFirst: true,
	max: 10,
	//size: 10,
	autoFill: false,
	width: 0,
	multiple: false,
	multipleSeparator: ", "
};

jQuery.Autocompleter.Cache = function(options) {

	var data = {};
	var length = 0;
	
	function matchSubset(s, sub) {
		if (!options.matchCase) 
			s = s.toLowerCase();
		var i = s.indexOf(sub);
		if (i == -1) return false;
		return i == 0 || options.matchContains;
	};
	
	function add(q, value) {
			if (length > options.cacheLength) {
				this.flush();
			}
			if (!data[q]) {
				length++;
			}
			data[q] = value;
		}
	
	// if there is a data array supplied
	if( options.data ){
		var stMatchSets = {},
			nullData = 0;

		// no url was specified, we need to adjust the cache length to make sure it fits the local data store
		if( !options.url ) options.cacheLength = 1;
		
		stMatchSets[""] = [];

		// loop through the array and create a lookup structure
		jQuery.each(options.data, function(i, rawValue) {
			// if row is a string, make an array otherwise just reference the array
			
			
			value = options.formatItem
				? options.formatItem(rawValue, i+1, options.data.length)
				: rawValue;
			var firstChar = value.charAt(0).toLowerCase();
			// if no lookup array for this character exists, look it up now
			if( !stMatchSets[firstChar] )
				stMatchSets[firstChar] = [];
			// if the match is a string
			var row = {
				value: value,
				data: rawValue,
				result: options.formatResult && options.formatResult(rawValue) || value
			}
			
			stMatchSets[firstChar].push(row);
			
			if ( nullData++ < options.max ) {
				stMatchSets[""].push(row);
			}
			
		});

		// add the data items to the cache
		jQuery.each(stMatchSets, function(i, value) {
			// increase the cache size
			options.cacheLength++;
			// add to the cache
			add(i, value);
		});
	}
	
	return {
		flush: function() {
			data = {};
			length = 0;
		},
		add: add,
		load: function(q) {
			if (!options.cacheLength || !length)
				return null;
			if (data[q])
				return data[q];
			if (options.matchSubset) {
				for (var i = q.length - 1; i >= options.minChars; i--) {
					var c = data[q.substr(0, i)];
					if (c) {
						var csub = [];
						jQuery.each(c, function(i, x) {
							if (matchSubset(x.value, q)) {
								csub[csub.length] = x;
							}
						});
						return csub;
					}
				}
			}
			return null;
		}
	};
};

jQuery.Autocompleter.Select = function (options, input, select) {
	var CLASSES = {
		ACTIVE: "ac_over"
	};
	
	// Create results
	var element = jQuery("<div>")
		.hide()
		.addClass(options.resultsClass)
		.css("position", "absolute")
		.appendTo("body");

	var list = jQuery("<ul>").appendTo(element).mouseover( function(event) {
		active = jQuery("li", list).removeClass(CLASSES.ACTIVE).index(target(event));
		jQuery(target(event)).addClass(CLASSES.ACTIVE);
	}).mouseout( function(event) {
		jQuery(target(event)).removeClass(CLASSES.ACTIVE);
	}).click(function(event) {
		jQuery(target(event)).addClass(CLASSES.ACTIVE);
		select();
		input.focus();
		return false;
	});
	var listItems,
		active = -1,
		data,
		term = "";
		
	if( options.width > 0 )
		element.css("width", options.width);
		
	function target(event) {
		var element = event.target;
		while(element.tagName != "LI")
			element = element.parentNode;
		return element;
	}

	function moveSelect(step) {
		active += step;
		wrapSelection();
		listItems.removeClass(CLASSES.ACTIVE).eq(active).addClass(CLASSES.ACTIVE);
	};
	
	function wrapSelection() {
		if (active < 0) {
			active = listItems.size() - 1;
		} else if (active >= listItems.size()) {
			active = 0;
		}
	}
	
	function limitNumberOfItems(available) {
		return (options.max > 0) && (options.max < available)
			? options.max
			: available;
	}
	
	function dataToDom() {
		var num = limitNumberOfItems(data.length);
		for (var i=0; i < num; i++) {
			if (!data[i])
				continue;
			function highlight(value) {
				return value.replace(new RegExp("(" + term + ")", "gi"), "<strong>$1</strong>");
			}
			jQuery("<li>").html( options.formatItem 
					? highlight(options.formatItem(data[i].data, i+1, num))
					: highlight(data[i].value) ).appendTo(list);
		}
		listItems = list.find("li");
		if ( options.selectFirst ) {
			listItems.eq(0).addClass(CLASSES.ACTIVE);
			active = 0;
		}
	}
	
	return {
		display: function(d, q) {
			data = d;
			term = q;
			list.empty();
			dataToDom();
			list.bgiframe();
		},
		next: function() {
			moveSelect(1);
		},
		prev: function() {
			moveSelect(-1);
		},
		hide: function() {
			element.hide();
			active = -1;
		},
		visible : function() {
			return element.is(":visible");
		},
		current: function() {
			return this.visible() && (listItems.filter("." + CLASSES.ACTIVE)[0] || options.selectFirst && listItems[0]);
		},
		show: function() {
			// get the position of the input field right now (in case the DOM is shifted)
			var offset = jQuery(input).offset({scroll: false, border: false});
			// either use the specified width, or autocalculate based on form element
			element.css({
				width: options.width > 0 ? options.width : jQuery(input).width(),
				//height: jQuery(listItems[0]).height() * options.size,
				top: offset.top + input.offsetHeight,
				left: offset.left
			}).show();
			//active = -1;
			//listItems.removeClass(CLASSES.ACTIVE);
		},
		selected: function() {
			return data && data[active];
		}
	};
}

jQuery.Autocompleter.Selection = function(field, start, end) {
	if( field.createTextRange ){
		var selRange = field.createTextRange();
		selRange.collapse(true);
		selRange.moveStart("character", start);
		selRange.moveEnd("character", end);
		selRange.select();
	} else if( field.setSelectionRange ){
		field.setSelectionRange(start, end);
	} else {
		if( field.selectionStart ){
			field.selectionStart = start;
			field.selectionEnd = end;
		}
	}
	field.focus();
};

function formatItem(row){ return row[0];} 
function formatResult(row) { return row[0];}