import '../styles/registration.css';

// Highlight registration menu link
document.addEventListener("DOMContentLoaded", () => {
    const registrationLink = document.getElementsByClassName("registration");
    registrationLink[0].classList.add('active');
});