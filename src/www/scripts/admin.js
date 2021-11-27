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
                var destination = document.getElementById("content");
                destination.innerHTML = `
                <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names..">
                <table id="myTable">
                <tr class="header"><th>Login</th><th>Role</th><th></th><th></th></tr>`
                var table = document.getElementById("myTable");
                received_data["users"].forEach(element => {                    
                    table.innerHTML += `<tr><td>`+ element.login + `</td><td>` + stringify_role(element.role) + `</td><td>button chng pwd</td><td>delete user button</td></tr>`;
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