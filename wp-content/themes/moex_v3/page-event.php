<?php
	get_header();
?>
        <div id="GioiThieu">
            <div id="mainAdv">
                <a href="#"><img alt="" src="<?php echo get_bloginfo("template_url")?>/pic/ticket_980.jpg" class="anhQC"/></a>                
            </div>
            <div class="content">
				<div class="event">
				<?php
             		if ( have_posts() ) while ( have_posts() ) : the_post();
                        the_content();
                    endwhile;
                ?>
				</div>

			</div>
        </div>
<?php
	get_footer();
?>
