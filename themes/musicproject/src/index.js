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

        $(document).on('submit', '.artist-form', function (e) {
            submitArtistForm(e, $(this));
        });

        $(document).on('click', 'a.ajax-link', function (e) {
            ajaxLinkHandler(e, $(this))
        })

        $(window).bind('popstate', function (e) {
            ajaxLinkHandler(e, null, window.location.href, true)
        });

        $(document).on('click', '.upload-song-page-js', function (e) {
            handleUploadedSongs($(this))
        })

        $(document).on('click', '.play-first-artist-song-js', function (e) {
            playFirstArtistSong($(this))
        })

        $(document).on('click', '.accordion-button', function (e) {
            accordeonToggler($(this))
        });

        $(document).on('change', 'input[type="file"]', function (e) {
            imageUploadHandler($(this), e)
        });

        $(document).on('click', '.delete-upload-image', function (e) {
            deleteUploadedImage($(this), e)
        });

        $(document).on('click', '.upload-image-button-js', function (e) {
            triggerImageFileInput($(this), e)
        });

        $(document).on('click', '.delete-song', function () {
            deleteSongHandler($(this))
        })
        // initSelectizeTags()

        $.data(document, 'eventsHandlerAttached', true);
    }

    async function deleteSongHandler(target) {
        const songId = target.data('song-id')
        const songName = target.data('song-name')

        const deleteConfirm = confirm(`do you realy want to delete song ${songName} ?`)

        if (deleteConfirm) {
            try {
                const resp = await fetch(musicData.root_url + "/wp-json/wp/v2/song/" + songId, {
                    method: 'DELETE',
                    headers: {
                        'X-WP-Nonce': musicData.nonce
                    }
                })
               
                if (window.location.pathname.includes(songName.replace(' ', '-'))) {                    
                    window.history.back()
                } else {
                    target.closest('.song-item').remove()
                }
            } catch {
                alert('Something went wrong')
            }
        }
    }

    function triggerImageFileInput(target) {
        target.closest('form').find('input[type="file"]').click()
    }

    function deleteUploadedImage(target) {
        const imageNameHolder = target.closest('form').find('.uploaded-image-name')
        const imageInput = target.closest('form').find('input[type="file"]')
        const imageHolder = target.closest('form').find('.image-holder')

        imageInput.val('')
        imageHolder.find('img').remove()
        imageHolder.hide()
        imageNameHolder.text('Image is not selected')
    }

    function imageUploadHandler(target, e) {
        const imageNameHolder = target.closest('form').find('.uploaded-image-name')
        const imageHolder = target.closest('form').find('.image-holder')
        file = URL.createObjectURL(e.target.files[0])

        if (file && imageHolder) {
            imageHolder.append(`<img src="${file}"/>`)
            imageHolder.show()
            imageNameHolder.text(e.target.files[0].name)
        } else {
            imageNameHolder.text('Image is not selected')
        }
    }

    function accordeonToggler(target) {
        const $content = target.closest('.accordion').find('.accordion-content')

        $content.slideToggle(300, function () {
            if ($content.is(':visible')) {
                target.find('.button-open-text').hide();
                target.find('.button-close-text').show();
            } else {
                target.find('.button-open-text').show();
                target.find('.button-close-text').hide();
            }
        });
    }

    function playFirstArtistSong() {
        $('.song-play').first().click()
    }

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

    //submit song
    async function submitArtistForm(e, form) {
        e.preventDefault()
        const messageField = form.find('.message-field');
        const data = new FormData(form[0]);

        const name = normalizeArtistName(data.get('band'))
        data.set('band', name)

        try {
            const resp = await fetch(musicData.root_url + "/wp-json/music/v1/createArtist", {
                method: "POST",
                body: data,
                headers: {
                    "X-WP-Nonce": musicData.nonce,
                }
            });
            const json = await resp.json();

            if (!resp.ok) {
                throw new Error(json.message);
            } else {
                messageField.text("Artist created");
                messageField.removeClass("error-message").addClass("success-message");
                const container = $(".artists-list")
                container.prepend(baseCardNode(json.post))
            }
        } catch (error) {
            messageField.text(error.message);
            messageField.removeClass("success-message").addClass("error-message");
        }
    }

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

        const name = normalizeArtistName(data.get('band'))
        data.set('band', name)

        data.delete('tags')
        data.append('tags', tags)

        try {
            const resp = await fetch(musicData.root_url + "/wp-json/music/v1/createSong", {
                method: "POST",
                body: data,
                headers: {
                    "X-WP-Nonce": musicData.nonce,
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
            const resp = await fetch(musicData.root_url + "/wp-json/music/v1/manageTag", {
                method: "POST",
                body: data,
                headers: {
                    "X-WP-Nonce": musicData.nonce,
                }
            });
            const json = await resp.json();

            if (!resp.ok) {
                throw new Error(json.message);
            } else {
                messageField.text("tag created");
                messageField.removeClass("error-message").addClass("success-message");

                const container = $(".tags-list")
                container.prepend(baseCardNode(json.post))
            }
        } catch (error) {
            messageField.text(error.message);
            messageField.removeClass("success-message").addClass("error-message");
        }
    }
    // Делегируем событие click на документ
    function ajaxLinkHandler(e, target, urlParam = null, isPopState = false) {
        e.preventDefault();

        const url = urlParam ?? target.attr('href');

        $.ajax({
            url: url,
            success: function (data) {
                const tempElement = $('<div>').html(data);
                const contentData = tempElement.find('#content-container').html();
                $('#content-container').html(contentData);
                // Обновляем URL только если это не popstate
                if (!isPopState) {
                    history.pushState(null, '', url);
                }
            }
        });
    }

    async function handleUploadedSongs(target) {
        const $maxPages = target.attr('data-max-pages')
        const $pageData = parseInt(target.attr('data-page'))
        const $artist = target.attr('data-artist') ? parseInt(target.attr('data-artist')) : false
        const $tag = target.attr('data-tag') ? parseInt(target.attr('data-tag')) : false

        target.attr('data-page', $pageData + 1);

        let url = musicData.root_url + `/wp-json/music/v1/songsPaginations?page=${$pageData}`

        if ($artist) {
            url += `?artist=${$artist}`
        } else if ($tag) {
            url += `?tag=${$tag}`
        }

        try {
            const resp = await fetch(url, {
                method: "GET",
                headers: {
                    "X-WP-Nonce": musicData.nonce,
                }
            });

            const json = await resp.json();

            if (!resp.ok) {
                console.log('here');
                throw new Error(json.message);
            } else {
                const container = $('.favsongs-page-list')

                json.posts.forEach(post => {
                    container.append(songNode(post))
                })

                if ($pageData + 1 >= $maxPages) {
                    target.hide()
                }
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

    function baseCardNode(post) {
        return `
            <div class="artist-card flex flex-col">
                <div class="artist-card_image relative pt-[100%]">
                    <img class="full-absolute object-cover" src="${post.image_link}"/>
                </div>

                <div class="artist-card_info">
                    ${post.title}
                </div>
            </div>
        `
    }

    const $selector = $('#new-song-tags')
    if ($selector.length) {
        const defaultOptions = $selector.data('default-tags')
        const selectizeInstance = $selector.selectize({
            closeAfterSelect: true,
            loadThrottle: 300,
            respect_word_boundaries: false,
            load: async function (query, callback) {
                if (query && !defaultOptions.some(el => el.text.toLowerCase().startsWith(query.toLowerCase())) && query.trim() !== '') {
                    try {
                        $('#spinner').show()
                        const resp = await fetch(musicData.root_url + `/wp-json/music/v1/searchTags?q=${encodeURIComponent(query)}`, {
                            method: "GET",
                            headers: {
                                "X-WP-Nonce": musicData.nonce
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
                } else {
                    $('.tags-select-wrapper').find('.message-field').removeClass('error-message').text('')
                }

                return callback()
            },
            onType: function (value) {
                if (!value) {
                    this.addOption(defaultOptions);
                    $('.tags-select-wrapper').find('.message-field').removeClass('error-message').text('')
                    alert(2)
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
            $this = $(this)
            $('.close-video-js').show()
            $('.youtube-video').css('z-index', 10)

            $(document).on('click', '.close-video-js', function () {
                player.stopVideo()
                player.destroy()
                player = false

                $(this).hide()
                $('.youtube-video').css('z-index', -1)
            })

            const dataLink = $(this).data('song-link').replace(' ,', '')
            const urlObj = new URL(dataLink);
            var videoId = urlObj.searchParams.get("v");

            createPlayer(videoId);

            function createPlayer(videoId) {
                const allSongsOnPage = $('.song-item');
                const index = allSongsOnPage.index($this.closest('.song-item'))

                if (index + 1 === allSongsOnPage.length) {
                    $('.upload-song-page-js').click()
                }

                if (!player) {
                    player = new YT.Player('player', {
                        videoId: videoId,
                        events: {
                            'onReady': onPlayerReady,
                            'onStateChange': onPlayerStateChange
                        }
                    });
                } else {
                    player.loadVideoById(videoId)
                }
            }

            function onPlayerReady(event) {
                event.target.playVideo();
            }

            function onPlayerStateChange(event) {
                if (event.data === YT.PlayerState.ENDED) {
                    fetch(musicData.root_url + `/wp-json/music/v1/updatePlayCount?id=${$this.data('song-id')}`, {
                        headers: {
                            "X-WP-Nonce": musicData.nonce
                        }
                    })

                    const nextSongItem = $this.closest('.song-item').next('.song-item').find('.song-play');

                    if (!nextSongItem.length) {
                        return
                    }

                    const nextSongLink = new URL(nextSongItem.attr('data-song-link'));
                    var videoId = nextSongLink.searchParams.get("v");
                    player.loadVideoById(videoId)

                    $this = nextSongItem
                }
            }
        });
    })
}