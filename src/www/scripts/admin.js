function add_user_content(){
    document.getElementById("content").innerHTML ="";
    loadHTML("content","admin/add_user.html");
}

function add_user(){
    var form = document.getElementById("add_user_form");
    var login = form.user_login.value;
    var pwd1 = form.user_password1.value;
    var pwd2 = form.user_password2.value;
    if(login == "" || pwd1 == ""){
        alert("Zadejte údaje o uživateli");
        return;
    }
    if(pwd1 != pwd2){
        alert("Hesla se neshodují!");
        return;
    }
    var send_data = JSON.stringify({"login":login, "pwd":pwd1,"role":form.user_role.value});
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
                    form.user_password1.value = "";
                    form.user_password2.value = "";
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

function stringify_role(role){
    switch(role){
        case "a":
            return "administrátor";
        case "m":
            return "moderátor";
        case "r": 
            return "uživatel";
    }
}

function show_all_users_content(){
    try{
        var request = new XMLHttpRequest();
        var url = apiURL+"/app/list_users.php";
        var send_data = JSON.stringify({});
        request.open("POST", url, true);
        request.setRequestHeader("Content-Type", "application/json");        
        request.onreadystatechange = function () {                
            if (request.readyState === 4 && request.status === 200) {
                console.log(request.responseText);
                var received_data = JSON.parse(request.responseText);
                document.getElementById("content").innerHTML= `<h1>Seznam uživatelů</h1>`;
                var destination = document.getElementById("content");                
                destination.innerHTML += `<div>
                <input type="text" class="myInput" id="myInput" onkeyup="myFunction()" placeholder="Search for names.."></div>
                <table class="myTable" id="myTable">
                <tr class="header"><th>Login</th><th>Role</th><th></th><th></th></tr>`
                var table = document.getElementById("myTable");
                received_data["users"].forEach(element => {

                    table.innerHTML += `<tr id="${element.login}_row"><td>${element.login}</td><td>` + stringify_role(element.role) + `
                    </td><td><button type="button" onclick="prepare_change_user_password(\'${element.login}\');">Změna hesla</button>
                    </td><td><button type="button" onclick="prepare_change_user_role(\'${element.login}\');">Změna role</button>
                    </td><td><button type="button" onclick="prepare_delete_user(\'${element.login}\');">Smazat</button></tr>`;
                });
                destination.innerHTML += "</table>";
            }
        }
        request.send(send_data);
    }
    catch(e){
        alert(e.toString());
    }
    return;
}

function prepare_delete_user(login){
    modal_header.innerHTML = "<h1>Opravdu chcete smazat uživatele " + login +"?<h1>"
    modal_body.innerHTML = `<button type="button" onclick="delete_user(\'${login}\');">Smazat</button>`
    open_modal();    
}

function prepare_change_user_role(login){
    document.getElementById("modal-header").innerHTML = `<h1>Změna role uživatele ${login}</h1>`;
    var body = document.getElementById("modal-body");
    body.innerHTML =`
    <label for="user_role_new">Nová role:</label>        
    <select id="user_role_new" name="user_role_new">
        <option value="r">Uživatel</option>
        <option value="a">Administrátor</option>
        <option value="m">Moderátor</option>        
    </select><br>
    <input type="button" onclick="change_user_role(\'${login}\');" value="Změnit">
    `;
    open_modal();
}

function change_user_role(login){
    var role = document.getElementById("user_role_new");
    try{        
        var request = new XMLHttpRequest();
        var url = apiURL+"/app/change_pwd.php";
        var send_data = JSON.stringify({"login":login,"role":role.value});
        console.log(send_data);
        request.open("POST", url, true);
        request.setRequestHeader("Content-Type", "application/json");      
        request.onreadystatechange = function (){
            if (request.readyState === 4 && request.status === 200) {
                console.log(request.responseText);
                var received_data = JSON.parse(request.responseText);
                if (received_data.status == "ok"){                
                    close_modal();
                }
                else{
                    alert("Špatný login!");
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

function delete_user(login){
    try{
        console.log("chci mazat "+login);
        var request = new XMLHttpRequest();
        var url = apiURL+"/app/delete_user.php";
        var send_data = JSON.stringify({"login":login});
        console.log("poslany data na api delete_user.php: "+send_data);
        request.open("POST", url, true);
        request.setRequestHeader("Content-Type", "application/json"); 
        function ResponsFunc(request,login){
            if (request.readyState === 4 && request.status === 200) {
                console.log(request.responseText);
                var received_data = JSON.parse(request.responseText);
                if (received_data.status == "ok"){
                    console.log("radek: \n"+login+"_row");
                    document.getElementById(login+"_row").outerHTML = "";                
                    close_modal();
                }
                else{
                    alert("Špatný login!");
                }
            }
        }       
        request.onreadystatechange = function (){ResponsFunc(request,login)}
        request.send(send_data);
    }
    catch(e){
        alert(e.toString());
    }
    return;
}

function prepare_change_user_password(login){
    modal_header.innerHTML = "<h1>Změna hesla uživatele " + login +"<h1>";
    modal_body.innerHTML = `
    <form id="change_password_form">    
    <label for="user_password1">Nové heslo: </label>
    <input type="password" id="user_password1" name="user_password1" placeholder="heslo"><br>
    <label for="user_password2">Zopakujte heslo: </label>
    <input type="password" id="user_password2" name="user_password2" placeholder="heslo"><br>
    <input type="button" onclick="change_user_password(\'${login}\');" value="Změnit">
    <select id="user_role" name="user_role">
        <option value="r">Uživatel</option>
        <option value="a">Administrátor</option>
        <option value="m">Moderátor</option>        
    </select><br>
    </form>
    `
    open_modal();
}

function change_user_password(login){
    var form = document.getElementById("change_password_form");
    var pwd1 = form.user_password1.value;
    var pwd2 = form.user_password2.value;
    if (pwd1 != pwd2){
        alert("Hesla nejsou stejná");
        return;
    }
    if(pwd1 == ""){
        alert("zadejte heslo!");
        return;
    }

    try{        
        var request = new XMLHttpRequest();
        var url = apiURL+"/app/change_pwd.php";
        var send_data = JSON.stringify({"login":login,"pwd":pwd1});
        console.log(send_data);
        request.open("POST", url, true);
        request.setRequestHeader("Content-Type", "application/json");      
        request.onreadystatechange = function (){
            if (request.readyState === 4 && request.status === 200) {
                console.log(request.responseText);
                var received_data = JSON.parse(request.responseText);
                if (received_data.status == "ok"){                
                    close_modal();
                }
                else{
                    alert("Špatný login!");
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

//*********************************************** */
function myFunction() {
    // Declare variables
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("myTable");
    tr = table.getElementsByTagName("tr");
  
    // Loop through all table rows, and hide those who don't match the search query
    for (i = 0; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td")[0];
      if (td) {
        txtValue = td.textContent || td.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
          tr[i].style.display = "";
        } else {
          tr[i].style.display = "none";
        }
      }
    }
  }
//***************************************************/



