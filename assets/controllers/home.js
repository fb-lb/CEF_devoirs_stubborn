import '../styles/home.css';
import '../styles/partials/products/_card_top.css';

// Highlight home menu link
document.addEventListener("DOMContentLoaded", () => {
    const homeLink = document.getElementsByClassName("home");
    homeLink[0].classList.add('active');
});