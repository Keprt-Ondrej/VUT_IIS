var apiURL = "http://www.stud.fit.vutbr.cz/~xfabom01/xfabom01";

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') {
            c = c.substring(1,c.length);
        }
        if (c.indexOf(nameEQ) == 0) {
            return c.substring(nameEQ.length,c.length);
        }
    }
    return null;
}

function loadHTML(destination,file_name,sync=true){
    var xhttp;
    //console.log(destination);
    var element = document.getElementById(destination);
    let file = file_name;
    if (file){
        xhttp = new XMLHttpRequest();
        xhttp.open("GET",`${file}`,sync);
        xhttp.onreadystatechange = function(){
            if(this.readyState == 4){
                if(this.status == 200){                    
                    element.innerHTML += `${this.responseText}`;
                }
                if(this.status == 404){element.innerHTML = "Not found"}
            }
        }        
        xhttp.send();
        return;
    }
}   

function test_cookie(){
    var session = readCookie("PHPSESSID");
    console.log(session);
}