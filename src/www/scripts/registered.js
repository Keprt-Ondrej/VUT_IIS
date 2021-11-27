function subjects_content(){
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
                content.innerHTML = "";
                if(received_data.status == "ok"){
                    content.innerHTML += `<h1>${received_data.subject_name}</h1>`;
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