$(document).ready(function(){
	window.print();
	every_time();
});


function every_time() {
    setTimeout(function(){ location.reload(); }, 60 * 1000); //1000 = 1 sec
}