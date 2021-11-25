function user_list(){
    var content = document.getElementById("content");
    content.innerHTML += "i was here";

}

function add_user_content(){
    loadHTML("content","admin/add_user.html");
}

function add_user(){
    var form = document.getElementById("add_user_form");
    var login = form.user_login.value;
    var pwd = form.user_password.value;
    if(login == "" || pwd == ""){
        alert("Zadejte údaje o uživateli");
        return;
    }
    var send_data = JSON.stringify({"login":login, "pwd":pwd,"role":form.user_role.value});
    console.log(send_data);
    try{
        var request = new XMLHttpRequest();
        var url = apiURL+"/app/insert_user.php";
        request.open("POST", url, true);
        request.setRequestHeader("Content-Type", "application/json");        
        request.onreadystatechange = function () {                
            if (request.readyState === 4 && request.status === 200) {
                console.log(request.responseText);
                var received_data = JSON.parse(request.responseText);
                if (received_data.status == "ok"){
                    var form = document.getElementById("add_user_form");
                    form.user_login.value = "";
                    form.user_password.value = "";
                }
                else{
                    alert("Uživatel již existuje!");
                }
            }
        }
        request.send(send_data);
    }
    catch(e){
        alert(e.toString());
    }
    return;
}