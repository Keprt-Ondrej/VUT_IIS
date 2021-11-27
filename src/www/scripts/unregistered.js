function show_all_users_points(){
    try{
        var request = new XMLHttpRequest();
        var url = apiURL+"/app/list_points.php";
        var send_data = JSON.stringify({});
        request.open("POST", url, true);
        request.setRequestHeader("Content-Type", "application/json");        
        request.onreadystatechange = function () {                
            if (request.readyState === 4 && request.status === 200) {
                console.log(request.responseText);
                var destination = document.getElementById("content");
                destination.innerHTML = `<div>
                <input type="text" id="myInput" class="myInput" onkeyup="myFunction()" placeholder="Search for names.."></div>
                <table id="myTable" class="myTable">
                <tr class="header"><th>Subject_ID</th><th>Login</th><th>Points</th><th></th></tr>`
                var table = document.getElementById("myTable");
                var received_data = JSON.parse(request.responseText);
                received_data["list_points"].forEach(element => {                    
                    table.innerHTML += `<tr><td>${element.subject_ID}</td><td>${element.login}</td><td>${element.points}</  td></tr>`
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