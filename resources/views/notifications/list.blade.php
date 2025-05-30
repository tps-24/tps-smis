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
});
var channel = pusher.subscribe('notifications');
channel.bind('notification', function(data) {
    //app.messages.push(JSON.stringify(data));
    var notification = data.data;
    if (notification_ids.length == 1) {

        notification_ids = notification_ids.concat(JSON.stringify(data.id));
    } else {
        notification_ids = notification_ids.concat(",", JSON.stringify(data.id));
    }

    //let url_link = notifications.join();
    //alert(url_link)
    // Update notification count
    var countLabel = document.querySelector('.count-label');
    countLabel.style.background = 'red';
    var currentValue = parseInt(countLabel.textContent, 10);
    var newValue = currentValue + 1;
    countLabel.textContent = newValue;

    // Show alert with announcement data
    //alert(`Title: ${announcement.title}\nMessage: ${announcement.message}`);
    var encodedId = encodeURIComponent(data.id);
    url = `{{ route('notifications.showNotifications',['notificationIds'=>'announcement_id']) }}`;
    url = url.replace('announcement_id', [encodedId]);
    // Add notification to the dropdown
    var notificationsContainer = document.querySelector('.dropdown-menu .mx-3.d-flex.flex-column');
    var notificationItem = document.createElement('div');
    notificationItem.className = 'notification-item';
    notificationItem.innerHTML = `
                        <div class="bg-${notification.type}-subtle border border-${notification.type} px-3 py-2 rounded-1"> 
                            <a href="${url}" class="dropdown-item text-${notification.type} d-flex align-items-center">
                                ${notification.title}
                            </a>
                            <p class="small m-0">${notification.created_at}</p>
                        </div>
                        `;
    notificationsContainer.prepend(notificationItem); // Add to the top of the list
});

// pusher.connection.bind('state_change', function (states) {
//     //console.log('State changed from ' + states.previous + ' to ' + states.current);
//     if (states.current === 'connected') {
//         //console.log('Successfully connected to Pusher!');
//     } else if (states.current === 'disconnected') {
//         // console.log('Disconnected. Attempting to reconnect...');
//         pusher.connect();
//     }
// });

pusher.connection.bind('state_change', function(states) {
    console.log('Pusher state changed:', states.previous, '→', states.current);
});

// Handle error events
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