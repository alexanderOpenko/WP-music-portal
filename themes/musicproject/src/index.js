jQuery(document).ready(function ($) {
    const $selector = $('#tags-select')

    if (!$.data(document, 'eventsHandlerAttached')) {
        eventsRegistration()
    }

    function eventsRegistration() {
        $(document).on('submit', '.tag-form', function (e) {
            submitTagForm(e, $(this));
        });

        $(document).on('submit', '.update-song-tag', function (e) {
            submitUpdateTag(e, $(this));
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
                const resp = await fetch(musicData.root_url + "/wp-json/wp/v2/song/" + songId + '?force=true', {
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

        function toggleVisibilityAndHeight(element, duration) {
            if (element.css('visibility') === 'visible') {
                element.animate({ height: 0 }, duration, function () {
                    element.addClass('invisible');
                    element.removeClass('visible');
                    target.find('.button-open-text').show();
                    target.find('.button-close-text').hide();
                });
            } else {
                element.addClass('visible').animate({ height: element.get(0).scrollHeight }, duration);
                element.removeClass('invisible');
                target.find('.button-open-text').hide();
                target.find('.button-close-text').show();
            }
        }

        toggleVisibilityAndHeight($content, 300);
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

    async function submitUpdateTag(e, form) {
        const data = new FormData(form[0]);

        try {
            const resp = await fetch(musicData.root_url + "/wp-json/music/v1/updateTag", {
                method: "POST",
                body: data,
                headers: {
                    "X-WP-Nonce": musicData.nonce,
                }
            });
        } catch {
            alert('something went wrong')
        }
    }

    //submit tag
    async function submitTagForm(e, form) {
        e.preventDefault();
        const messageField = form.find('.message-field');
        const data = new FormData(form[0]);

        try {
            const resp = await fetch(musicData.root_url + "/wp-json/music/v1/updateTag", {
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
                        <button class="song-play chartlist-play-button" data-song-id=${post.ID} data-song-link="${post.song_link.replace(/[, ]/g, '')}">
                        </button>
                        <h3 class="m-0 !ml-5">
                            <a class="ajax-link" href="${post.link}">
                                ${post.band} - ${post.title}
                            </a>
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

    if ($selector.length) {
        initSelectize()
    }

    function initSelectize() {
        const defaultOptions = $selector.data('default-tags')
        selectizeInstance = $selector.selectize({
            closeAfterSelect: false,
            dropdownDirection: 'down',
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
                    $('.tags-select-wrapper').find('.select-message-field').removeClass('error-message').text('')
                }

                return callback()
            },
            onType: function (value) {
                console.log(value, 'value');

                if (!value) {
                    console.log('no value');

                    this.addOption(defaultOptions);
                    $('.tags-select-wrapper').find('.select-message-field').removeClass('error-message').text('')

                    this.refreshOptions(false);
                }
            },
            onItemAdd: function () {
                setTimeout(() => {
                    this.focus(); // Устанавливаем фокус на поле ввода после открытия
                }, 10);
            },
            onBlur: function () {
                $('.selectize-dropdown').show()
            },
            onInitialize: function () {
                this.open();
                this.blur();
            }
        })[0].selectize

        $(document).on('click', '.selectize-input', function (e) {
            selectizeInstance.focus();
        });

        console.log(selectizeInstance, 'selectizeInstance');

        // $(".selectize-dropdown").show();
    }



    class Search {
        // 1. describe and create/initiate our object
        constructor() {
            // this.addSearchHTML()
            this.resultsDiv = $("#search-overlay__results")
            this.openButton = $(".js-search-trigger")
            this.closeButton = $(".search-overlay__close")
            this.searchOverlay = $(".search-overlay")
            this.searchField = $("#search-term")
            this.events()
            this.isOverlayOpen = false
            this.isSpinnerVisible = false
            this.previousValue
            this.typingTimer
        }

        // 2. events
        events() {
            this.openButton.on("click", this.openOverlay.bind(this))
            this.closeButton.on("click", this.closeOverlay.bind(this))
            $(document).on("keydown", this.keyPressDispatcher.bind(this))
            this.searchField.on("keyup", this.typingLogic.bind(this))
            $(document).on("click", ".search-overlay a", this.closeOverlay.bind(this))
        }

        // 3. methods (function, action...)
        typingLogic() {
            if (this.searchField.val() != this.previousValue) {
                clearTimeout(this.typingTimer)

                if (this.searchField.val()) {
                    if (!this.isSpinnerVisible) {
                        this.resultsDiv.html('<div class="spinner-loader"></div>')
                        this.isSpinnerVisible = true
                    }
                    this.typingTimer = setTimeout(this.getResults.bind(this), 750)
                } else {
                    this.resultsDiv.html("")
                    this.isSpinnerVisible = false
                }
            }

            this.previousValue = this.searchField.val()
        }

        getResults() {
            $.getJSON(musicData.root_url + "/wp-json/music/v1/search?term=" + this.searchField.val(), results => {                
                this.resultsDiv.html(`
                <div class="row">
                    <div class="one-third songs">
                        <h2 class="search-overlay__section-title">Songs</h2>
                        ${results.song.length ? '<ul class="link-list min-list">' : 
                            `<div class="search-no-results">No songs matches that search</div> <a class="ajax-link" href="${musicData.root_url}/favorite-songs/">View all songs</a>`}
                        ${results.song.map(item => `<li>${songNode(item)}</li>`).join("")}
                        ${results.song.length ? "</ul>" : ""}
                        ${results.song_ids.length > 7 ? `<a class="ajax-link" href="${musicData.root_url}/search-results?type=song&ids=${results.song_ids}">View all searching songs</a>` : ''}
                    </div>
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Tags</h2>
                        ${results.musictag.length ? '<ul class="link-list min-list">' : 
                            `<div class="search-no-results">No tags matches that search.</div> <a class="ajax-link" href="${musicData.root_url}/tag/">View all tags</a>`}
                        ${results.musictag.map(item => `<li><a class="ajax-link" href="${item.permalink}">${item.title}</a> ${item.postType == "post" ? `by ${item.authorName}` : ""}</li>`).join("")}
                        ${results.musictag.length ? "</ul>" : ""}
                        ${results.musictag.length > 7 ? `<a class="ajax-link" href="${musicData.root_url}/search-results?type=musictag&ids=${results.musictag_ids}">View all searching tags</a>` : ''}
                    </div>
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Artists</h2>
                        ${results.artist.length ? '<ul class="link-list min-list">' : 
                            `<div class="search-no-results">No artists matches that search.</div> <a class="ajax-link" href="${musicData.root_url}/artists">View all artists</a>`}
                        ${results.artist.map(item => `<li><a class="ajax-link" href="${item.permalink}">${item.title}</a></li>`).join("")}
                        ${results.artist.length ? "</ul>" : ""}
                        ${results.artist.length > 7 ? `<a class="ajax-link" href="${musicData.root_url}/search-results?type=artist&ids=${results.artist_ids}">View all searching artists</a>` : ''}
                    </div>
                </div>
            `)
                this.isSpinnerVisible = false
            })
        }

        keyPressDispatcher(e) {
            if (e.keyCode == 83 && !this.isOverlayOpen && !$("input, textarea").is(":focus")) {
                this.openOverlay()
            }

            if (e.keyCode == 27 && this.isOverlayOpen) {
                this.closeOverlay()
            }
        }

        openOverlay() {
            this.searchOverlay.addClass("search-overlay--active")
            $("body").addClass("body-no-scroll")
            this.searchField.val("")
            setTimeout(() => this.searchField.focus(), 301)
            this.isOverlayOpen = true
            return false
        }

        closeOverlay() {
            this.searchOverlay.removeClass("search-overlay--active")
            $("body").removeClass("body-no-scroll")
            console.log("our close method just ran!")
            this.isOverlayOpen = false
        }

        addSearchHTML() {
            $("body").append(`
                <div class="search-overlay">
                    <div class="search-overlay__top">
                        <i style="padding: 0 15px;" class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
                        <div>
                        <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term">
                        </div>
                        <div>
                        <i style="padding: 0 15px; background: transparent" class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
                        </div>
                        </div>
                    
                    <div class="content-container">
                        <div id="search-overlay__results"></div>
                    </div>
                </div>
            `)
        }
    }
    const search = new Search()

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