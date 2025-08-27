import '../styles/cart.css';

document.addEventListener("DOMContentLoaded", () => {
    // Add active class on menu link leading to cart page
    const cartLink = document.getElementsByClassName("cart");
    cartLink[0].classList.add('active');

    // Mount stripe's card element
    const publicKeyElement = document.getElementById('stripe-public-key');
    const publicKey = publicKeyElement.dataset.publicKey;
    const stripe = Stripe(publicKey);
    const elements = stripe.elements();
    const cardElement = elements.create("card");
    cardElement.mount("#card-element");
    
    // Toggle payment modal
    const toggleModal = document.querySelectorAll('.modal-toggle');
    const paymentModal = document.getElementById('payment-modal');
    toggleModal.forEach(element => {
        element.addEventListener('click', async () => {
            paymentModal.classList.toggle('hidden-modal');
        })
    });
    
    // Manage stripe payment
    const paymentForm = document.getElementById('payment-form');
    const submitPaymentButton = document.getElementById('sumbit-payment');
    const userNameElement = document.getElementById('userName');
    const userName = userNameElement.dataset.name;
    paymentForm.addEventListener('submit', async (e) => {
        
        e.preventDefault();

        submitPaymentButton.disabled = true;
        submitPaymentButton.textContent = "Paiement en cours...";

        try {
            const response = await fetch('/cart-payment');
            const { clientSecret } = await response.json();
            const { error, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: userName
                    } 
                }
            })

            if (error) {
                alert(error.message);
                submitPaymentButton.disabled = false;
                submitPaymentButton.textContent = "Payer";
                return;
            }

            if (paymentIntent && paymentIntent.status == "succeeded") {
                const response = await fetch('/cart-payment-confirmed');
                const { success } = await response.json();
                if (success) {
                    window.location.href = '/cart';
                }
            }
        } catch (error) {
            alert("Erreur inattendue : " + error.message);
            submitPaymentButton.disabled = false;
            submitPaymentButton.textContent = "Payer";
        }
    });
});