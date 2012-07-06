/////////////////// Plug-in file for CalendarXP 9.0 /////////////////
// This file is totally configurable. You may remove all the comments in this file to minimize the download size.
/////////////////////////////////////////////////////////////////////

///////////// Calendar Onchange Handler ////////////////////////////
// It's triggered whenever the calendar gets changed to y(ear),m(onth),d(ay)
// d = 0 means the calendar is about to switch to the month of (y,m); 
// d > 0 means a specific date [y,m,d] is about to be selected.
// e is a reference to the triggering event object
// Return a true value will cancel the change action.
// NOTE: DO NOT define this handler unless you really need to use it.
////////////////////////////////////////////////////////////////////
function fOnChange(y,m,d,e) {
	if (d==0) {
		var lastDay=fGetDays(y)[m];
		fUpdSelect(y,m,lastDay<gdSelect[2]?lastDay:gdSelect[2]);	// keep day of month updated
	}
}


///////////// Calendar AfterSelected Handler ///////////////////////
// It's triggered whenever a date gets fully selected.
// The selected date is passed in as y(ear),m(onth),d(ay)
// e is a reference to the triggering event object
// NOTE: DO NOT define this handler unless you really need to use it.
////////////////////////////////////////////////////////////////////
// function fAfterSelected(y,m,d,e) {}


///////////// Calendar Cell OnDrag Handler ///////////////////////
// It triggered when you try to drag a calendar cell. (y,m,d) is the cell date. 
// aStat = 0 means a mousedown is detected (dragstart)
// aStat = 1 means a mouseover between dragstart and dragend is detected (dragover)
// aStat = 2 means a mouseup is detected (dragend)
// e is a reference to the triggering event object
// NOTE: DO NOT define this handler unless you really need to use it.
//       If you use fRepaint() here, fAfterSelected() will be ignored.
////////////////////////////////////////////////////////////////////

// function fOnDrag(y,m,d,aStat,e) {}



////////////////// Calendar OnResize Handler ///////////////////////
// It's triggered after the calendar panel has finished drawing.
// NOTE: DO NOT define this handler unless you really need to use it.
////////////////////////////////////////////////////////////////////
function fOnResize() {
	// update the time fields on the calendar
	// you may move the following lines into the fParseInput() if you don't want to support NN4.
	var bf=document.cxpBottomForm;
	var t=_timeVal.match(_timeFormat);
	if (t) {
		bf.hourF.value=t[1];
		bf.minF.value=t[2];
		if (!_is24H) bf.ampm.value=t[3];
	}
}


////////////////// Calendar fOnWeekClick Handler ///////////////////////
// It's triggered when the week number is clicked.
// NOTE: DO NOT define this handler unless you really need to use it.
////////////////////////////////////////////////////////////////////
// function fOnWeekClick(year, weekNo) {}

////////////////// Calendar fOnDoWClick Handler ///////////////////////
// It's triggered when the week head (day of week) is clicked.
// dow ranged from 0-6 while 0 denotes Sunday, 6 denotes Saturday.
// NOTE: DO NOT define this handler unless you really need to use it.
////////////////////////////////////////////////////////////////////
// function fOnDoWClick(year, month, dow) {}


////////////////// Calendar fIsSelected Callback ///////////////////////
// It's triggered for every date passed in as y(ear) m(onth) d(ay). And if 
// the return value is true, that date will be rendered using the giMarkSelected,
// gcFGSelected, gcBGSelected and guSelectedBGImg theme options.
// NOTE: If NOT defined here, the engine will create one that checks the gdSelect only.
////////////////////////////////////////////////////////////////////
// function fIsSelected(y,m,d) {
//		return gdSelect[2]==d&&gdSelect[1]==m&&gdSelect[0]==y;
// }


////////////////// Calendar fParseInput Handler ///////////////////////
// Once defined, it'll be used to parse the input string stored in gdCtrl.value.
// It's expected to return an array of [y,m,d] to indicate the parsed date,
// or null if the input str can't be parsed as a date.
// NOTE: If NOT defined here, the engine will create one matching fParseDate().
////////////////////////////////////////////////////////////////////
function fParseInput(str) {
	if (gbHideCalMiddle) {
		_timeVal=formatTime(str);
		return [0,0,1]; // make date > 0 so as not to clear the input field
	} else {
		var dt=str.split(_separator_datetime);
		_timeVal=formatTime(str.substring(dt[0].length+_separator_datetime.length));
		return fParseDate(dt[0]);
	}
}


////////////////// Calendar fFormatInput Handler ///////////////////////
// Once defined, it'll be used to format the selected date - y(ear) m(onth) d(ay)
// into gdCtrl.value.
// It's expected to return a formated date string.
// NOTE: If NOT defined here, the engine will create one matching fFormatDate().
////////////////////////////////////////////////////////////////////
function fFormatInput(y,m,d) {
	if (gbHideCalMiddle)
		return _timeVal;
	else
//		return fFormatDate(y,m,d)+_separator_datetime+_timeVal;
		return _timeVal+_separator_datetime+fFormatDate(y,m,d);
}

////////////////// Calendar fOnload Handler ///////////////////////
// It's triggered when the calendar engine is fully loaded by the browser.
// NOTE: DO NOT define this handler unless you really need to use it.
////////////////////////////////////////////////////////////////////
// function fOnload() {}


// ====== predefined utility functions for use with agendas. ========

// load an url in the window/frame designated by "framename".
function popup(url,framename) {	
	var w=parent.open(url,framename,"top=200,left=200,width=400,height=200,scrollbars=1,resizable=1");
	if (w&&url.split(":")[0]=="mailto") w.close();
	else if (w&&!framename) w.focus();
}

// ====== Following are self-defined and/or custom-built functions! =======



// ======= the following plugin is coded for the time picker ========
// To enable time picker in other themes, simply copy this part of code into their plugins.js files
// and merge the fOnResize, fParseInput and fFormatInput functions.

// If you hide top and middle part, you will get a time only picker.
//gbHideTop=true;
//gbHideCalMiddle=true;
gbHideBottom=false;
var _is24H=true;	// use 24-hour format or not.
var _hour_marker=":"; // the char between hour and minute
var _time_marker=" "; // the char between the time and "AM|PM".
var _separator_datetime="_"; // the char between date and time.
var _scrollTime=200;	// scrolling delay in milliseconds
var _inc=5;	// incremental time interval in minutes
var _AM="AM", _PM="PM";

var _timeVal,_timeFormat=new RegExp(_is24H?"^([0-1]?[0-9]|2[0-3])[^0-9]+([0-5]?[0-9])$":"^([0]?[1-9]|1[0-2])[^0-9]+([0-5]?[0-9]).*("+_AM+"|"+_PM+")$");

gsBottom=('<table align="center" border="0" cellpadding="0" cellspacing="0" width="1" height="8"><tr><td>&nbsp;</td><td><input type="text" name="hourF" size="2" maxlength="2" class="TimeBox" onchange="updateTimeStr()" onfocus="this.value=\'\'"></td><td><div id="arrow-up" onmousedown="incHour();" onmouseup="stopTime();"></div><div style="height: 2px;"></div><div id="arrow-down" onmousedown="decHour();" onmouseup="stopTime();"></td><td nowrap>'+_hour_marker+'&nbsp;</td><td><input type="text" name="minF" size="2" maxlength="2" class="TimeBox" onchange="updateTimeStr()" onfocus="this.value=\'\'"></div></td><td><div id="arrow-up" onmousedown="incMin();" onmouseup="stopTime();"></div><div style="height: 2px;"></div><div id="arrow-down" onmousedown="decMin();" onmouseup="stopTime()"></div></td>'+(_is24H?'':'<td>'+_time_marker+'&nbsp;</td><td><input type="Text" name="ampm" size="2" maxlength="2" class="TimeBox" readonly onfocus="flipAmPm();this.blur()"></td>')+'<td>&nbsp;&nbsp;</td><td valign="middle">&nbsp;</td><td>&nbsp;</td></tr></table>');
if(NN4)_nn4_css.push("TimeBox");

function time2str(hour, minute, ampm) { // format time and round it according to interval
	return padZero(hour)+_hour_marker+padZero(Math.floor(minute/_inc)*_inc)+(_is24H?'':_time_marker+ampm);
}

function formatTime(str) {
	if (_timeFormat.test(str)==false) { // use current time if str is invalid
		var nd=new Date(), h=nd.getHours(), sign=h>11?_PM:_AM;
		if (!_is24H&&(h>12||h==0)) h=Math.abs(h-12);
		return time2str(h,nd.getMinutes(),sign);
	} else
		return str;
}

function padZero(n) {
	n=parseInt(n,10);
	return n<10?'0'+n:n;
}

function updateTimeStr() {
	var bf=document.cxpBottomForm
	var hv=parseInt(bf.hourF.value,10), mv=parseInt(bf.minF.value,10);
	if (_is24H) bf.hourF.value=hv>=0&&hv<=23?padZero(hv):"00";
	else  bf.hourF.value=hv>=1&&hv<=12?padZero(hv):"12";
	bf.minF.value=mv>=0&&mv<=59?padZero(Math.floor(mv/_inc)*_inc):"00";
	_timeVal=time2str(bf.hourF.value,bf.minF.value,_is24H?"":bf.ampm.value);
	if (gdSelect[2]>0) {
		gdCtrl.value=fFormatInput(gdSelect[0],gdSelect[1],gdSelect[2]);
	}
}

var _th=null;
function incMin(){
	if (!_th) _th=setInterval(NN4?incMin:"incMin()",_scrollTime);  // must be first line
	var bf=document.cxpBottomForm, m=parseInt(bf.minF.value,10)+_inc;
	if (m>59) { m=0; incHour(); }
	bf.minF.value=padZero(m);
	updateTimeStr();
}
function decMin(){
	if (!_th) _th=setInterval(NN4?decMin:"decMin()",_scrollTime);  // must be first line
	var bf=document.cxpBottomForm, m=parseInt(bf.minF.value,10)-_inc;
	if (m<0) { m=60-_inc; decHour(); }
	bf.minF.value=padZero(m);
	updateTimeStr();
}
function incHour(){
	if (!_th) _th=setInterval(NN4?incHour:"incHour()",_scrollTime);
	var bf=document.cxpBottomForm, h=parseInt(bf.hourF.value,10), maxh=_is24H?23:12;
	if (++h>maxh) h=_is24H?0:1;
	if (h==12) flipAmPm();
	bf.hourF.value=padZero(h);
	updateTimeStr();
}
function decHour(){
	if (!_th) _th=setInterval(NN4?decHour:"decHour()",_scrollTime);
	var bf=document.cxpBottomForm, h=parseInt(bf.hourF.value,10);
	if (_is24H) {
		if (--h<0) h=23;
	} else
		if (--h==0) h=12; 
	if (h==11) flipAmPm();
	bf.hourF.value=padZero(h);
	updateTimeStr();
}
function stopTime(){
	clearInterval(_th);
	_th=null;
}
function flipAmPm() {
	if (_is24H) return;
	var bf=document.cxpBottomForm;
	bf.ampm.value=bf.ampm.value==_AM?_PM:_AM;
	updateTimeStr();
}
// ======= end of time picker plugin ========


// ======= the following plugin is coded for the artificial internal dropdown seletors ========
// You may change the left,top in the fPopMenu() to adjust the popup position.
// Other Settings
var _highlite_background="#D4D0C8";	// highlight background color
var _highlite_fontColor="white";	// highlight font color
var _pop_length=7;	// how many months to be shown
var _pop_width=100;	// pixels of the popup width

// Override the gsCalTitle option to popup a date-selector layer. Remember to keep it as an expression or a function returning a string.
gsCalTitle="\"<a class='PopAnchor' href='javascript:void(0);' onclick='if(this.blur)this.blur();fPopMenu(this,event);return false;'>\"+gMonths[gCurMonth[1]-1]+' '+gCurMonth[0]+\"</a>\"";



function fPopMenu(dc,e) {
	var lyr=NN4?document.freeDiv0:fGetById(document,"freeDiv0");
	var bv=NN4?lyr.visibility=="show":lyr.style.visibility=="visible";
	if (bv) { fToggleLayer(0,false); return; }
	fSetDPop(gCurMonth[0],gCurMonth[1]);
	if (NN4) with (lyr) {
		left=43;
		top=4;
	} else with (lyr.style) {
		left=43+"px";
		top=4+"px";
	}
	fToggleLayer(0,true);
}

var _tmid=null;
function fSetDPop(y,m) {
	var mi=_pop_length;
	var wd=_pop_width;
	var sME=NN4||IE4?"":" onmouseover='fToggleColor(this,0)' onmouseout='fToggleColor(this,1)' ";	// menu-item focus background-color
	var padstr="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	var cm=fCalibrate(y,m);
	var a=[NN4||IE4||IE&&MAC?"<table border=1 cellspacing=0 cellpadding=0><tr><td>":"","<div onmouseover='clearTimeout(_tmid)' onmouseout='_tmid=setTimeout(\"fToggleLayer(0,false)\",100)'><table class='PopMenu' border=0 cellspacing=0 cellpadding=0>"];
	if(!fBfRange(cm[0],cm[1]))a.push("<tr><td align='center' class='PopMenuItem' nowrap width=",wd,sME," onclick='fSetDPop(",cm[0],",",cm[1]-mi,")'><a class='PopMenuItem' href='javascript:void(0)' onclick='if(NN4)fSetDPop(",cm[0],",",cm[1]-mi,");return false;'>",padstr,"-",padstr,"</a></td></tr>");
	for (var i=0;i<mi;i++) {
		var lm=fCalibrate(cm[0],cm[1]+i);
		if (!fIsOutRange(lm[0],lm[1]))
			a.push("<tr><td align='center' class='PopMenuItem' nowrap width=",wd,sME," onclick='fToggleLayer(0,false);fSetCal(",lm[0],",",lm[1],",0,true,event);'><a class='PopMenuItem' href='javascript:void(0)' onclick='if(NN4)fSetCal(",lm[0],",",lm[1],",0,true,event);return false;'>",gMonths[lm[1]-1]," ",lm[0],"</a></td></tr>");
	}
	if(!fAfRange(lm[0],lm[1]))a.push("<tr><td align='center' class='PopMenuItem' nowrap width=",wd,sME," onclick='fSetDPop(",cm[0],",",cm[1]+mi,")'><a class='PopMenuItem' href='javascript:void(0)' onclick='if(NN4)fSetDPop(",cm[0],",",cm[1]+mi,");return false;'>",padstr,"+",padstr,"</a></td></tr>")
	a.push("</table></div>",NN4||IE4||IE&&MAC?"</td></tr></table>":"");
	fDrawLayer(0,a.join(''));
}

var _cPair=[];
function fToggleColor(obj,n) {
	if (NN4||IE4) return;
	if (n==0) { // mouseover
		_cPair[0]=obj.style.backgroundColor;
		obj.style.backgroundColor=_highlite_background;
		_cPair[1]=obj.firstChild.style.color;
		obj.firstChild.style.color=_highlite_fontColor;
	} else {
		obj.style.backgroundColor=_cPair[0];
		obj.firstChild.style.color=_cPair[1];
	}
}

function fToggleLayer(id,bShow) {
	var lyr=NN4?eval("document.freeDiv"+id):fGetById(document,"freeDiv"+id);
	if (NN4) lyr.visibility=bShow?"show":"hide";
	else lyr.style.visibility=bShow?"visible":"hidden";
}

function fDrawLayer(id,html) {
	var lyr=NN4?eval("document.freeDiv"+id):fGetById(document,"freeDiv"+id);
	if (IE4||IE&&MAC) lyr.style.border="0px";
	if (NN4) with (lyr.document) {
		clear(); open();
		write(html);
		close();
	} else {
		lyr.innerHTML=html+"\n";
	}
}



// ======= end of dropdown plugin ========
