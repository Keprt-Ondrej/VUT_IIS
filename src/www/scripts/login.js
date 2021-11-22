function login_fce(){ 
    try{               
        var element = document.getElementById('login-data');
        var xhr = new XMLHttpRequest();              
        var data = JSON.stringify({"login": element.login.value, "password": element.password.value});
        console.log("odeslano:" +data); 
        var url = "http://www.stud.fit.vutbr.cz/~xfabom01/xfabom01/app/login.php";
        //var url = "http://itu.uranus-portal.com/userlogin";
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/json");        
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var element = document.getElementById('err_msg');
                element.text = "";
                console.log(xhr.responseText);
                var received_data = JSON.parse(xhr.responseText);               
                var user = received_data.user;

                switch(user){
                    case "admin" :
                        window.location.href = "admin/index.php";
                        break;
                    case "teacher" :
                        window.location.href = "teacher/index.php";
                        break;
                    case "student" :
                        window.location.href = "student/index.php";
                        break;
                    case "not_user" :
                        var element = document.getElementById('err_msg');
                        element.text = "user not found";  
                        break;
                    case "w_pwd" :
                        var element = document.getElementById('err_msg');
                        element.text = "wrong password or username";  
                        break;
                }
             
            }
        }
        xhr.send(data);
    }     
    catch (e){
        alert(e.toString());
    }
    return false; // to avoid default form submit behavior 
}