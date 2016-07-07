$(document).ready(function () {
    // When the user is not there
    if (window.LOCKSCREEN_TIMEOUT != undefined) {
        $(document).idleTimer(600000);
        $(document).on("idle.idleTimer", function (event, elem, obj) {
            window.location.href = '/lockscreen?uri=' + window.location.pathname;
        });
    }
});