 <!-- Notification script starts -->
 <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
 <!-- <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script> -->
 <script>
let notification_ids = "[";
// Enable pusher logging - don't include this in production
//Pusher.logToConsole = true;


var pusher = new Pusher('3a9b85e8ad0fb87a0a56', {
    cluster: 'mt1',
    encrypted: true, // Use encrypted connection (recommended)
    reconnection: true, // Enable automatic reconnection
    reconnectionAttempts: 5, // Max number of reconnection attempts
    reconnectionDelay: 1000, // Time in ms before each retry attempt
    reconnectTimeout: 5000, // Timeout before retrying reconnection
    authEndpoint: '/tps-smis/broadcasting/auth',  // ✅ Laravel default
    auth: {
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'  // ✅ Required if using web guard
        }
    }
});

//var userId = {{ auth()->user()->id }};
var channel = pusher.subscribe(`private-notifications.all`);
var companyChannel = pusher.subscribe(`private-notifications.company`);

function handleNotification(data) {
    console.log("Incoming notification:", data);

    const notification = data?.data;
    if (!notification || !data.id) return;

    // Update notification count
    const countLabel = document.querySelector('.count-label');
    if (countLabel) {
        countLabel.style.background = 'red';
        const currentValue = parseInt(countLabel.textContent, 10) || 0;
        countLabel.textContent = currentValue + 1;
    }

    // Generate notification URL using a placeholder pattern
    var encodedId = encodeURIComponent(data.id);
    var urlTemplate = `{{ route('notifications.showNotifications', ['notificationIds' => '__ID__']) }}`;
    var url = urlTemplate.replace('__ID__', encodedId);

    // Append notification to dropdown
    const notificationsContainer = document.querySelector('.dropdown-menu .mx-3.d-flex.flex-column');
    if (notificationsContainer) {
        const notificationItem = document.createElement('div');
        notificationItem.className = 'notification-item';
        notificationItem.innerHTML = `
            <div class="bg-${notification.type}-subtle border border-${notification.type} px-3 py-2 rounded-1"> 
                <a href="${url}" class="dropdown-item text-${notification.type} d-flex align-items-center">
                    ${notification.title}
                </a>
                <p class="small m-0">${notification.created_at}</p>
            </div>
        `;
        notificationsContainer.prepend(notificationItem);
    }
}

// Bind event to both channels
channel.bind('notification', handleNotification);
companyChannel.bind('notification', handleNotification);

// Monitor Pusher connection
pusher.connection.bind('state_change', function(states) {
    console.log('Pusher state changed:', states.previous, '→', states.current);

    if (states.current === 'disconnected') {
        // Reconnect manually if desired
        pusher.connect();
    }
});

// Handle errors
pusher.connection.bind('error', function(err) {
    console.error('Pusher connection error:', err);
});

 </script>



 <div class="d-sm-flex d-none">
     <div class="dropdown">
         <a class="dropdown-toggle d-flex p-3 position-relative" href="#!" role="button" data-bs-toggle="dropdown"
             aria-expanded="false">
             <i class="bi bi-bell fs-4 lh-1 text-primary"></i>
             <span class="count-label">0</span>
         </a>
         <div class="dropdown-menu dropdown-menu-end dropdown-menu-sm">
             <h5 class="fw-semibold px-3 py-2 text-primary">Notifications</h5>
             <div class="scroll250">
                 <div class="mx-3 d-flex gap-2 flex-column">
                     <!-- <div class="bg-danger-subtle border border-danger px-3 py-2 rounded-1">
                                    <p class="m-0 text-danger">New product purchased</p>
                                    <p class="small m-0">Just now</p>
                                </div> -->

                 </div>
             </div>
             <div class="d-grid m-3">
                 <a id="viewAll" href="javascript:void(0)" class="btn btn-primary">View all</a>
             </div>
         </div>
     </div>

 </div>