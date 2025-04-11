// Delete button handler
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', () => {
        const medicineId = button.dataset.id;
        const formData = new FormData();
        formData.append('medicine_id', medicineId);

        fetch('delete_medicine.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                button.closest('.medicine-card').remove();
            } else {
                alert('Failed to delete medicine');
            }
        })
        .catch(error => console.error('Error:', error));
    });
});

// Check reminders every minute
setInterval(() => {
    fetch('check_reminders.php')
        .then(response => response.json())
        .then(data => {
            if (data.reminder) {
                alert(`Time to take ${data.name}, ${data.dosage}`);
            }
        });
}, 60000);