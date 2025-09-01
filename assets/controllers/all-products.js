import '../styles/all-products.css';
import '../styles/partials/products/_card_shop.css';

// Highlight shop menu link
document.addEventListener("DOMContentLoaded", () => {
    const shopLink = document.getElementsByClassName("shop");
    shopLink[0].classList.add('active');
});

// Filter sweats according to price filter selection on all products page
document.addEventListener('DOMContentLoaded', () => {
    const buttons = Array.from(document.getElementsByClassName('price-filter__button'));
    const sweats = Array.from(document.getElementsByClassName('card-shop'));

    buttons.forEach(button => {
        button.addEventListener('click', (event) => {
            sweats.forEach(sweat => {
                if (sweat.classList.contains('hidden')) {
                    sweat.classList.remove('hidden');
                }
            })

            if (event.target.classList.contains('focus')) {
                event.target.classList.remove('focus');
            } else {
                buttons.forEach(button => {
                    if (button.classList.contains('focus')) {
                        button.classList.remove('focus');
                    }
                });
                event.target.classList.add('focus');

                // Display sweats according to price filter selection
                const minPrice = Number(event.target.dataset.min);
                const maxPrice = Number(event.target.dataset.max);
                sweats.forEach(sweat => {
                    let sweatPrice = Number(sweat.dataset.price);
                    if (minPrice > sweatPrice || maxPrice < sweatPrice) {
                        sweat.classList.add('hidden');
                    }
                })
            }
        })
    });
});