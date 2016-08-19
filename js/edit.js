/* global document: false */
function AdminJs(apikey, etutorium_id) {
    'use strict';
    this.apikey = apikey;
    this.etutorium_id = etutorium_id;
    this.userweblist = [];
    this.allweblist = [];

    this.viewpanel = function () {
        document.getElementById('error').innerHTML = '';
        if (document.getElementById('searchpanel').style.display === 'none') {
            etutorium_send(
                'getAccounts.php',
                {apikey: this.apikey},
                function (data) {
                    if (data.error !== '') {
                        document.getElementById('error').innerHTML = data.error;
                    } else {
                        var res = '<select id="accountselect">';
                        Object.keys(data.result).forEach(function (key) {
                            res = res + '<option value=\'' + data.result[key].id + '\'>' + data.result[key].last_name + ' ' + data.result[key].first_name + '<\/option>';
                        });
                        res = res + '<\/select>';
                        document.getElementById('account').innerHTML = res;
                        document.getElementById('searchpanel').style.display = 'block';
                    }
                }
            );
        } else {
            document.getElementById('searchpanel').style.display = 'none';
        }
    };

    this.getallwebinarlist = function () {
        document.getElementById('error').innerHTML = '';
        var data = {};
        data.apikey = this.apikey;
        data.etutorium = this.etutorium_id;
        if (document.getElementById('searchpanel').style.display === 'block') {
            if (document.getElementById('useaccount').checked) {
                data.account_id = document.getElementById('accountselect').options[document.getElementById('accountselect').selectedIndex].value;
            }
            if (document.getElementById('usestart_time').checked) {
                data.start_time = document.getElementById('start_time').value;
            }
            if (document.getElementById('usefinish_time').checked) {
                data.finish_time = document.getElementById('finish_time').value;
            }
        }
        var q = this;
        etutorium_send(
            'getWebinars.php',
            data,
            function (data) {
                if (data.error !== '') {
                    document.getElementById('error').innerHTML = data.error;
                } else {
                    document.getElementById('allwebinarlist').innerHTML = data.result.table;
                    q.setallweblist(data.result.data);
                }
            }
        );
    };

    this.getwebinarbyid = function (dataid, data) {
        var result = false;
        var webkey;
        Object.keys(data).forEach(function (key) {
            if (parseInt(data[key].id) === dataid) {
                result = data[key];
                webkey = key;
            }
        });
        return {result: result, key: webkey};
    };

    this.viewmore = function (dataid, id) {
        var webinar = '';
        if (id === 'allweblist') {
            webinar = this.getwebinarbyid(dataid, this.allweblist);
        } else {
            webinar = this.getwebinarbyid(dataid, this.userweblist);
        }
        if (webinar.result === false) {
            alert('Not found');
        } else {
            document.getElementById(id + 'moreinfo').innerHTML = '<table style="width:100%;">' +
                    '<tr>' +
                    '<td style="text-align:right; cursor:pointer;" onclick="webinaredit.closeinfo(\'' + id + '\')" colspan=2> X </td>' +
                    '</tr>' +
                    '<tr>' +
                    '<th style="text-align:left; width:40%;">' + M.util.get_string('title', 'etutorium') + '</th>' +
                    '<td>' + webinar.result.title + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<th style="text-align:left;">' + M.util.get_string('start_time', 'etutorium') + '</th>' +
                    '<td>' + webinar.result.start_time + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<th style="text-align:left;">' + M.util.get_string('finish_time', 'etutorium') + '</th>' +
                    '<td>' + webinar.result.finish_time + '</td>' +
                    '</tr>' +
                    '<tr>' +
                    '<th colspan="2" style="text-align:left;">' + M.util.get_string('description', 'etutorium') + '</th>' +
                    '</tr>' +
                    '<tr>' +
                    '<td>' + webinar.result.description + '</td>' +
                    '</tr>' +
                    '</table>';
            document.getElementById(id).style.width = '50%';
            document.getElementById(id + 'moreinfo').style.width = '50%';
            document.getElementById(id + 'moreinfo').style.display = 'block';
        }
    };

    this.setallweblist = function (data) {
        this.allweblist = data;
    };

    this.setuserweblist = function (data) {
        this.userweblist = data;
    };

    this.closeinfo = function (id) {
        document.getElementById(id + 'moreinfo').style.display = 'none';
        document.getElementById(id + 'moreinfo').style.width = '0%';
        document.getElementById(id).style.width = '100%';
    };

    this.getuserwebinarlist = function () {
        document.getElementById('error').innerHTML = '';
        var data = {};
        data.apikey = this.apikey;
        data.etutorium = this.etutorium_id;
        var q = this;
        etutorium_send(
            'getUserWebinars.php',
            data,
            function (data) {
                if (data.error !== '') {
                    document.getElementById('error').innerHTML = data.error;
                } else {
                    document.getElementById('usewebinarlist').innerHTML = data.result.table;
                    q.setuserweblist(data.result.data);
                }
            }
        );
    };

    this.addWebinar = function (id) {
        document.getElementById('error').innerHTML = '';
        var data = this.getwebinarbyid(id, this.allweblist);
        var requestdata = data.result;
        requestdata.etutorium_id = this.etutorium_id;
        requestdata.apikey = this.apikey;
        var q = this;
        etutorium_send(
            'addWebinar.php',
            requestdata,
            function (result) {
                if (result.result !== 'ok') {
                    alert(M.util.get_string('adderror', 'etutorium'));
                } else {
                    var newallweblist = [];
                    Object.keys(q.allweblist).forEach(function (key) {
                        if (parseInt(q.allweblist[key].id) !== id) {
                            newallweblist.push(q.allweblist[key]);
                        }
                    });
                    q.allweblist = newallweblist;
                    document.getElementById('allweblist' + id).remove();
                    q.getuserwebinarlist();
                }
            }
        );
    };

    this.delWebinar = function (id) {
        document.getElementById('error').innerHTML = '';
        var data = this.getwebinarbyid(id, this.userweblist);
        var q = this;
        etutorium_send(
            'delWebinar.php',
            {
                id: id,
                etutorium_id: this.etutorium_id
            },
            function (result) {
                if (result.result !== 'ok') {
                    alert(M.util.get_string('adderror', 'etutorium'));
                } else {
                    q.userweblist.splice(data.key, 1);
                    document.getElementById('userweblist' + id).remove();
                    q.getallwebinarlist();
                }
            }
        );
    };
}
