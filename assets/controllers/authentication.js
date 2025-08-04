import '../styles/authentication.css';

// Add active class on menu link leading to login page
document.addEventListener("DOMContentLoaded", () => {
    const loginLink = document.getElementsByClassName("login");
    loginLink[0].classList.add('active');
});