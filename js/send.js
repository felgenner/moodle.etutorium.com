/* global document: false */
/* global variables: ActiveXObject, XMLHttpRequest, console */
function etutorium_getXmlHttp() {
    'use strict';
    var xmlhttp;
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e1) {
        console.log(e1);
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (e2) {
            console.log(e2);
            xmlhttp = false;
        }
    }
    if (!xmlhttp && XMLHttpRequest !== undefined) {
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}

function etutorium_send(action, data, func) {
    'use strict';
    var xhr = etutorium_getXmlHttp();
    var bodyarray = [];
    data.action = action;
    Object.keys(data).forEach(function (key) {
        bodyarray.push(key + '=' + encodeURIComponent(data[key]));
    });
    var body = bodyarray.join('&');
    xhr.open('POST', 'ajax.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (this.readyState !== 4) {
            return;
        }
        var response = JSON.parse(this.responseText);
        func(response);
    };
    xhr.send(body);
}
