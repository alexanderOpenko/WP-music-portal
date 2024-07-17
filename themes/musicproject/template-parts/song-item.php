<div class="song-item relative flex py-3 px-4 items-center border-0 border-b border-solid border-slate-300">
    <button class="song-play chartlist-play-button" data-song-link="<?php echo str_replace([',', ' '], '', get_field('song_link')['url']) ?>">
    </button>

    <h3 class="m-0 !ml-5">
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h3>
</div>