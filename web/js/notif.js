$( document ).ready(function() {
    var socket = io.connect('http://127.0.0.1:3000');
    socket.on('notif', function (data) {
        $.toast({
		    heading: data.name,
		    text: data.message,
		    showHideTransition: 'slide',
		    icon: 'info',
		    stack: 4,
		    position: 'top-right',
		    hideAfter: 10000   // in milli seconds
		});
    });
});