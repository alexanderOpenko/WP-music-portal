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

        $(document).on('submit', '.edit-post-description', function (e) {
            submitUpdateCustomPostContent(e, $(this));
        });

        $(document).on('submit', '.song-form', function (e) {
            submitSongForm(e, $(this));
        });

        $(document).on('submit', '.artist-form', function (e) {
            submitArtistForm(e, $(this));
        });

        $(document).on('click', 'a', function (e) {
            ajaxLinkHandler(e, $(this))
        })

        $(window).bind('popstate', function (e) {
            ajaxLinkHandler(e, null, window.location.href, true)
        });

        $(document).on('click', '.upload-song-page-js', function (e) {
            handleUploadedSongs($(this))
        })

        $(document).on('click', '.upload-artist-page-js', function (e) {
            handleUploadedArtists($(this))
        })

        $(document).on('click', '.artists-page-js', function (e) {
            handleAllArtists($(this))
        })
        
        $(document).on('click', '.tags-page-js', function (e) {
            handleAlltags($(this))
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
            deleteContentHandler($(this))
        })

        $(document).on('submit', '.save-icon-form', function (e) {
            saveSubmitHandler(e, $(this))
        })

        $(document).on('click', '.paginate-saved-content-js', function (e) {
            handleSavedContentPagination($(this))
        })
        
        $(document).on('click', '.edit-description-js', function (e) {
            handleEditDescription($(this))
        })

        $(document).on('submit', '.update-post-image', function (e) {
            handleUpdateImage(e, $(this))
        })

        $.data(document, 'eventsHandlerAttached', true);
    }

    async function handleUpdateImage(e, form) {
        e.preventDefault()

        const data = new FormData(form[0])

        const resp = await fetch(musicData.root_url + "/wp-json/music/v1/updateImage", {
            method: "POST",
            body: data
        })

        window.location.reload()
    }

    function handleEditDescription(target) {
        let $textarea = target.closest('.description-container').find('textarea')
        const $button = target.closest('.description-container').find('button')

        if (target.attr('data-editting') == 'disable') {
            $textarea.show()
            target.closest('.description-container').addClass('editable')
            $textarea.removeAttr('readonly')
            $button.show()  
            target.attr('data-editting', 'enable')
        } else {            
            if ($textarea.val() === '') {
                $textarea.hide()
            }
            target.closest('.description-container').removeClass('editable')
            $textarea.attr('readonly', true)
            $button.hide()  
            target.attr('data-editting', 'disable')
        }
    }

    async function submitUpdateCustomPostContent(e, form) {
        e.preventDefault()
        const data = new FormData(form[0])

        try {
            const resp = await fetch(musicData.root_url + "/wp-json/music/v1/updateCustomPostContent", {
                method: 'POST',
                body: data,
                headers: {
                    "X-WP-Nonce": musicData.nonce,
                }
            })

            if (resp.ok) {
                $('.edit-description-js').click()
            }
        } catch {
            alert('something went wrong')
        }
    }

    async function saveSubmitHandler(e, form) {
        e.preventDefault()
        const data = new FormData(form[0])
        const bookmarkBtn = form.find('.save-icon-btn')
        const action = data.get('action')

        try {
            const resp = await fetch(musicData.root_url + "/wp-json/music/v1/saveSong", {
                method: 'POST',
                body: data,
                headers: {
                    "X-WP-Nonce": musicData.nonce,
                }
            });
            const json = await resp.json();

            if (!resp.ok) {
                throw new Error(json.message);
            } else {
                if (action === 'save') {
                    bookmarkBtn.addClass('saved-song')
                    form.find('input[name="action"]').val('unsave')
                } else {
                    bookmarkBtn.removeClass('saved-song')
                    form.find('input[name="action"]').val('save')
                }
            }
        } catch (error) {
            alert(error)
        }
    }

    async function deleteContentHandler(target) {
        const postId = target.data('post-id')
        const contentName = target.data('content-name')
        const postType = target.data('post-type')

        const deleteConfirm = confirm(`do you realy want to delete ${postType} ${contentName} ?`)

        if (deleteConfirm) {
            try {
                const resp = await fetch(musicData.root_url + `/wp-json/wp/v2/${postType}/${postId}?force=true`, {
                    method: 'DELETE',
                    headers: {
                        'X-WP-Nonce': musicData.nonce
                    }
                })

                if (window.location.pathname.includes(contentName.replace(' ', '-'))) {
                    window.history.back()
                }

                if (target.closest('.song-item')) {
                    target.closest('.song-item').remove()
                }
            } catch (e) {
                console.log(e);

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
        const imageContainer = target.closest('form').find('.image-container')

        imageInput.val('')
        imageContainer.html('')
        imageContainer.closest('.image-holder').hide()
        imageNameHolder.text('Image is not selected')
    }

    function imageUploadHandler(target, e) {
        const imageNameHolder = target.closest('form').find('.uploaded-image-name')
        const imageHolder = target.closest('form').find('.image-holder')
        const imageContainer = imageHolder.find('.image-container')
        file = URL.createObjectURL(e.target.files[0])

        if (file && imageHolder) {
            imageContainer.html(`<img src="${file}"/>`)
            imageHolder.show()
            imageNameHolder.text(e.target.files[0].name)
        } else {
            imageNameHolder.text('Image is not selected')
        }
    }

    function accordeonToggler(target) {
        const $content = target.closest('.accordion').find('.accordion-content').first();
    
            /**
         * Custom function to toggle accordion visibility without using jQuery's built-in slide or fade methods.
         * 
         * Reasoning:
         * - The built-in jQuery methods (e.g., `slideUp()`, `slideDown()`, or `toggle()`) apply `display: none` 
         *   to hidden elements, which causes issues when working with plugins like Selectize.js. 
         * - For example, Selectize.js requires the `select` element to be visible for proper initialization. 
         * - If the accordion is hidden using `display: none`, Selectize will not initialize properly, 
         *   breaking the functionality for hidden form elements.
         * - Instead, this approach only sets the height of the accordion to `0` while keeping the element visible in the DOM,
         *   thus ensuring that plugins like Selectize can still initialize and work correctly, even when the content is hidden.
         */

        function toggleVisibility(element) {
            if (element.hasClass('visible')) {
                // Скрытие: высота 0 и добавление класса "invisible"
                element.addClass('invisible').removeClass('visible').css('height', 0);
                target.find('.button-open-text').show();
                target.find('.button-close-text').hide();
                element.find('.accordion-content').addClass('invisible').removeClass('visible').css('height', 0);
            } else {
                // Отображение: убираем класс "invisible" и устанавливаем "visible"
                element.removeClass('invisible').addClass('visible').css('height', 'auto');
                target.find('.button-open-text').hide();
                target.find('.button-close-text').show();
            }
        }
    
        toggleVisibility($content);
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
                messageField.show()
                messageField.text("Artist created");
                messageField.removeClass("error-message").addClass("success-message");
                const container = $(".artists-list")
                container.prepend(baseCardNode(json.post))
                form[0].reset()
            }
        } catch (error) {
            messageField.show()
            messageField.text(error.message);
            messageField.removeClass("success-message").addClass("error-message");
        }

        setTimeout(() => messageField.hide(), 3000)
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
                messageField.show()
                messageField.text("Song created");
                messageField.removeClass("error-message").addClass("success-message");
                const container = $(".favsongs-page-list")
                container.prepend(songNode(json.post))
                form[0].reset()
            }
        } catch (error) {
            messageField.hide()
            messageField.text(error.message);
            messageField.removeClass("success-message").addClass("error-message");
        }

        setTimeout(() => messageField.hide(), 3000)
    }

    async function submitUpdateTag(e, form) {
        e.preventDefault()
        const data = new FormData(form[0]);

        try {
            const resp = await fetch(musicData.root_url + "/wp-json/music/v1/updateTag", {
                method: "POST",
                body: data,
                headers: {
                    "X-WP-Nonce": musicData.nonce,
                }
            });

            if (resp.ok) {
                window.location.reload()
            }
        } catch (e) {
            console.log(e);
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
            console.log(json, 'json');

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
    async function ajaxLinkHandler(e, target, urlParam = null, isPopState = false) {
        if (target && target.hasClass('normal-link')) {
            return
        }

        if (target && window.location.href == target.attr('href')) {
            e.preventDefault()
            return
        }

        console.log(target, 'target');
        

        e.preventDefault()

        $('.spinner-js').removeClass('hidden')
        const url = urlParam ?? target.attr('href');

        try {
            const resp = await fetch(url)

            if (!resp.ok) {
                throw new Error()
            } else {
                const data = await resp.text();
                
                const tempElement = $('<div>').html(data);
                const contentData = tempElement.find('#content-container').html();
                $('#content-container').html(contentData);

                if (!isPopState) {
                    history.pushState(null, '', url);
                }
                $('.spinner-js').addClass('hidden')
            }
        } catch (e) {
            alert (e)
        }
    }

    async function handleSavedContentPagination(target) {
        let url
        const $pageData = parseInt(target.attr('data-page'))
        const $maxPages = target.attr('data-max-pages')

        if (target.data('type') == 'saved-artists') {
            url = musicData.root_url + `/wp-json/music/v1/artistsPaginations?page=${$pageData}`
        }

        if (target.data('type') == 'saved-songs') {
            url = musicData.root_url + `/wp-json/music/v1/savedSongsPaginations?page=${$pageData}`
        }

        if (target.data('type') == 'saved-tags') {
            url = musicData.root_url + `/wp-json/music/v1/savedTagsPaginations?page=${$pageData}`
        }

        try {
            const resp = await fetch(url, {
                headers: {
                    "X-WP-Nonce": musicData.nonce,
                }
            })
            const json = await resp.json();

            if (!resp.ok) {
                throw new Error()
            } else {
                if (target.data('type') == 'saved-artists' || target.data('type') == 'saved-tags') {
                    const container = $('.card-list')

                    json.posts.forEach(post => {
                        container.append(baseCardNode(post))
                    })
                }

                if (target.data('type') == 'saved-songs') {
                    const container = $('.songs-list')

                    json.posts.forEach(post => {
                        container.append(songNode(post))
                    })
                }

                if ($pageData >= $maxPages) {
                    target.hide()
                }

                target.attr('data-page', $pageData + 1);
            }
        } catch {
            alert('Something went wrong')
        }
    }

    async function handleUploadedArtists(target) {
        const $maxPages = target.attr('data-max-pages')
        const $pageData = parseInt(target.attr('data-page'))

        target.attr('data-page', $pageData + 1)

        try {
            const resp = await fetch(musicData.root_url + `/wp-json/music/v1/uploadedArtistsPaginations?page=${$pageData}`)
            const json = await resp.json();

            if (!resp.ok) {
                throw new Error(json.message);
            } else {
                const container = $('.artists-list')

                json.posts.forEach(post => {
                    container.append(baseCardNode(post))
                })

                if ($pageData >= $maxPages) {
                    target.hide()
                }
            }
        } catch {
            alert ('Something went wrong')
        }
    }

    async function handleAlltags(target) {
        const $maxPages = target.attr('data-max-pages')
        const $pageData = parseInt(target.attr('data-page'))

        target.attr('data-page', $pageData + 1)

        try {
            const resp = await fetch(musicData.root_url + `/wp-json/music/v1/allTagsPaginations?page=${$pageData}`)
            const json = await resp.json();

            if (!resp.ok) {
                throw new Error(json.message);
            } else {
                const container = $('.tags-list')

                json.posts.forEach(post => {
                    container.append(baseCardNode(post))
                })

                if ($pageData >= $maxPages) {
                    target.hide()
                }
            }
        } catch (e) {
            console.log(e, 'eee');
            
            alert ('Something went wrong')
        }
    }

    async function handleAllArtists(target) {
        const $maxPages = target.attr('data-max-pages')
        const $pageData = parseInt(target.attr('data-page'))

        target.attr('data-page', $pageData + 1)

        try {
            const resp = await fetch(musicData.root_url + `/wp-json/music/v1/allArtistsPaginations?page=${$pageData}`)
            const json = await resp.json();

            if (!resp.ok) {
                throw new Error(json.message);
            } else {
                const container = $('.artists-list')

                json.posts.forEach(post => {
                    container.append(baseCardNode(post))
                })

                if ($pageData >= $maxPages) {
                    target.hide()
                }
            }
        } catch {
            alert ('Something went wrong')
        }
    }

    async function handleUploadedSongs(target) {
        const $maxPages = target.attr('data-max-pages')
        const $pageData = parseInt(target.attr('data-page'))
        const $artist = target.attr('data-artist') ? parseInt(target.attr('data-artist')) : false
        const $tag = target.attr('data-tag') ? parseInt(target.attr('data-tag')) : false
        const $is_all_songs = target.attr('data-all-songs') ?? false

        target.attr('data-page', $pageData + 1);

        let url = musicData.root_url + `/wp-json/music/v1/songsPaginations?page=${$pageData}`

        if ($artist) {
            url += `&artist=${$artist}`
        } else if ($tag) {
            url += `&tag=${$tag}`
        } else if ($is_all_songs) {
            url += `&all_songs=true`
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
                throw new Error(json.message);
            } else {
                const container = $('.songs-list')

                json.posts.forEach(post => {
                    container.append(songNode(post))
                })

                if ($pageData >= $maxPages) {
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
        return `<div class="song-item relative flex py-3 px-4 items-center justify-between border-0 border-b border-solid border-slate-300">
                    <div class="flex items-center">
                        <button class="song-play chartlist-play-button" data-song-id=${post.ID} data-song-link="${post.song_link.replace(/[, ]/g, '')}">
                        </button>
                        <h3 class="m-0 !ml-5">
                            <a class="ajax-link" href="${post.link}">
                                ${post.band} - ${post.title}
                            </a>
                        </h3>
                    </div>

                        <div class="icon delete-song" data-song-id=${post.ID} data-song-name=${post.title}">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" version="1.1" id="Capa_1" width="800px" height="800px" viewBox="0 0 482.428 482.429" xml:space="preserve">
                                <g>
                                    <g>
                                        <path d="M381.163,57.799h-75.094C302.323,25.316,274.686,0,241.214,0c-33.471,0-61.104,25.315-64.85,57.799h-75.098    c-30.39,0-55.111,24.728-55.111,55.117v2.828c0,23.223,14.46,43.1,34.83,51.199v260.369c0,30.39,24.724,55.117,55.112,55.117    h210.236c30.389,0,55.111-24.729,55.111-55.117V166.944c20.369-8.1,34.83-27.977,34.83-51.199v-2.828    C436.274,82.527,411.551,57.799,381.163,57.799z M241.214,26.139c19.037,0,34.927,13.645,38.443,31.66h-76.879    C206.293,39.783,222.184,26.139,241.214,26.139z M375.305,427.312c0,15.978-13,28.979-28.973,28.979H136.096    c-15.973,0-28.973-13.002-28.973-28.979V170.861h268.182V427.312z M410.135,115.744c0,15.978-13,28.979-28.973,28.979H101.266    c-15.973,0-28.973-13.001-28.973-28.979v-2.828c0-15.978,13-28.979,28.973-28.979h279.897c15.973,0,28.973,13.001,28.973,28.979    V115.744z" />
                                        <path d="M171.144,422.863c7.218,0,13.069-5.853,13.069-13.068V262.641c0-7.216-5.852-13.07-13.069-13.07    c-7.217,0-13.069,5.854-13.069,13.07v147.154C158.074,417.012,163.926,422.863,171.144,422.863z" />
                                        <path d="M241.214,422.863c7.218,0,13.07-5.853,13.07-13.068V262.641c0-7.216-5.854-13.07-13.07-13.07    c-7.217,0-13.069,5.854-13.069,13.07v147.154C228.145,417.012,233.996,422.863,241.214,422.863z" />
                                        <path d="M311.284,422.863c7.217,0,13.068-5.853,13.068-13.068V262.641c0-7.216-5.852-13.07-13.068-13.07    c-7.219,0-13.07,5.854-13.07,13.07v147.154C298.213,417.012,304.067,422.863,311.284,422.863z" />
                                    </g>
                                </g>
                            </svg>
                        </div>
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
                        ${results.artist.length > 0 ? `<a class="ajax-link" href="${musicData.root_url}/search-results?type=artist&ids=${results.artist_ids}">View all searching artists</a>` : ''}
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
            $('.youtube-video').css('z-index', 1000)

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