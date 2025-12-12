
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('clear-all-notifications').addEventListener('click', function() {
        fetch('/notifications/clear-all', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove notifications from UI or reload notifications
                document.querySelector('.notification-list').innerHTML = '';
            } else {
                alert('Failed to clear notifications.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});