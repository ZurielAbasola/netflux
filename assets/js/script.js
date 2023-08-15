function volumeToggle(button) {
    var muted = $(".previewVideo").prop("muted");
    $(".previewVideo").prop("muted", !muted);

    $(button).find("i").toggleClass("fa-volume-xmark");
    $(button).find("i").toggleClass("fa-volume-high");
}

function previewEnded() {
    $(".previewVideo").toggle();
    $(".previewImage").toggle();
}

function goBack() {
    window.history.back();
}

function startHideTimer() {
    var timeout = null;

    $(document).on("mousemove", function() {
        clearTimeout(timeout);
        $(".watchNav").fadeIn();

        timeout = setTimeout(function() {
            $(".watchNav").fadeOut();
        }, 2000);
    })
}

function initVideo(videoId, userLoggedIn) {
    startHideTimer();
    updateProgressTimer(videoId, userLoggedIn);
}

function updateProgressTimer(videoId, userLoggedIn) {
    addDuration(videoId, userLoggedIn);

    var timer;

    $("video").on("playing", function(event) {
        window.clearInterval(timer);
        timer = window.setInterval(function() {
            updateProgress(videoId, userLoggedIn, event.target.currentTime);
        }, 3000);
    }).on("ended", function() {
        setFinished(videoId, userLoggedIn);
        window.clearInterval(timer);
    })
}

function addDuration(videoId, userLoggedIn) {
    $.post("ajax/addDuration.php", { videoId: videoId, username: userLoggedIn }, function(data) {
        if (data !== null && data !== "") {
            alert(data);
        }
    })
}

function updateProgress(videoId, userLoggedIn, progress) {
    $.post("ajax/updateDuration.php", { videoId: videoId, username: userLoggedIn, progress: progress }, function(data) {
        if (data !== null && data !== "") {
            alert(data);
        }
    })
}

function setFinished(videoId, userLoggedIn) {
    $.post("ajax/setFinished.php", { videoId: videoId, username: userLoggedIn }, function(data) {
        if (data !== null && data !== "") {
            alert(data);
        }
    })
}