$( document ).ready(function() {
    var socket = io.connect('http://localhost:3000');
    socket.on('notif', function (data) {
        $.toast({
		    heading: data.name,
		    text: data.message,
		    showHideTransition: 'slide',
		    icon: 'info',
		    stack: 4,
		    position: 'top-right',
		    hideAfter: 5000   // in milli seconds
		});
    });
});