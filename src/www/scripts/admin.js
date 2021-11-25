function user_list(){
    var content = document.getElementById("content");
    content.innerHTML += "i was here";

}

function add_user_content(){
    loadHTML("content","admin/add_user.html");
}

function add_user(){
    var form = document.getElementById("add_user_form");
    var send_data = JSON.stringify({"login":form.user_login.value, "pwd":form.user_password.value,"role":form.user_role.value});
    console.log(send_data);
    try{
        var request = new XMLHttpRequest();
        var url = apiURL+"/app/add_user.php";
        request.open("POST", url, true);
        request.setRequestHeader("Content-Type", "application/json");        
        request.onreadystatechange = function () {                
            if (request.readyState === 4 && request.status === 200) {
                console.log(request.responseText);
                var received_data = JSON.parse(request.responseText);
                //TODO
                
                //if pridano, vymaz form
                var form = document.getElementById("add_user_form");
                form.user_login.value = "";
                form.user_password.value = "";
            }
        }
        request.send(send_data);
    }
    catch(e){
        alert(e.toString());
    }
    return;
}