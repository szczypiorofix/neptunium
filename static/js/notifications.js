export function setNotificationsHandler() {
    // const notificationsToClose = document.getElementsByClassName('snackbar');
    // for(let notification of notificationsToClose) {
    //     console.log(notification);
    //
    //     const notificationClose = notification.getElementsByTagName('button')[0];
    //     notificationClose.addEventListener('click', (event) => {
    //         console.log('clicked!');
    //     });
    //
    // }

    const notificationsContainer = document.getElementsByClassName('notifications-container')[0];
    notificationsContainer.addEventListener('click', (event) => {
       console.log('clicked!');
    });

}
