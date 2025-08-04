import '../styles/registration.css';

// Add active class on menu link leading to registration page
document.addEventListener("DOMContentLoaded", () => {
    const registrationLink = document.getElementsByClassName("registration");
    registrationLink[0].classList.add('active');
});