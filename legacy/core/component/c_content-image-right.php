<div class="section companion tournaments">
    <div class="container">
        <div class="companion-container">
            <div class="text-container">
                <h1>
                    <?php echo $title1; ?> <span> <?php echo $title2; ?></span>
                </h1>
                <p>
                    <?php echo $bodytext; ?>
                </p>
                <?php
                //if button text != "" then echo button
                if ($button_text != "") {
                    echo '<a href="' . $button_url . '" class="button is-primary is-medium">' . $button_text . '</a>';
                }
                ?>

            </div>
            <img src="img/character.webp" alt="character" class="companion-image" />
        </div>

    </div>
</div>
