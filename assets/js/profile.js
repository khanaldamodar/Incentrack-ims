document.getElementById('editBtn').addEventListener('click', function() {
    // Remove readonly attribute and class from all inputs except username
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        input.readOnly = false;
        input.classList.remove('readonly-mode');
    });

    // Show save button and hide edit button
    this.style.display = 'none';
    document.getElementById('saveBtn').style.display = 'inline-block';
});

document.getElementById('photo-upload').addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.querySelector('.profile-image img').src = e.target.result;
        };
        reader.readAsDataURL(e.target.files[0]);
        
        // Automatically submit the form when a file is selected
        this.closest('form').submit();
    }
});