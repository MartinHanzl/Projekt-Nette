//////////F12 disable code////////////////////////
document.onkeypress = function (event) {
    event = (event || window.event);
    if (event.keyCode == 123 || event.keyCode == 17) {
        //alert('No F-12');
        return false;
    } else if ((event.ctrlKey && event.shiftKey && event.keyCode == 73) || (event.ctrlKey && event.shiftKey && event.keyCode == 74) || (event.ctrlKey && event.shiftKey && event.keyCode == 75) || (event.ctrlKey && event.shiftKey && event.keyCode == 67)) {
        return false;
    }
}
document.onmousedown = function (event) {
    event = (event || window.event);
    if (event.keyCode == 123 || event.keyCode == 17) {
        //alert('No F-keys');
        return false;
    } else if ((event.ctrlKey && event.shiftKey && event.keyCode == 73) || (event.ctrlKey && event.shiftKey && event.keyCode == 74) || (event.ctrlKey && event.shiftKey && event.keyCode == 75) || (event.ctrlKey && event.shiftKey && event.keyCode == 67)) {
        return false;
    }
}
document.onkeydown = function (event) {
    event = (event || window.event);
    if (event.keyCode == 123 || event.keyCode == 17) {
        //alert('No F-keys');
        return false;
    } else if ((event.ctrlKey && event.shiftKey && event.keyCode == 73) || (event.ctrlKey && event.shiftKey && event.keyCode == 74) || (event.ctrlKey && event.shiftKey && event.keyCode == 75) || (event.ctrlKey && event.shiftKey && event.keyCode == 67)) {
        return false;
    }
}
/////////////////////end///////////////////////