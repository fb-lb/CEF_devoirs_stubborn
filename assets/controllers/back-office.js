import '../styles/back-office.css';

// Highlight back-office menu link
document.addEventListener("DOMContentLoaded", () => {
    const adminLink = document.getElementsByClassName("admin");
    adminLink[0].classList.add('active');
});

// Display sweat image if one is selected in all pages's forms
document.addEventListener('change', (event) => {
    if (event.target && event.target.id.includes('sweat-file-')) {
        const id = event.target.dataset.id;
        const preview = document.getElementById('file-preview-' + id);
        const placeholder = document.getElementById('file-placeholder-' + id);
        const file = event.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            }
            reader.readAsDataURL(file);
        } else {
            preview.src = '#';
            preview.style.display ='none';
            placeholder.style.display = 'block';
        }
    }
});