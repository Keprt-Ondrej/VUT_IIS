function login_fce(){ 
    try{               
        var element = document.getElementById('login-data');
        var xhr = new XMLHttpRequest();              
        var data = JSON.stringify({"login": element.login.value, "password": element.password.value});
        console.log("odeslano:" +data); 
        var url = "http://www.stud.fit.vutbr.cz/~xfabom01/app/login.php";
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/json");        
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var received_data = JSON.parse(xhr.responseText);
                console.log(received_data);
                var user = received_data.user;
                if(!user.localeCompare("admin")){
                    window.location.href = "admin/index.php";
                }
                else if(!user.localeCompare("teacher")){
                    window.location.href = "teacher/index.php";
                }
                else if(!user.localeCompare("student")){
                    window.location.href = "student/index.php";
                }                
                else{
                    alert("Špatné jméno nebo heslo!");
                }
            }
        }; 
        xhr.send(data);
    }     
    catch (e){
        alert(e.toString());
    }
    return false; // to avoid default form submit behavior 
}