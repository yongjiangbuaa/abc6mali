<?php global $asteria;?>
<?php get_header('construction'); ?>
        <div class="under-construction-message">
            <div class="under-construction-inner">
                <h2><?php echo $asteria['offline_text_id'];?></h2>
                <p><?php echo $asteria['offline_msg_id'];?></p>
                
                <!--MAINTENANCE COUNTDOWN-->
                <?php if (!empty ($asteria['offline_date_id'])) { ?>
                <div class="ast_countdown">
                    <ul id="countdown_mntnc">
                        <li>
                            <span class="days">00</span>
                            <p class="timeRefDays">days</p>
                        </li>
                        <li>
                            <span class="hours">00</span>
                            <p class="timeRefHours">hours</p>
                        </li>
                        <li>
                            <span class="minutes">00</span>
                            <p class="timeRefMinutes">minutes</p>
                        </li>
                        <li>
                            <span class="seconds">00</span>
                            <p class="timeRefSeconds">seconds</p>
                        </li>
                    </ul>
            	</div>
           		<?php } ?>
        </div>
        </div>
        
        <?php wp_footer(); ?>
    </body>
    </html>