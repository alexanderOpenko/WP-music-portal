<?php 
    $open_name = $args['open_name'] ?? 'expand';
    $close_name = $args['close_name'] ?? 'collapse';
    $is_open_dafault = $args['is_open'] ?? false
?>

<button class="accordion-button secondary-button flex items-center">
    <span class="button-open-text <?php echo $is_open_dafault ? 'hidden' : ''?>">
        <?php echo $open_name ?>
    </span>

    <span class="button-close-text <?php echo $is_open_dafault ? '' : 'hidden'?>">
        <?php echo $close_name ?>
    </span>

    <div class="w-[15px] h-[15px] ml-[10px] icon">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" height="800px" width="800px" version="1.1" id="Layer_1" viewBox="0 0 330 330" xml:space="preserve">
            <path id="XMLID_225_" d="M325.607,79.393c-5.857-5.857-15.355-5.858-21.213,0.001l-139.39,139.393L25.607,79.393  c-5.857-5.857-15.355-5.858-21.213,0.001c-5.858,5.858-5.858,15.355,0,21.213l150.004,150c2.813,2.813,6.628,4.393,10.606,4.393  s7.794-1.581,10.606-4.394l149.996-150C331.465,94.749,331.465,85.251,325.607,79.393z" />
        </svg>
    </div>
</button>