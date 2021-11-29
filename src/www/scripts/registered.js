function subjects_content(){
    var content = document.getElementById("content");
    content.innerHTML =`<h1>Seznam předmětů</h1>`;
    loadHTML("content","unregistered/course_list_form.html",false);
    var logged = readCookie("logged");
    console.log("list predmetu: "+logged)
    if(logged != "neregistrovan"){
        document.getElementById("add_subject").innerHTML = `<h3><a href="#" onclick="create_subject_content();">Vytvořit předmět</a></h3>`
    }    
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
                received_data = JSON.parse(request.responseText);
                if (received_data.status == "ok"){
                    document.getElementById(subject_ID+"_row").innerHTML = `Nerozhodnuto`; 
                }
                else if(received_data.status == "not_logged"){
                    alert("Přihlaste se do systému Fitusky!");
                }
                else  alert("Nastala chyba");                                              
            }
        }
        request.send(send_data);
    }
    catch(e){
        alert(e.toString());
    }
    return;    
}

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
                        content.innerHTML += `<h3><a href="#" onclick="manage_students(\'${subject_ID}\');">Správa studentů</a></h3>`;
                        content.innerHTML += `<h4><a href="#" onclick="create_category_content(\'${subject_ID}\');">Vytvořit kategorii</a></h4>`;                        
                    }                    
                    content.innerHTML += `<h3>Kategorie otázek</h3>`;
                    received_data["categories"].forEach(element => { 
                        content.innerHTML+= `<a href="#" onclick="list_questions(\'${element.category_ID}\');">${element.brief}</a><br>`
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
                var content = document.getElementById("modal-body");
                received_data = JSON.parse(request.responseText);
                if(received_data.status == "ok"){
                    document.getElementById("modal-header").innerHTML = `<h1>Správa studentů ${subject_ID}</h1>`
                    content.innerHTML = "";
                    content.innerHTML += `<div>
                    <input type="text" class="myInput" id="myInput" onkeyup="myFunction()" placeholder="Search for names.."></div>
                    <table class="myTable" id="myTable">
                    <tr class="header"><th>Login</th><th>Stav</th><th></th><th></th></tr>`;
                    var table = document.getElementById("myTable");                        
                    received_data["students"].forEach(element => {
                        var status = approved_converter(element.approved); 
                        table.innerHTML += `<tr><td>${element.login}</td><td id="${element.login}_row">${status}</td>
                        <td><button type="button" onclick="approve_student(\'${element.login}\',\'${subject_ID}\',1);">Potvrdit</button>
                        </td><td><button type="button" onclick="approve_student(\'${element.login}\',\'${subject_ID}\',0);">Zamítnout</button></tr>`; 
                    });
                    content.innerHTML += `</table>`;
                    open_modal();                    
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

function create_subject_content(){
    document.getElementById("modal-header").innerHTML = `<h1>Vytvořit předmět</h1>`;
    var modal_content = document.getElementById("modal-body");
    modal_content.innerHTML = `
    <form id="create_subject_form">
    <label for="subject_ID_create">Zkratka předmětu:</label>
    <input type="text" id="subject_ID_create" name="subject_ID_create" maxlenght="5"><br>
    <label for="subject_name_create">Jméno předmětu</label> 
    <input type="text" id="subject_name_create" name"subject_name_create" maxlenght="255"><br>
    <input type="button" onclick="create_subject();" value="Vytvořit">
    </form>
    `;
    open_modal();
}

function create_subject(){
    try{
        var request = new XMLHttpRequest();
        var url = apiURL+"/app/create_course.php";
        var form = document.getElementById("create_subject_form")
        var send_data = JSON.stringify({"subject_ID":form.subject_ID_create.value,"subject_name":form.subject_name_create.value});
        console.log(send_data);
        request.open("POST", url, true);
        request.setRequestHeader("Content-Type", "application/json");        
        request.onreadystatechange = function () {                
            if (request.readyState === 4 && request.status === 200) {
                console.log(request.responseText);
                var received_data = JSON.parse(this.responseText);
                if(received_data.status == "ok"){
                    close_modal();
                }
                else{
                    alert("Předmět se nepodařilo vytvořit!");
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

function create_category_content(subject_ID){
    document.getElementById("modal-header").innerHTML = `<h1>Vytvořit kategorii pro předmět ${subject_ID}</h1>`;
    var modal_content = document.getElementById("modal-body");
    modal_content.innerHTML =`
    <form id="create_category_form">
    <label for="category_name_create">Název kategorie: </label>
    <input type="text" id="category_name_create" name="category_name_create" maxlenght="255"><br>
    <input type="button" onclick="create_category(\'${subject_ID}\');" value="Vytvořit">
    </form>
    `;
    
    open_modal()
}

function create_category(subject_ID){
    try{
        var request = new XMLHttpRequest();
        var url = apiURL+"/app/create_category.php";
        var form = document.getElementById("create_category_form")
        var send_data = JSON.stringify({"subject_ID":subject_ID,"brief":form.category_name_create.value});
        console.log(send_data);
        request.open("POST", url, true);
        request.setRequestHeader("Content-Type", "application/json");        
        request.onreadystatechange = function () {                
            if (request.readyState === 4 && request.status === 200) {
                console.log(request.responseText);
                var received_data = JSON.parse(this.responseText);
                if(received_data.status == "ok"){
                    var content = document.getElementById("content");
                    content.innerHTML += `<a href="#" onclick="list_questions(\'${received_data.category_ID}\');">${form.category_name_create.value}</a><br>`
                    close_modal();
                }
                else{
                    alert("Kategorii se nepodařilo vytvořit!");
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

function list_questions(category_ID){
    try{
        var request = new XMLHttpRequest();
        var url = apiURL+"/app/list_questions.php";
        var send_data = JSON.stringify({"category_ID":category_ID});
        console.log(send_data);
        request.open("POST", url, true);
        request.setRequestHeader("Content-Type", "application/json");        
        request.onreadystatechange = function () {                
            if (request.readyState === 4 && request.status === 200) {
                console.log(request.responseText);
                var received_data = JSON.parse(this.responseText);
                if(received_data.status == "ok"){
                    var content = document.getElementById("content");
                    content.innerHTML = `<h1>${received_data.brief}</h1>`;
                    if(received_data.role != null){ //null = unregistered on course
                        content.innerHTML += `<h3><a href="#" onclick="create_question_content(\'${category_ID}\');">Vytvořit otázku</a></h3>`
                    }
                    content.innerHTML += `<h3>Otázky:</h3>`;
                    received_data["questions"].forEach(element => {
                       content.innerHTML +=` <a href="#" onclick="list_answers_content(\'${element.question_ID}\');">${element.brief}</a><br>`
                    });

                }
                else{
                    alert("Kategorie nebyla nalezena!");
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

function create_question_content(category_ID){
    document.getElementById("modal-header").innerHTML = `<h1>Vytvořit otázku</h1>`;
    document.getElementById("modal-body").innerHTML ="";
    loadHTML("modal-body","registered/create_question_form.html",false);
    document.getElementById("modal-body").innerHTML += `<input type="button" onclick="create_question(\'${category_ID}\');" value="Vytvořit otázku">`;    
    open_modal();
}

function create_question(category_ID){
    try{
        var request = new XMLHttpRequest();
        var url = apiURL+"/app/ask_question.php";
        var form = document.getElementById("create_question_form");
        var send_data = JSON.stringify({"category_ID":category_ID,"brief": form.question_brief.value, "full_question":form.full_question.value});
        console.log(send_data);
        request.open("POST", url, true);
        request.setRequestHeader("Content-Type", "application/json");        
        request.onreadystatechange = function () {                
            if (request.readyState === 4 && request.status === 200) {
                console.log(request.responseText);
                var received_data = JSON.parse(this.responseText);
                if(received_data.status == "ok"){
                    document.getElementById("content").innerHTML +=`<a href="#" onclick="list_answers_content(\'${received_data.question_ID}\');">${form.question_brief.value}</a><br>`
                    close_modal();
                }
                else{
                    alert("Nastala chyba při vytváření otázky");
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

function correct_answer_converter(value){
    if(value == null){
        return `<div style="color: blue;float: left;margin-right: 10px;">nehodnoceno  </div>`;
    }
    else if(value == 1){
        return `<div style="color: green;float: left;margin-right: 10px;">správně  </div>`;
    }
    else return `<div style="color: red;float: left;margin-right: 10px;">špatně  </div>`;
}

function list_answers_content(question_ID){
    console.log("otazka: " +question_ID);
    try{
        var request = new XMLHttpRequest();
        var url = apiURL+"/app/list_answers.php";
        var form = document.getElementById("create_question_form");
        var send_data = JSON.stringify({"question_ID": question_ID});
        console.log(send_data);
        request.open("POST", url, true);
        request.setRequestHeader("Content-Type", "application/json");        
        request.onreadystatechange = function () {                
            if (request.readyState === 4 && request.status === 200) {
                console.log(request.responseText);
                var received_data = JSON.parse(this.responseText);
                if(received_data.status == "ok"){
                    var content = document.getElementById("content");
                    content.innerHTML = "";
                    content.innerHTML += `<h3>Otázka: ${received_data.brief}</h3>`;
                    content.innerHTML += `<p>${received_data.full_question}</p>`;
                    if (received_data.answer != null){  //final answer is defined
                        content.innerHTML +=`<h3>Správná odpověd:</h3>
                        <p>${received_data.answer}</p>`;
                    }
                    else{
                        if(received_data.role == true){                            
                            //ucitel
                            content.innerHTML += `<h3><a href="#" onclick="write_answer_content(\'${question_ID}\',true);">Zadat finální odpověď</a></h3>`;
                        }
                        else if (received_data.role == false){
                            content.innerHTML += `<h3><a href="#" onclick="write_answer_content(\'${question_ID}\',false);">Odpovědět na otázku</a></h3>`;
                        }
                    }
                    content.innerHTML += `<h3>Odpovědi</h3>`;
                    received_data["answers"].forEach(element => {
                        if(received_data.answer == null){
                            login = "";
                        }
                        else{
                            login = `${element.login}:<br>`
                        }
                        var actions = return_answer_actions_area(received_data.answer,received_data.role,element.login,question_ID,element.points,element.correct);
                        content.innerHTML += `<div class="answer"><div style="padding-bottom: 2px">${actions}</div>${login}${element.answer}<br></div>`
                    });
                }
                else{
                    alert("Otázka nebyla nalezena");
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

function return_answer_actions_area(final_answer,role,login,question_ID,votes,correct){
    if(final_answer == null){   //final answer is not set
        if(role == true){   //teacher
            return `počet hlasů: ${votes}<br>`;
        }
        else if(role == false){     //student
            //vote + reactions
            return `
            <input type="button" onclick="list_reactions_content(\'${question_ID}\',\'${login}\');" value="Zobrazit reakce">
            <input type="button" onclick="add_rating(\'${question_ID}\',\'${login}\');" value="Přidat hlas">`;
        }
        else{
            return `<input type="button" onclick="list_reactions_content(\'${question_ID}\',\'${login}\');" value="Zobrazit reakce">`;
        }
    }
    else{
        if(role == true){   //teacher
            //evaluate            
            return `${correct_answer_converter(correct)}<label>Body: </label><input type="text" id="${login}_points"size="1" value="${votes}"> Správně: <input type="checkbox" id="${login}_correct"><input type="button" onclick="evaluate_answer(\'${question_ID}\',\'${login}\',\'${votes}\');" value="Vyhodnotit">`;
        }
        else if(role == false){     //student   list_reactions_content(question_ID,login)
            //reactions
            return `${correct_answer_converter(correct)} Hlasovalo: ${votes} <input type="button" onclick="list_reactions_content(\'${question_ID}\',\'${login}\');" value="Zobrazit reakce">`; 
        }
        else{
            return `${correct_answer_converter(correct)} Hlasovalo: ${votes} <input type="button" onclick="list_reactions_content(\'${question_ID}\',\'${login}\');" value="Zobrazit reakce">`;
        }
    }
    return ``;
}

function write_answer_content(question_ID,role){    
    var body = document.getElementById("modal-body");
    body.innerHTML = "";
    loadHTML("modal-body","registered/create_answer_form.html",false);
    if(role){   //teacher
        document.getElementById("modal-header").innerHTML = "<h1>Správná odpověď<h1>";
        body.innerHTML += `<button type="button" onclick="final_answer(\'${question_ID}\');">Uložit</button>`;
    }
    else{ //student
        document.getElementById("modal-header").innerHTML = "<h1>Odpověď<h1>";
        body.innerHTML += `<button type="button" onclick="write_answer(\'${question_ID}\');">Uložit</button>`;
    }
    open_modal();
}

function final_answer(question_ID){
    console.log("otazka: " +question_ID);
    try{
        var request = new XMLHttpRequest();
        var url = apiURL+"/app/final_answer.php";
        var form = document.getElementById("answer_form");
        var send_data = JSON.stringify({"question_ID": question_ID,"answer":form.answer.value});
        console.log(send_data);
        request.open("POST", url, true);
        request.setRequestHeader("Content-Type", "application/json");        
        request.onreadystatechange = function () {                
            if (request.readyState === 4 && request.status === 200) {
                console.log(request.responseText);
                var received_data = JSON.parse(this.responseText);
                if(received_data.status == "ok"){
                   close_modal();
                }
                else{
                    alert("Nelze uložit finální odpověď");
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

function write_answer(question_ID){
    console.log("otazka: " +question_ID);
    try{
        var request = new XMLHttpRequest();
        var url = apiURL+"/app/write_answer.php";
        var form = document.getElementById("answer_form");
        var send_data = JSON.stringify({"question_ID": question_ID,"answer":form.answer.value});
        console.log(send_data);
        request.open("POST", url, true);
        request.setRequestHeader("Content-Type", "application/json");        
        request.onreadystatechange = function () {                
            if (request.readyState === 4 && request.status === 200) {
                console.log(request.responseText);
                var received_data = JSON.parse(this.responseText);
                if(received_data.status == "ok"){
                   close_modal();
                }
                else{
                    alert("Nelze uložit odpověď");
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

function evaluate_answer(question_ID,login,votes){
    var correct = document.getElementById(login+"_correct").checked;
    var points = document.getElementById(login+"_points").value;
    if(isNaN(points)){
        alert("Body musí být číslo");
        return;
    }
    if(!correct){
        points = 0;
    }
    if(points < 0){
        alert("Body musí být kladné");
        return;
    }
    try{
        var request = new XMLHttpRequest();
        var url = apiURL+"/app/mark_answers.php";
        var send_data = JSON.stringify({"login":login,"question_ID": question_ID,"correct":correct,"points":points});
        console.log(send_data);
        request.open("POST", url, true);
        request.setRequestHeader("Content-Type", "application/json");        
        request.onreadystatechange = function () {                
            if (request.readyState === 4 && request.status === 200) {
                console.log(request.responseText);
                var received_data = JSON.parse(this.responseText);
                if(received_data.status == "ok"){
                    alert("prijato");
                }
                else{
                    alert("Nelze zobrazit reakce");
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

function list_reactions_content(question_ID,login){    
    console.log("otazka: " +question_ID);
    try{
        var request = new XMLHttpRequest();
        var url = apiURL+"/app/list_reactions.php";
        var send_data = JSON.stringify({"question_ID": question_ID,"login":login});
        console.log(send_data);
        request.open("POST", url, true);
        request.setRequestHeader("Content-Type", "application/json");        
        request.onreadystatechange = function () {                
            if (request.readyState === 4 && request.status === 200) {
                console.log(request.responseText);
                var received_data = JSON.parse(this.responseText);
                if(received_data.status == "ok"){
                    var content = document.getElementById("content");
                    content.innerHTML = "<h3>Odpověď:</h3>";                
                    content.innerHTML += `${received_data.answer}`;
                    if(received_data.role != null){
                        content.innerHTML += `<h3><a href="#" onclick="create_reaction_content(\'${question_ID}\',\'${login}\');">Vytvořit reakci</a></h3>`;
                    }
                    content.innerHTML += `<h3>Reakce:</h3>`;                    
                    received_data["reactions"].reverse().forEach(element => {
                        content.innerHTML += `<div class="answer">autor:${element.reaction_login}<br>${element.text}</div`;
                    });

                }
                else{
                    alert("Nelze zobrazit reakce");
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

function add_rating(question_ID,answer_login){
    console.log("otazka: " +question_ID);
    try{
        var request = new XMLHttpRequest();
        var url = apiURL+"/app/vote.php";
        var send_data = JSON.stringify({"question_ID": question_ID,"answer_login":answer_login});
        console.log(send_data);
        request.open("POST", url, true);
        request.setRequestHeader("Content-Type", "application/json");        
        request.onreadystatechange = function () {                
            if (request.readyState === 4 && request.status === 200) {
                console.log(request.responseText);
                var received_data = JSON.parse(this.responseText);
                if(received_data.status == "ok"){
                    close_modal();
                }
                else{
                    alert("Nelze zobrazit reakce");
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

function create_reaction_content(question_ID,login){
    document.getElementById("modal-header").innerHTML = "Tvroba reakce";
    document.getElementById("modal-body").innerHTML = `
    <label for="reaction_value">Zadejte reakci na odpověď:</label><br>
    <textarea id="reaction_value" name="reaction_value" placeholder="Odpověď"></textarea><br>
    <button type="button" onclick="create_reaction(\'${question_ID}\',\'${login}\');">Odeslat</button>
    `;
    open_modal();
}

function create_reaction(question_ID,login){
    try{
        var request = new XMLHttpRequest();
        var url = apiURL+"/app/react.php";
        var send_data = JSON.stringify({"question_ID": question_ID,"answer_login":login,"reaction":document.getElementById("reaction_value").value});
        console.log(send_data);
        request.open("POST", url, true);
        request.setRequestHeader("Content-Type", "application/json");        
        request.onreadystatechange = function () {                
            if (request.readyState === 4 && request.status === 200) {
                console.log(request.responseText);
                var received_data = JSON.parse(this.responseText);
                if(received_data.status == "ok"){
                    console.log("zadano");
                    close_modal();
                }
                else{
                    alert("Nelze zobrazit reakce");
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