import '../styles/cart.css';

// Add active class on menu link leading to cart page
document.addEventListener("DOMContentLoaded", () => {
    const cartLink = document.getElementsByClassName("cart");
    cartLink[0].classList.add('active');
});