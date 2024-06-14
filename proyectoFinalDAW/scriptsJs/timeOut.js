document.addEventListener("DOMContentLoaded", function() {
    var successMessage = document.querySelector(".success-message");
    if (successMessage) {
        setTimeout(function() {
            successMessage.style.display = "none";
        }, 3000); 
    }
});