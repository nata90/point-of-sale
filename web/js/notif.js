$( document ).ready(function() {
    var socket = io.connect('http://192.168.20.15:3000');
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