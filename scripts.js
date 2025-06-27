document.addEventListener("DOMContentLoaded", function() {
    const showSignupLink = document.getElementById("show-signup");
    const showLoginLink = document.getElementById("show-login");
    const loginForm = document.getElementById("login-form");
    const signupForm = document.getElementById("signup-form");
    const joinUsBtn = document.getElementById("join-us-btn");
    const loginNavBtn = document.getElementById("login-btn");

    showSignupLink.addEventListener("click",function (event) {
        event.preventDefault();
        loginForm.style.display = "none";
        signupForm.style.display = "block";
        
    });

    showLoginLink.addEventListener("click",function(event){
        event.preventDefault();
        signupForm.style.display = "none";
        loginForm.style.display = "block";
    });

    joinUsBtn.addEventListener("click", function(event){
        event.preventDefault();
        loginForm.style.display = "block";
        signupForm.style.display = "none";
    })

     // Add event listener for the "LOGIN" nav button
     loginNavBtn.addEventListener("click", function(event) {
        event.preventDefault();
        loginForm.style.display = "block"; // Show login form
        signupForm.style.display = "none"; // Hide signup form if it's open
    });

});


