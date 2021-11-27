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
                var received_data = JSON.parse(request.responseText);
                var destination = document.getElementById("content");
                destination.innerHTML = `<div>
                <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names.."></div>
                <table id="myTable">
                <tr class="header"><th>Subject_ID</th><th>Login</th><th>Points</th><th></th></tr>`
                var table = document.getElementById("myTable");
                received_data["list_points"].forEach(element => {                    
                    table.innerHTML += `<tr id="${element.subject_ID}_row"><td>${element.login}</td><td>`+ element.points
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