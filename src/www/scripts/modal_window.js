var modal = document.getElementById('modal');
// open modal button
var openModal = document.getElementById('open-modal');
// close modal button
var closeModal = document.getElementById('close-modal');

var modal_body = document.getElementById("modal-body");
var modal_header = document.getElementById("modal-header");

// Form validation on submit
function close_modal(){
    modal.style.display = 'none';
    console.log("schovat modal");
}

function open_modal(){
    modal.style.display = 'block';
    console.log("otevrit modal")
}

// hide modal, remove error messages (if there are any) and reset form when the X was clicked
closeModal.onclick = function() {
    modal.style.display = 'none';
}