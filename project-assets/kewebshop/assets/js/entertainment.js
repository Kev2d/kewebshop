if ($('.js-quiz').length) {
    const request = new XMLHttpRequest();
    request.open('GET', 'https://chesspuzzle.net/Daily/Api', true);
    request.onload = function () {
        if (request.status >= 200 && request.status < 400) {
            const result = JSON.parse(request.responseText);
            let text;
            if (result.Text == 'White to win') {
                text = 'Valge võit';
            } else {
                text = 'Musta võit';
            }
            document.getElementById("puzzleText").textContent = text;
            document.getElementById("puzzleImage").src = result.Image;
        }
    };
    request.send();

    $(document).ready(getContainerHeight);
    $(window).on('resize', getContainerHeight);

    function getContainerHeight() {
        const vidHeight = $('.js-video-container').outerHeight();
        $('.js-quiz').css('height', vidHeight + 'px');
    }
}