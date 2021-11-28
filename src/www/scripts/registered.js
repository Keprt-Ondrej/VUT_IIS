function subjects_content(){
    document.getElementById("content").innerHTML =`<h1>Seznam předmětů</h1>`;
    loadHTML("content","unregistered/course_list_form.html",false);
    document.getElementById("course_list_button").onclick = list_subjects;
}

function sign_up_as_student(subject_ID){
    try{
        var request = new XMLHttpRequest();
        var url = apiURL+"/app/sign_up_as_student.php";
        request.open("POST", url, true);
        var send_data = JSON.stringify({"subject_ID":subject_ID});
        console.log(send_data);
        request.setRequestHeader("Content-Type", "application/json");
        request.onreadystatechange = function () {                
            if (request.readyState === 4 && request.status === 200) {
                console.log(request.responseText);
                console.log();
                document.getElementById(subject_ID+"_row").innerHTML = `Nerozhodnuto`;                                
            }
        }
        request.send(send_data);
    }
    catch(e){
        alert(e.toString());
    }
    return;    
}

//<a href="#" onclick="subjects_content();">Předměty</a>
function list_subjects(){
    console.log("list_subjects");
    var send_data = course_list_form_JSON();
    console.log(send_data);
    try{
        var request = new XMLHttpRequest();
        var url = apiURL+"/app/list_subjects.php";
        request.open("POST", url, true);
        request.setRequestHeader("Content-Type", "application/json");        
        request.onreadystatechange = function () {                
            if (request.readyState === 4 && request.status === 200) {
                console.log(request.responseText);
                var received_data = JSON.parse(request.responseText);
                var destination = document.getElementById("content2");
                destination.innerHTML = `<div>
                <input type="text" class="myInput" id="myInput" onkeyup="myFunction()" placeholder="Search for names.."></div>
                <table class="myTable" id="myTable">
                <tr class="header"><th>Zkratka</th><th>Jméno předmětu</th><th>Vyučující</th><th>Stav</th><th>Stav předmětu</th></tr>`
                var table = document.getElementById("myTable"); 

                received_data["subjects"].forEach(element => {
                    //console.log(element);
                    var status_subject = approved_converter(element.approved);
                    if(element.role == 0){
                        user_status = `<button type="button" onclick="sign_up_as_student(\'${element.subject_ID}\');">Přihlásit se</button>`;
                    }
                    else{
                        user_status = element.role;
                    }
                    
                    table.innerHTML += `<tr>
                    <td><a href="#" onclick="list_category(\'${element.subject_ID}\');">${element.subject_ID}</a></td>
                    <td><a href="#" onclick="list_category(\'${element.subject_ID}\');">${element.subject_name}</a></td>
                    <td>${element.login}</td>
                    <td id="${element.subject_ID}_row">${user_status}</td> 
                    <td>${status_subject}</td></tr>`;
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

function list_category(subject_ID){
    try{
        var request = new XMLHttpRequest();
        var url = apiURL+"/app/list_categories.php";
        var send_data = JSON.stringify({"subject_ID":subject_ID});
        console.log("chci kategori: "+send_data);
        request.open("POST", url, true);
        request.setRequestHeader("Content-Type", "application/json");        
        request.onreadystatechange = function () {                
            if (request.readyState === 4 && request.status === 200) {
                console.log(request.responseText);
                var content = document.getElementById("content");
                received_data = JSON.parse(request.responseText); 
                if(received_data.status == "ok"){
                    content.innerHTML = "";
                    content.innerHTML += `<h1>${received_data.subject_name}</h1>`;
                    if(received_data.role == true){
                        content.innerHTML += `<a href="#" onclick="manage_students(\'${subject_ID}\');">Správa studentů</a>`;
                    }                    
                    content.innerHTML += `<h3>Kategorie otázek</h3>`;
                    received_data["categories"].forEach(element => { 
                        content.innerHTML+= `<a href="#" onclick="console.log(\'${element.category_ID}\');">${element.brief}</a><br>`
                    });
                }
                else{
                    alert("Chyba");
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

function approve_student(login,subject_ID,value){
    console.log("value: "+value);
    try{
        var request = new XMLHttpRequest();
        var url = apiURL+"/app/approve_students.php";
        var send_data = JSON.stringify({"approved":value,"login":login,"subject_ID":subject_ID});
        console.log(send_data);
        request.open("POST", url, true);
        request.setRequestHeader("Content-Type", "application/json");        
        request.onreadystatechange = function () {                
            if (request.readyState === 4 && request.status === 200) {
                console.log(request.responseText);
                var received_data = JSON.parse(request.responseText);
                var row = document.getElementById(`${received_data.login}_row`);                    
                row.innerHTML = approved_converter(received_data.approved);          
            }
        }
        request.send(send_data);
    }
    catch(e){
        alert(e.toString());
    }
    return; 
}

function manage_students(subject_ID){
    console.log(subject_ID);
    try{
        var request = new XMLHttpRequest();
        var url = apiURL+"/app/list_students_to_subjects.php";
        var send_data = JSON.stringify({"subject_ID":subject_ID});
        console.log("chci kategori: "+send_data);
        request.open("POST", url, true);
        request.setRequestHeader("Content-Type", "application/json");        
        request.onreadystatechange = function () {                
            if (request.readyState === 4 && request.status === 200) {
                console.log(request.responseText);
                var content = document.getElementById("content");
                received_data = JSON.parse(request.responseText);
                if(received_data.status == "ok"){
                    content.innerHTML = "";

                   content.innerHTML += `<div>
                    <input type="text" class="myInput" id="myInput" onkeyup="myFunction()" placeholder="Search for names.."></div>
                    <table class="myTable" id="myTable">
                    <tr class="header"><th>Login</th><th>Stav</th><th></th><th></th></tr>`;
                    var table = document.getElementById("myTable"); 
                    var status = approved_converter(received_data.approved);    
                    received_data["students"].forEach(element => {
                        table.innerHTML += `<tr><td>${element.login}</td><td id="${element.login}_row">${status}</td>
                        <td><button type="button" onclick="approve_student(\'${element.login}\',\'${subject_ID}\',1);">Potvrdit</button>
                        </td><td><button type="button" onclick="approve_student(\'${element.login}\',\'${subject_ID}\',0);">Zamítnout</button></tr>`; 
                    });
                    content.innerHTML += `</table>`;
                    
                }
                else{
                    alert("Chyba");
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