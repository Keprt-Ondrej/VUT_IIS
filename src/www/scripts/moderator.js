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
            }
        }
        request.send(send_data);
    }
    catch(e){
        alert(e.toString());
    }
    return;    
}