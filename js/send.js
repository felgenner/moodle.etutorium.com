function getXmlHttp(){
    var xmlhttp;
    try {
        xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
        } catch (E) {
            xmlhttp = false;
        }
    }
    if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}

function send(url, data, func){
    var xhr = getXmlHttp();
    bodyarray = [];
    for(key in data){
        bodyarray.push(key + '=' + encodeURIComponent(data[key]));
    }
    body = bodyarray.join('&');
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (this.readyState != 4) {
            return;
        }
        response = JSON.parse(this.responseText);
        func(response);
    };
    xhr.send(body);
}
