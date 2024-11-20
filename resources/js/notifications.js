// public/js/notifications.js
$(document).ready(function () {
    function fetchNotifications() {
        $.ajax({
            url: "{{ route('pengajuan_izin.notifications') }}", // Ganti dengan rute notifikasi Anda
            method: 'GET',
            success: function(data) {
                if (data.unreadPermissionRequestsCount > 0) {
                    $('.permission-badge')
                        .text(data.unreadPermissionRequestsCount)
                        .show();
                } else {
                    $('.permission-badge').hide();
                }
            },
            error: function() {
                console.log('Error fetching notifications.');
            }
        });
    }

    // Panggil fungsi fetchNotifications setiap 5 detik
    setInterval(fetchNotifications, 5000);
});
