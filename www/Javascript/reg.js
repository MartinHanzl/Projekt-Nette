var mail = document.getElementsByName("email")[0];
var regMail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
var labelEmail = document.getElementsByClassName("labelEmail")[0];
mail.addEventListener("focusout", function () {
    if(regMail.test(mail.value)) {
        console.log("Email souhlasí");
    } else {
        console.log("Email nesouhlasí");
        mail.value = "";
        labelEmail.innerHTML = "Zadejte prosím platnou emailovou adresu!";
    }
})

var telefon = document.getElementsByName("telefon")[0]
var regTelefon = /^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$/;
var labelTelefon = document.getElementsByClassName("labelTelefon")[0];
telefon.addEventListener("focusout", function () {
    if(regTelefon.test(telefon.value)) {
        console.log("Telefon souhlasí");
    } else {
        console.log("Telefon nesouhlasí");
        telefon.value = "";
        labelTelefon.innerHTML = "Zadejte prosím platné telefonní číslo!";
    }
})