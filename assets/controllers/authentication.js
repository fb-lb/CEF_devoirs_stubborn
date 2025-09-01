import '../styles/authentication.css';

// Highlight login menu link
document.addEventListener("DOMContentLoaded", () => {
    const loginLink = document.getElementsByClassName("login");
    loginLink[0].classList.add('active');
});