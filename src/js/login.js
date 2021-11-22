function login_fce(){
    try{
        var element = document.getElementById('login-data');
        var xhr = new XMLHttpRequest();
        var data = JSON.stringify({"login": element.login.value, "password": element.password.value});
        console.log("odeslano:" +data); 
        var url = apiURL+"/api/login.php";
        xhr.open("POST", url, true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log(xhr.responseText);
                var received_data = JSON.parse(xhr.responseText);
                var status = received_data.status
                switch(status){
                    case "not_user" :
                        alert("Invalid login");
                        break;
                    case "w_pwd" :
                        alert("Invalid password");
                        break;
                    default:
                        window.location.href = "index.php";
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