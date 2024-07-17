jQuery(document).ready(function ($) {
    // Делегируем событие submit на документ
    $(document).on('submit', '.tag-form', function (e) {
        submitForm(e, $(this));  // Передаем текущую форму в функцию
    });

    async function submitForm(e, form) {
        e.preventDefault();
        const messageField = form.find('.message-field');
        const data = new FormData(form[0]);

        try {
            const resp = await fetch(universityData.root_url + "/wp-json/music/v1/manageTag", {
                method: "POST",
                body: data,
                headers: {
                    "X-WP-Nonce": universityData.nonce,
                }
            });

            if (!resp.ok) {
                const json = await resp.json();
                throw new Error(json.message);
            } else {
                messageField.text("tag created");
                messageField.removeClass("error-message").addClass("success-message");
            }
        } catch (error) {
            messageField.text(error.message);
            messageField.removeClass("success-message").addClass("error-message");
        }
    }
    // Делегируем событие click на документ
    $(document).on('click', 'a.ajax-link', function (e) {
        e.preventDefault();

        const url = $(this).attr('href');

        // Подгрузка контента
        $.ajax({
            url: url,
            success: function (data) {
                const tempElement = $('<div>').html(data);
                const contentData = tempElement.find('#content-container').html();
                $('#content-container').html(contentData);
            }
        });
    });

    $(document).on('click', '.song-link', function (e) {
        const videoUrl = $(this).data('video-url');

        if (videoUrl) {
            $('#youtube-video').attr('src', videoUrl);
        }
    })

    $(document).on('click', '.song-play', function () {
        const songEmbed = $(this).find('.song-embed');
        songEmbed.appendTo('#video-container');

    });


    // Функция инициализации плеера
    
});


function onYouTubeIframeAPIReady() {
    jQuery(document).ready(function ($) {

    $('.song-play').on('click', function () {
        const dataLink = $(this).data('song-link').replace(' ,', '')

        const urlObj = new URL(dataLink);
        var videoId = urlObj.searchParams.get("v"); 

        var player;

        player = new YT.Player('player', {
            height: '390',
            width: '640',
            videoId: videoId, // Замените VIDEO_ID на ID вашего видео на YouTube
            events: {
                'onReady': onPlayerReady
            }
        });

        function onPlayerReady(event) {
                player.playVideo();
        }});
    })
}