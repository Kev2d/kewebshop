<section class="entertainment">

    <div class="entertainment__video">

        <?php if (get_field('video_title')) : ?>
            <h2><?= get_field('video_title'); ?></h2>
        <?php endif; ?>

        <div class="entertainment__video-container js-video-container">
            <?php if (get_field('video')) : ?>
                <?= get_field('video'); ?>
            <?php endif; ?>
        </div>

    </div>


    <?php if (get_field('chess_quiz')) : ?>

        <div class="entertainment__quiz">

            <?php if (get_field('chess_quiz_title')) : ?>
                <h2><?= get_field('chess_quiz_title'); ?></h2>
            <?php endif; ?>

            <div class="entertainment__quiz-container js-quiz">

                <img id="puzzleImage" alt="Daily Chess Puzzle" />

                <h5><span id="puzzleText" /></h5>

            </div>

        </div>

    <?php endif; ?>



</section>