var DatePickerMini = function(){
	
	var name;
	var day;
	var month;
	var year;
	/*
	 * typedate = {
	 * '0':'d'
	 * '1':'m',
	 * '2':'y'
	 * }
	 */
	var typedata;
	var selector;
	
	this.init = function (name, typedata, selector){
		this.name = name;
		this.typedata = typedata;
		this.selector = selector;
		this.getdate();
	};
	
	this.getdate = function (){
		this.day = document.getElementById(this.name + 'day').options[document.getElementById(this.name + 'day').selectedIndex].value;
		this.month = document.getElementById(this.name + 'month').options[document.getElementById(this.name + 'month').selectedIndex].value;
		this.year = document.getElementById(this.name + 'year').options[document.getElementById(this.name + 'year').selectedIndex].value;
		this.changecountday();
		this.inserttofield(this.constructdate());
	};
	
	this.changecountday = function () {
		res = '';
		exist = false
		for (i=1;i<=this.countdayinmonth();i++) {
			res = res + '<option value ='; 
			if (i < 10)
				res = res + '"0' + i + '"';
			else
				res = res + '"' + i + '"';
			if (i == this.day*1) {
				res = res + ' selected';
				exist = true;
			}
			res = res + '>' + i + '</option>';
		}
		if (!exist)
			this.day = '01';
		document.getElementById(this.name + 'day').innerHTML = res;
	};
	
	this.countdayinmonth = function (){
		count = {
			'01' : '31',
			'02' : this.countfebruary(),
			'03' : '31',
			'04' : '30',
			'05' : '31',
			'06' : '30',
			'07' : '31',
			'08' : '31',
			'09' : '30',
			'10' : '31',
			'11' : '30',
			'12' : '31'
		};
		return count[this.month];
	};
	
	this.countfebruary = function() {
		if ((this.year * 1) % 4 == 0)
			return 29;
		else
			return 28;
	};
	
	this.constructdate = function(){
		res='';
		switch (this.typedata[0]){
			case 'd': res = res + this.day; break;
			case 'm': res = res + this.month; break;
			case 'y': res = res + this.year; break;
		}
		res = res + this.selector;
		switch (this.typedata[1]){
			case 'd': res = res + this.day; break;
			case 'm': res = res + this.month; break;
			case 'y': res = res + this.year; break;
		}
		res = res + this.selector;
		switch (this.typedata[2]){
			case 'd': res = res + this.day; break;
			case 'm': res = res + this.month; break;
			case 'y': res = res + this.year; break;
		}
		console.log(this.name + '-' + res);
		return res;
	};
	
	this.inserttofield=function(data){
		document.getElementById(this.name).value = data;
	};
};