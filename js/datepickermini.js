/* global document: false */
function DatePickerMini() {
    'use strict';
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

    this.init = function (dpm_name, dpm_typedata, dpm_selector) {
        name = dpm_name;
        typedata = dpm_typedata;
        selector = dpm_selector;
        this.getdate();
    };

    this.getdate = function () {
        day = document.getElementById(name +
                'day').options[document.getElementById(name +
                'day').selectedIndex].value;
        month = document.getElementById(name +
                'month').options[document.getElementById(name +
                'month').selectedIndex].value;
        year = document.getElementById(name +
                'year').options[document.getElementById(name +
                'year').selectedIndex].value;
        this.changecountday();
        this.inserttofield(this.constructdate());
    };

    this.changecountday = function () {
        var res = '';
        var exist = false;
        var i;
        for (i = 1; i <= this.countdayinmonth(); i++) {
            res = res + '<option value =';
            if (i < 10) {
                res = res + '"0' + i + '"';
            } else {
                res = res + '"' + i + '"';
            }
            if (i === day * 1) {
                res = res + ' selected';
                exist = true;
            }
            res = res + '>' + i + '</option>';
        }
        if (!exist) {
            day = '01';
        }
        document.getElementById(name + 'day').innerHTML = res;
    };

    this.countdayinmonth = function () {
        var count = {
            '01': '31',
            '02': this.countfebruary(),
            '03': '31',
            '04': '30',
            '05': '31',
            '06': '30',
            '07': '31',
            '08': '31',
            '09': '30',
            '10': '31',
            '11': '30',
            '12': '31'
        };
        return count[month];
    };

    this.countfebruary = function () {
        if ((year * 1) % 4 === 0) {
            return 29;
        } else {
            return 28;
        }
    };

    this.constructdate = function () {
        var res = '';
        switch (typedata[0]) {
        case 'd':
            res = res + day;
            break;
        case 'm':
            res = res + month;
            break;
        case 'y':
            res = res + year;
            break;
        }
        res = res + selector;
        switch (typedata[1]) {
        case 'd':
            res = res + day;
            break;
        case 'm':
            res = res + month;
            break;
        case 'y':
            res = res + year;
            break;
        }
        res = res + selector;
        switch (typedata[2]) {
        case 'd':
            res = res + day;
            break;
        case 'm':
            res = res + month;
            break;
        case 'y':
            res = res + year;
            break;
        }
        return res;
    };

    this.inserttofield = function (data) {
        document.getElementById(name).value = data;
    };
}