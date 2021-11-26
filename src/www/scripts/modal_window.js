var form = document.getElementById('add-form');
var modal = document.getElementById('modal');

// open modal button
var openModal = document.getElementById('open-modal');

// close modal button
var closeModal = document.getElementById('close-modal');

// First we reset the form to default
form.reset();

// Form validation on submit
form.onsubmit = function(e){
    e.preventDefault();
    form.reset();
    // remove error messages
    modal.style.display = 'none';
    console.log("zavreno");
}

// show modal on click
openModal.onclick = function() {
    modal.style.display = 'block';
}

// hide modal, remove error messages (if there are any) and reset form when the X was clicked
closeModal.onclick = function() {
    form.reset();
    modal.style.display = 'none';
    modal.style.display = 'none';
}