document.getElementById('notificationDropdown').addEventListener('click', function() {
    // Mark notifications as read when the bell icon is clicked
    fetch('mark_notifications_read.php', {
        method: 'POST',
    }).then(response => {
        if (response.ok) {
            document.getElementById('notificationCount').innerText = '0'; // Reset the notification count
        }
    });
});
