/* global document: false */
function ConnectJs(cjs_apikey, cjs_etutorium) {
    'use strict';
    var apikey = cjs_apikey;
    var etutorium = cjs_etutorium;
    var fieldlist = [];
    var requiredfieldlist = [];
    var count = 0;

    this.setevent = function () {
        var q = this;
        var id;
        Object.keys(document.getElementsByTagName('a')).forEach(function (key) {
            id = document.getElementsByTagName('a')[key].id;
            if (id.indexOf('link') !== -1) {
                q.seteventinlink(document.getElementsByTagName('a')[key], q);
            }
        });
    };

    this.seteventinlink = function (obj, q) {
        obj.addEventListener('click', function (e) {
            e.preventDefault();
            q.getRegFields(document.getElementsByName(this.id)[0].value);
        });
    };

    this.getRegFields = function (webinar_id) {
        var q = this;
        etutorium_send(
            'getRegfields',
            {
                webinar_id: webinar_id,
                apikey: apikey
            },
            function (data) {
                if (data.error !== '') {
                    var translate = M.util.get_string(data.error, 'etutorium');
                    if (translate !== '[[' + data.error + ',etutorium]]') {
                        alert(translate);
                    } else {
                        alert(data.error);
                    }
                } else {
                    q.fieldlist = data.result.fieldlist;
                    q.requiredfieldlist = data.result.requiredfieldlist;
                    q.count = data.result.count;

                    if (q.count === 0) {
                        q.webinarConnect(webinar_id);
                    } else {
                        document.getElementById('regfields').style.display = 'block';
                        document.getElementById('regfields').innerHTML = data.result.table;
                    }
                }
            }
        );
    };

    this.webinarConnect = function (webinarid) {
        var data = {
            webinarid: webinarid,
            id: etutorium
        };
        var check = true;
        Object.keys(requiredfieldlist).forEach(function (key) {
            if (document.getElementById(fieldlist[key]).value.trim() === '') {
                check = false;
            }
        });
        if (!check) {
            document.getElementById('regfielderror').innerHTML = M.util.get_string('fullingfields', 'etutorium');
        } else {
            Object.keys(fieldlist).forEach(function (key) {
                data[fieldlist[key]] = document.getElementById(fieldlist[key]).value;
            });
            etutorium_send(
                'webinarConnect',
                data,
                function (responsedata) {
                    if (responsedata.error !== '') {
                        alert(responsedata.error);
                    } else {
                        document.location.href = responsedata.result.authurl;
                    }
                }
            );
        }
    };
}
