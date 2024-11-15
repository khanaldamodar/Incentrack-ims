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