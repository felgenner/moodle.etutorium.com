function ConnectJs(apikey, etutorium) {
    this.apikey = apikey;
    this.etutorium = etutorium;
    this.fieldlist = [];
    this.requiredfieldlist = [];
    this.count = 0;

    this.setevent = function() {
        q = this;
        for (key in document.getElementsByTagName('a')) {
            id = document.getElementsByTagName('a')[key].id;
            if (id.indexOf('link') != -1) {
                document.getElementsByTagName('a')[key].addEventListener('click', function(e){
                    e.preventDefault();
                    q.getRegFields(document.getElementsByName(this.id)[0].value);
                });
            }
        }
    };

    this.getRegFields = function(webinar_id) {
        q = this;
        send(
            '/mod/etutorium/getRegfields.php',
            {
                webinar_id:webinar_id,
                apikey:this.apikey
            },
            function (data) {
                if (data.error != '') {
                    translate = M.util.get_string(data.error, 'etutorium');
                    if (translate != '[[' + data.error + ',etutorium]]') {
                        alert(translate);
                    } else {
                        alert(data.error);
                    }
                } else {
                    q.fieldlist = data.result.fieldlist;
                    q.requiredfieldlist = data.result.requiredfieldlist;
                    q.count = data.result.count;

                    if (q.count == 0) {
                        q.webinarConnect(webinar_id);
                    } else {
                        document.getElementById('regfields').style.display = 'block';
                        document.getElementById('regfields').innerHTML = data.result.table;
                    }
                }
            }
        );
    };

    this.webinarConnect = function(webinarid) {
        data = {
            webinarid: webinarid,
            id: this.etutorium
        };
        check = true;
        for (key in this.requiredfieldlist) {
            if (document.getElementById(this.fieldlist[key]).value.trim() == '') {
                check = false;
            }
        }
        if (!check) {
            document.getElementById('regfielderror').innerHTML = M.util.get_string('fullingfields', 'etutorium');
        } else {
            for (key in this.fieldlist) {
                data[this.fieldlist[key]] = document.getElementById(this.fieldlist[key]).value;
            }
            send(
                '/mod/etutorium/webinarConnect.php',
                data,
                function (data) {
                    if (data.error != '') {
                        alert(data.error);
                    } else {
                        document.location.href = data.result.authurl;
                    }
                }
            );
        }
    };

    this.returnerror = function(error){
    };
}
