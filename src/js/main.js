function showEditForm(aptID){
    document.getElementById('edit_apt_form' + aptID).style.display = 'block';
    document.getElementById('background_popup').style.display = 'block';
}

function showDelForm(aptID){
    document.getElementById('delete_apt_form' + aptID).style.display = 'block';
    document.getElementById('background_popup').style.display = 'block';
}

function showBookingForm(){
    document.getElementById('booking_form').style.display = 'block';
    document.getElementById('background_popup').style.display = 'block';
}

function showAddAptForm(){
    document.getElementById('add_apt_form').style.display = 'block';
    document.getElementById('background_popup').style.display = 'block';
}

function DeletePost(postID){
    var deletePostForm = document.getElementById('delete_post_form' + postID);

    if (deletePostForm) {
        deletePostForm.style.display = 'block';
    }
}

function closePopup() {
    document.getElementById('background_popup').style.display = 'none';
    hideAllPopupForms();
}

function hideAllPopupForms() {
    var forms = document.querySelectorAll('.popup-form');
    forms.forEach(function(form) {
        form.style.display = 'none';
    });
}

var AddPost_Form = document.getElementById("AddPost_Form");

function showAddPost(){
    background_popup.style.display = "block";
    AddPost_Form.style.display = "block";
}

function closeAddPost(){
    background_popup.style.display = "none";
    AddPost_Form.style.display = "none";
}

function ToFooter(){
    var body = document.body, html = document.documentElement;
    var page_height = Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight);
    window.scrollTo(0, page_height-window.innerHeight);
}

function notyet(){
    alert("This Feature is not available yet. Try again later.");
}