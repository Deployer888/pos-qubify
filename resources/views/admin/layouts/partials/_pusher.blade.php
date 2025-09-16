<script src="https://cdnjs.cloudflare.com/ajax/libs/pusher/7.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@latest/dist/echo.iife.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>

    const echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ config('app_key') }}',
        cluster: '{{ config('app_cluster') }}',
        forceTLS: true
    });

    // Replace with the authenticated user's ID
    const userId = '{{ auth()->id() }}';

    // Initialize Pusher channel for the user
    echo.private('App.Models.User.' + userId)
        .notification((notification) => {
            // console.log('New notification received:', notification);
            toastr.success(notification.title);
            // Add the new notification to the dropdown
            const notificationList = document.querySelector('.second-notification-list');
            const newNotificationHtml = `
                <a href="${notification.url}" data-notification-id="${notification.id}" class="dropdown-item notify-item notification-item active">
                    <div>
                        <h6 class="mb-1">${notification.title}</h6>
                        <div class="font-size-12 text-muted">
                            <p class="mb-1">${notification.message}</p>
                        </div>
                    </div>
                </a>
            `;

            if (notificationList) {
                notificationList.insertAdjacentHTML('afterbegin', newNotificationHtml);
            }

            // Update the unread notification count
            const badge = document.querySelector('.second-notification-count');
            if (badge) {
                let currentCount = parseInt(badge.textContent) || 0;
                badge.textContent = currentCount + 1; // Increment the count
            }

            // Update the notification count in the "second-notification-left-count" element
            const secondNotificationCount = document.querySelector('.second-notification-left-count');
            if (secondNotificationCount) {
                let currentCount = parseInt(secondNotificationCount.textContent.match(/\d+/)) || 0; // Extract the number
                const newCount = currentCount + 1;

                // Update the content
                secondNotificationCount.textContent = `{{ __('custom.notification') }} (${newCount})`;
            }
        });
</script>
