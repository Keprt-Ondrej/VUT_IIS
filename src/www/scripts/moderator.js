function course_management_content(){
    loadHTML("content","unregistered/course_list_form.html",false);
    document.getElementById("course_list_button").onclick = course_management_list_courses;
}

//return JSON
function course_list_form_JSON(){   
    var unapproved = document.getElementById('unapproved').checked;
    var approved = document.getElementById('approved').checked;
    var undecided = document.getElementById('undecided').checked;
    return JSON.stringify({"unapproved":unapproved,"approved":approved,"undecided":undecided})  
}

function approved_converter(value){
    console.log("approved va: "+value);
    if(value == 1){
        return "potvrzené";
    }
    else if(value === null){
        return "nerozhodnuto"
    }
    else{
        return "nepotvrzené";
    }
    return;   
}

function approve_course(login,subject_ID,value){
    console.log("value: "+value);
    try{
        var request = new XMLHttpRequest();
        var url = apiURL+"/app/approve_subject.php";
        var send_data = JSON.stringify({"approved":value,"login":login,"subject_ID":subject_ID});
        console.log(send_data);
        request.open("POST", url, true);
        request.setRequestHeader("Content-Type", "application/json");        
        request.onreadystatechange = function () {                
            if (request.readyState === 4 && request.status === 200) {
                console.log(request.responseText);
                var received_data = JSON.parse(request.responseText);
                var row = document.getElementById(`${received_data.login}_${received_data.subject_ID}_row`);                    
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


function course_management_list_courses(){
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
                <tr class="header"><th>Zkratka</th><th>Jméno předmětu</th><th>Vyučující</th><th>Stav</th><th></th><th></th></tr>`
                var table = document.getElementById("myTable");
                received_data["subjects"].forEach(element => {
                    console.log(element);
                    var status = approved_converter(element.approved);                                                           
                    table.innerHTML += `<tr><td>${element.subject_ID}</td><td>${element.subject_name}</td><td>${element.login}</td>
                    <td id="${element.login}_${element.subject_ID}_row">${status}</td>
                    <td><button type="button" onclick="approve_course(\'${element.login}\',\'${element.subject_ID}\',1);">Potvrdit</button>
                    </td><td><button type="button" onclick="approve_course(\'${element.login}\',\'${element.subject_ID}\',0);">Zamítnout</button></tr>`;
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