/* global document: false */
/* global variables: document, alert, etutorium_send, M */
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
                'getAccounts',
                {apikey: this.apikey},
                function (data) {
                    if (data.error !== '') {
                        document.getElementById('error').innerHTML = data.error;
                    } else {
                        var select = document.createElement('select');
                        select.setAttribute('id', 'accountselect');
                        var option;
                        Object.keys(data.result).forEach(function (key) {
                            option = document.createElement('option');
                            option.setAttribute('value', data.result[key].id);
                            option.appendChild(
                                document.createTextNode(
                                    data.result[key].last_name + ' ' + data.result[key].first_name
                                )
                            );
                            select.appendChild(option);
                        });
                        var accountblock = document.getElementById('account');
                        accountblock.innerHTML = '';
                        accountblock.appendChild(select);
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
            'getWebinars',
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
            this.moreinfo(id, webinar.result);
        }
    };

    this.moreinfo = function (id, data) {
        var table = document.createElement('table');
        table.style.width = '100%';

        var tr = document.createElement('tr');
        var td = document.createElement('td');
        td.style.cursor = 'pointer';
        td.style.textAlign = 'right';
        td.setAttribute('onclick', 'webinaredit.closeinfo(\'' + id + '\')');
        td.setAttribute('colspan', 2);
        td.appendChild(document.createTextNode(' X '));
        tr.appendChild(td);
        table.appendChild(tr);

        tr = document.createElement('tr');
        var th = document.createElement('th');
        th.style.width = '40%';
        th.style.textAlign = 'left';
        th.appendChild(document.createTextNode(M.util.get_string('title', 'etutorium')));
        tr.appendChild(th);
        td = document.createElement('td');
        td.appendChild(document.createTextNode(data.title));
        tr.appendChild(td);
        table.appendChild(tr);

        tr = document.createElement('tr');
        th = document.createElement('th');
        th.style.width = '40%';
        th.style.textAlign = 'left';
        th.appendChild(document.createTextNode(M.util.get_string('start_time', 'etutorium')));
        tr.appendChild(th);
        td = document.createElement('td');
        td.appendChild(document.createTextNode(data.start_time));
        tr.appendChild(td);
        table.appendChild(tr);

        tr = document.createElement('tr');
        th = document.createElement('th');
        th.style.width = '40%';
        th.style.textAlign = 'left';
        th.appendChild(document.createTextNode(M.util.get_string('finish_time', 'etutorium')));
        tr.appendChild(th);
        td = document.createElement('td');
        td.appendChild(document.createTextNode(data.title));
        tr.appendChild(td);
        table.appendChild(tr);

        tr = document.createElement('tr');
        th = document.createElement('th');
        th.style.textAlign = 'left';
        th.setAttribute('colspan', 2);
        th.appendChild(document.createTextNode(M.util.get_string('description', 'etutorium')));
        tr.appendChild(th);
        table.appendChild(tr);

        tr = document.createElement('tr');
        td = document.createElement('td');
        td.setAttribute('colspan', 2);
        td.appendChild(document.createTextNode(data.description));
        tr.appendChild(td);
        table.appendChild(tr);

        document.getElementById(id).style.width = '50%';
        document.getElementById(id + 'moreinfo').style.width = '50%';
        document.getElementById(id + 'moreinfo').style.display = 'block';
        document.getElementById(id + 'moreinfo').appendChild(table);
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
        document.getElementById(id + 'moreinfo').innerHTML = '';
        document.getElementById(id).style.width = '100%';
    };

    this.getuserwebinarlist = function () {
        document.getElementById('error').innerHTML = '';
        var data = {};
        data.apikey = this.apikey;
        data.etutorium = this.etutorium_id;
        var q = this;
        etutorium_send(
            'getUserWebinars',
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
            'addWebinar',
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
            'delWebinar',
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
