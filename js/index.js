var usernameLogin = document.getElementById("loginUsername");
var passwordLogin = document.getElementById("loginPassword");
// --------------------------------------------------- \\
var usernameRegister = document.getElementById("registerUsername");
var passwordRegister = document.getElementById("registerPassword");
var emailRegister = document.getElementById("registerEmail");
// --------------------------------------------------- \\
var hashArray = document.getElementsByClassName("hash-animation");
// --------------------------------------------------- \\
var mainMenu = document.getElementById('mainMenu');
var postMenu = document.getElementById('postMenu');
var hashtagsMenu = document.getElementById('hashtagsMenu');
var profileMenu = document.getElementById('main-profile');
var navigatorMenu = document.getElementById('navigatorMenu');
var deleteMenu = document.getElementById('delete-ros-func');

deleteRosID = 0;

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function waitAndExit() {
    sleep(2000);
    exitAcc();
}

function girisYap() {
    if (usernameLogin.value && passwordLogin.value) {
        document.getElementById("loginUsername").value = "";
        document.getElementById("loginPassword").value = "";
        window.location.pathname = '../anasayfa/mainpage.html'
    } else {
        alert("Lütfen gerekli alanları doldurun.");
    }
}

function kayitOl() {
    if (usernameRegister.value && passwordRegister.value && emailRegister.value) {
        document.getElementById("registerUsername").value = "";
        document.getElementById("registerEmail").value = "";
        document.getElementById("registerPassword").value = "";
    } else {
        alert("Lütfen gerekli alanları doldurun.");
        console.log("gerekli alanlari doldurun.")
    }
}

let changePassword = new Function("goTo('changepass.php')");
let newUser = new Function("goTo('register.php')");
let sifremiUnuttum = new Function("alert('Sifre sifirlama linki mail hesabiniza gonderildi.')");


function loginAgain() {
    var link = "../giris/index.php";
    window.location = link;
}

function exitAcc() {
    goTo('exit.php');
}

function mainPageAcc() {
    goTo("mainpage.php");
}

function viewProfile(id) {
    url = 'userprofile.php?id=' + id;
    goTo(url);
}

function viewComments(id) {
    if (document.getElementById(id).style.display == 'block') {
        document.getElementById(id).style.display = "none";
    } else {
        document.getElementById(id).style.display = "block";
    }
}

function viewLikes(id) {
    liker_id = 'likers_' + id;
    likers = document.getElementById(liker_id);
    likers.style.display = "block";
    likers.style.zIndex = "50";
}

function hideLikes(id) {
    liker_id = 'likers_' + id;
    likers = document.getElementById(liker_id);
    likers.style.display = "none";
    likers.style.zIndex = "-50";
}


function post() {
    mainMenu.style.opacity = 0.05;
    postMenu.style.display = 'block';
    postMenu.style.zIndex = 8;
    hashtagsMenu.style.opacity = 0.05;
    hashtagsMenu.style.zIndex = -2;
    navigatorMenu.style.opacity = 0.05;
    navigatorMenu.style.zIndex = -2;
}

function unPost() {
    mainMenu.style.opacity = 1;
    postMenu.style.display = 'none';
    postMenu.style.zIndex = -2;
    hashtagsMenu.style.opacity = 1;
    hashtagsMenu.style.zIndex = 20;
    navigatorMenu.style.opacity = 1;
    navigatorMenu.style.zIndex = 20;
}

function goTo(url) {
    window.location = url;
}

function deleteRos(rosID) {
    deleteRosID = rosID;
    deleteMenu.style.display = 'block';
    deleteMenu.style.zIndex = 8;
    mainMenu.style.opacity = 0.05;
    mainMenu.style.zIndex = -2;
    hashtagsMenu.style.opacity = 0.05;
    hashtagsMenu.style.zIndex = -2;
}

function delete_yes() {
    url = 'delete.php?ros=' + deleteRosID;
    goTo(url);
}

function delete_no() {
    deleteMenu.style.display = 'none';
    deleteMenu.style.zIndex = -5;
    mainMenu.style.opacity = 1;
    mainMenu.style.zIndex = 5;
    hashtagsMenu.style.opacity = 1;
    hashtagsMenu.style.zIndex = 5;
}