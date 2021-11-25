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
                //TODO spatne heslo a jmeno, neposila se v role ale v USER!
                switch(role){
                    case "a" :
                        login.innerHTML = "";
                        show_admin(received_data.login);
                        fill_header(received_data.login,"administrátor");
                    break;
                    case "t" :
                        window.location.href = "teacher/index.php";
                        login.innerHTML = "";
                    break;
                    case "s" :
                        window.location.href = "student/index.php";
                        login.innerHTML = "";
                    break;
                    case "not_user" :                       
                        alert("Invalid login");
                    break;
                    case "w_pwd" :                        
                        alert("Invalid password");
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

function header_info(login,role){

}

function logout(){
    document.cookie = "PHPSESSID=;path=/";
    document.cookie = "logged=notlogged;path=/";    
    document.getElementById("header").innerHTML = "";
    document.getElementById("content").innerHTML = "";
    document.getElementById("navigation").innerHTML = "";
    loadHTML("login","login.html");
}


function require_user(){
    var session = readCookie("PHPSESSID");
    var logged = readCookie("logged");
    //user not logged    
    if (session == null || session == 0 || logged == "notlogged"){
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
                                fill_header(login,"administrátor");                                
                            break;
                        case "t" :
                            window.location.href = "teacher/index.php";
                            break;
                        case "s" :
                            window.location.href = "student/index.php";
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
    loadHTML("header","header.html",false);    
    loadHTML("navigation","admin/admin_navigation.html");
}