var actions_array = Array();
function one_kepy_back(){
    try{
        var funkce = actions_array.pop();
        var delka = actions_array.length;
        console.log(delka);
        funkce();
    }
    catch (e){
        ;
    }
}

function kepy_refresh(){
    try{
        var funkce = actions_array[actions_array.length-1];
        var delka = actions_array.length;
        console.log(delka);
        funkce();
    }
    catch (e){
        ;
    } 
}

function login_fce(){ 
    try{               
        var element = document.getElementById('login-data');
        var request = new XMLHttpRequest();              
        var data = JSON.stringify({"login": element.login.value, "password": element.password.value});
        var url = apiURL+"/app/login.php";
        request.open("POST", url, true);
        request.setRequestHeader("Content-Type", "application/json");        
        request.onreadystatechange = function () {
            if (request.readyState === 4 && request.status === 200) {
                console.log(request.responseText);
                var received_data = JSON.parse(request.responseText);
                var role = received_data.role
                document.cookie = "logged="+true+";path=/";
                var login = document.getElementById("login");
                switch(role){
                    case "a" :
                        login.innerHTML = "";
                        document.getElementById("")
                        show_admin(received_data.login);
                        fill_header(received_data.login,"Administrátor");
                    break;
                    case "m" :
                        login.innerHTML = "";
                        show_moderator();
                        fill_header(received_data.login,"Moderátor");
                    break;
                    case "r" :
                        login.innerHTML = "";
                        show_registered();                        
                        fill_header(received_data.login,"Běžný uživatel");
                    break;
                    case "not_user" :                       
                        alert("Invalid login");
                        return;
                    case "deleted_user":
                        alert("Váš účet byl smazán");
                        return;
                    case "w_pwd" :                        
                        alert("Invalid password");
                        return;
                    break;
                }
            }
        }
        request.send(data);
    }     
    catch (e){
        alert(e.toString());
    }
    return false; // to avoid default form submit behavior 
}

function logout(){
    document.cookie = "PHPSESSID=;path=/";
    document.cookie = "logged=notlogged;path=/";    
    document.getElementById("header").innerHTML = "";
    document.getElementById("content").innerHTML = "";
    document.getElementById("navigation").innerHTML = "";
    document.getElementById("login").innerHTML ="";
    loadHTML("login","login.html");
}

function require_user(){
    var session = readCookie("PHPSESSID");
    var logged = readCookie("logged");
    //user not logged    
    if (session == null || session == 0 || logged == "notlogged"){
        console.log("this is the problem")
        document.getElementById("login").innerHTML ="";
        loadHTML("login","login.html");
        document.getElementById("header").innerHTML = "";
        document.getElementById("content").innerHTML = "";
        return;
    }
    else{
        try{
            var request = new XMLHttpRequest();              
            var data = JSON.stringify({"which_user":"i_am_asking!"});
            var url = apiURL+"/app/which_user.php";
            request.open("POST", url, true);
            request.setRequestHeader("Content-Type", "application/json");        
            request.onreadystatechange = function () {                
                if (request.readyState === 4 && request.status === 200) {
                    var role;
                    var login;
                    console.log(request.responseText);
                    var received_data = JSON.parse(request.responseText);
                    role = received_data.role;
                    login = received_data.login;
                    console.log(login);
                    switch(role){
                        case "a" :
                            show_admin();
                            fill_header(login,"Administrátor");                                
                        break;
                        case "m":
                            show_moderator();
                            fill_header(received_data.login,"Moderátor");
                        break;
                        case "r":
                            login.innerHTML = "";
                            show_registered();
                            fill_header(received_data.login,"Běžný uživatel");
                        break;
                        case null:
                            logout();
                        break;
                    }
                }
            }
            request.send(data);
        }
        catch(e){
            alert(e.toString());
        }
        return;
    }
    return;
}

function fill_header(login,role){
    document.getElementById("header_login").innerHTML = login;
    document.getElementById("header_role").innerHTML = role;
}

function show_admin(){        
    document.getElementById("navigation").innerHTML ="";
    loadHTML("navigation","admin/admin_navigation.html");
    document.getElementById("header").innerHTML ="";
    loadHTML("header","header.html",false);
}

function show_moderator(){
    document.getElementById("navigation").innerHTML ="";
    loadHTML("navigation","moderator/moderator_navigation.html");
    document.getElementById("header").innerHTML ="";
    loadHTML("header","header.html",false);
}

function show_registered(){
    document.getElementById("navigation").innerHTML ="";
    loadHTML("navigation","registered/registered_navigation.html");
    document.getElementById("header").innerHTML ="";
    loadHTML("header","header.html",false);
}