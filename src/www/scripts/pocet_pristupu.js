function pocet_pristupu(){ 
    try{             
        var xhr = new XMLHttpRequest();    
        ///var session = readCookie('ID_session');
        var data = JSON.stringify({"session": "randomak"});
        console.log("odeslano:" +data); 
        var url = apiURL+"/app/pocet_pristupu.php";
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/json");        
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log(xhr.responseText);
            }
        }
        xhr.send(data);
    }     
    catch (e){
        alert(e.toString());
    }
    return false; // to avoid default form submit behavior 
}