//Function To Display Popup

function popup_show() {
document.getElementById('back-black').style.display = "block";
document.getElementById('pop-container').style.display = "block";
$('body').css('overflow','hidden');
}
//Function to Hide Popup
function popup_hide(){
document.getElementById('back-black').style.display = "none";
document.getElementById('pop-container').style.display = "none";
$('body').css('overflow','visible');
}

//Function To Display Popup 2nd
function popup_show_two() {
document.getElementById('back-black').style.display = "block";
document.getElementById('pop-container-two').style.display = "block";
$('body').css('overflow','hidden');
}
//Function to Hide Popup 2nd
function popup_hide_two(){
document.getElementById('back-black').style.display = "none";
document.getElementById('pop-container-two').style.display = "none";
$('body').css('overflow','visible');
}

//Function To Display Popup 2nd
function popup_show_main() {
document.getElementById('back-black').style.display = "block";
document.getElementById('pop-container-main').style.display = "block";
$('body').css('overflow','hidden');
}
//Function to Hide Popup 2nd
function popup_hide_main(){
document.getElementById('back-black').style.display = "none";
document.getElementById('pop-container-main').style.display = "none";
$('body').css('overflow','visible');
}

window.onclick = function(event) {
    if (event.target == document.getElementById('pop-container')) {
        document.getElementById('pop-container').style.display = "none";
        document.getElementById('back-black').style.display = "none";
        $('body').css('overflow','visible');
    }else if (event.target == document.getElementById('pop-container-two')) {
        document.getElementById('pop-container-two').style.display = "none";
        document.getElementById('back-black').style.display = "none";
        $('body').css('overflow','visible');
    }else if (event.target == document.getElementById('edit-pop-container')) {
        document.getElementById('edit-pop-container').style.display = "none";
        document.getElementById('back-black').style.display = "none";
        $('body').css('overflow','visible');
    }else if (event.target == document.getElementById('edit-pop-container-two')){
    	document.getElementById('edit-pop-container-two').style.display = "none";
        document.getElementById('back-black').style.display = "none";
        $('body').css('overflow','visible');
    }
}

//FOR EDIT WITH VALUES PARAMETER POPUP
function edit_popup_show() {
document.getElementById('back-black').style.display = "block";
document.getElementById('edit-pop-container').style.display = "block";
$('body').css('overflow','hidden');
}
//Function to Hide Popup
function edit_popup_hide(){
document.getElementById('back-black').style.display = "none";
document.getElementById('edit-pop-container').style.display = "none";
$('body').css('overflow','visible');
}

//FOR EDIT WITH VALUES PARAMETER POPUP PART 2
function edit_popup_show_two() {
document.getElementById('back-black').style.display = "block";
document.getElementById('edit-pop-container-two').style.display = "block";
$('body').css('overflow','hidden');
}
//Function to Hide Popup
function edit_popup_hide_two(){
document.getElementById('back-black').style.display = "none";
document.getElementById('edit-pop-container-two').style.display = "none";
$('body').css('overflow','visible');
}


//FOR EDIT WITH VALUES PARAMETER POPUP PART 3
function edit_popup_show_three() {
document.getElementById('back-black').style.display = "block";
document.getElementById('edit-pop-container-three').style.display = "block";
$('body').css('overflow','hidden');
}
//Function to Hide Popup
function edit_popup_hide_three(){
document.getElementById('back-black').style.display = "none";
document.getElementById('edit-pop-container-three').style.display = "none";
$('body').css('overflow','visible');
}


//FOR EDIT WITH VALUES PARAMETER POPUP PART 2
function edit_popup_show_two_approved() {
document.getElementById('back-black-approved').style.display = "block";
document.getElementById('edit-pop-container-two-approved').style.display = "block";
$('body').css('overflow','hidden');
}
//Function to Hide Popup
function edit_popup_hide_two_approved(){
document.getElementById('back-black-approved').style.display = "none";
document.getElementById('edit-pop-container-two-approved').style.display = "none";
$('body').css('overflow','visible');
}