import '../styles/home.css';
import '../styles/partials/products/_card_top.css';

// Add active class on menu link leading to home page
document.addEventListener("DOMContentLoaded", () => {
    const homeLink = document.getElementsByClassName("home");
    homeLink[0].classList.add('active');
});