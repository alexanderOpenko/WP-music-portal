jQuery(document).ready(function ($) {
    if (!$.data(document, 'eventsHandlerAttached')) {
        eventsRegistration()
    }

    function eventsRegistration() {
        $(document).on('submit', '.tag-form', function (e) {
            submitTagForm(e, $(this));  
        });

        $(document).on('submit', '.song-form', function (e) {
            submitSongForm(e, $(this)); 
        });

        $(document).on('click', 'a.ajax-link', function (e) {
            ajaxLinkHandler(e, $(this))
        })

        $(document).on('click', '.upload-song-page-js', function (e) {
            handleUploadedSongs($(this))
        })

        // initSelectizeTags()

        $.data(document, 'eventsHandlerAttached', true);
    }

    //submit song
    async function submitSongForm(e, form) {
        e.preventDefault();
        const messageField = form.find('.message-field');
        const data = new FormData(form[0]);

        const tags = [];

        for (const [key, value] of data.entries()) {
            if (key.startsWith('tags')) {
                tags.push(value);
            }
        }

        data.delete('tags')
        data.append('tags', tags)

        const name = normalizeArtistName(data.get('band'))
        data.set('band', name)

        function normalizeArtistName(name) {
            name = name.trim();
            name = name.toLowerCase();

            const symbolsToRemove = ['@', '$', '&', '#', '!', '*', '(', ')'];
            symbolsToRemove.forEach(symbol => {
                name = name.split(symbol).join('');
            });

            name = name.replace(/[^a-z0-9\s]/g, '');
            
            return name;
        }

        try {
            const resp = await fetch(universityData.root_url + "/wp-json/music/v1/createSong", {
                method: "POST",
                body: data,
                headers: {
                    "X-WP-Nonce": universityData.nonce,
                }
            });
            const json = await resp.json();

            if (!resp.ok) {
                throw new Error(json.message);
            } else {
                messageField.text("Song created");
                messageField.removeClass("error-message").addClass("success-message");
                const container = $(".favsongs-page-list")
                container.prepend(songNode(json.post))
            }
        } catch (error) {
            messageField.text(error.message);
            messageField.removeClass("success-message").addClass("error-message");
        }
    }

    //submit tag
    async function submitTagForm(e, form) {
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
    function ajaxLinkHandler(e, target) {
        e.preventDefault();

        const url = target.attr('href');

        // Подгрузка контента
        $.ajax({
            url: url,
            success: function (data) {
                const tempElement = $('<div>').html(data);
                const contentData = tempElement.find('#content-container').html();
                $('#content-container').html(contentData);
                history.pushState(null, '', url);
            }
        });
    }

    async function handleUploadedSongs(target) {
        alert('handleUploadedSongs')
        const $pageData = parseInt(target.attr('data-page'));
        target.attr('data-page', $pageData + 1);

        try {
            const resp = await fetch(universityData.root_url + `/wp-json/music/v1/songsPaginations?page=${$pageData}`, {
                method: "GET",
                headers: {
                    "X-WP-Nonce": universityData.nonce,
                }
            });

            const json = await resp.json();
            console.log(resp.ok);

            if (!resp.ok) {
                console.log('here');
                throw new Error(json.message);
            } else {
                const container = $('.favsongs-page-list')

                json.posts.forEach(post => {
                    container.append(songNode(post))
                }
                )
            }
        } catch (error) {
            console.log(error, 'error');
            alert('something went wrong')
            // messageField.text(error.message);
            // messageField.removeClass("success-message").addClass("error-message");
        }
    }

    function songNode(post) {
        return `<div class="song-item relative flex py-3 px-4 items-center border-0 border-b border-solid border-slate-300">
                        <button class="song-play chartlist-play-button" data-song-link="${post.song_link.replace(/[, ]/g, '')}">
                        </button>
                        <h3 class="m-0 !ml-5">
                            <a href="${post.link}">${post.title}</a>
                        </h3>
                    </div>`
    }

    const $selector = $('#new-song-tags')
    if ($selector.length) {
        const defaultOptions = $selector.data('default-tags')
        const selectizeInstance = $selector.selectize({
            closeAfterSelect: true,
            loadThrottle: 300,
            load: async function (query, callback) {
                if (query && !defaultOptions.some(el => el.text.startsWith(query))) {
                    try {
                        $('#spinner').show()
                        const resp = await fetch(universityData.root_url + `/wp-json/music/v1/searchTags?q=${query}`, {
                            method: "GET",
                            headers: {
                                "X-WP-Nonce": universityData.nonce
                            }
                        })

                        const json = await resp.json()

                        if (!resp.ok) {
                            throw new Error(json.message)
                        }

                        if (json.tags.length) {
                            $('.tags-select-wrapper').find('.select-message-field').removeClass('error-message').text('')
                            return callback(json.tags)
                        }

                        $('.tags-select-wrapper').find('.select-message-field').addClass('error-message').text('no tags found')
                        return callback()
                    } catch (error) {
                        alert('something went wrong')
                        // this.clearOptions();
                        // ;
                    } finally {
                        $('#spinner').hide();
                    }
                }

                return callback()
            },
            onType: function (value) {
                if (!value) {
                    $('.tags-select-wrapper').find('.message-field').removeClass('error-message').text('')

                    this.clearOptions();
                    this.addOption(defaultOptions);
                    this.refreshOptions(false);
                }
            }
        })[0].selectize
    }
});



function onYouTubeIframeAPIReady() {
    jQuery(document).ready(function ($) {
        var player;

        $(document).on('click', '.song-play', function () {
            console.log('hello');
            if (player) {
                player.stopVideo()
                player.destroy()
            }
            const dataLink = $(this).data('song-link').replace(' ,', '')

            const urlObj = new URL(dataLink);
            var videoId = urlObj.searchParams.get("v");

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
            }
        });
    })
}