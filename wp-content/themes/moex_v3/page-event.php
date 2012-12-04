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
                    <div class="cb h15"><!----></div>
                    <div class="">
                        <div class="fl">
                            <!-- AddThis Button BEGIN -->
                            <div class="addthis_toolbox addthis_default_style ">
                            <!--<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>-->
                            <a class="addthis_button_facebook_like"></a>
                            <a class="addthis_button_tweet"></a>
                            <a class="addthis_button_google_plusone" g:plusone:size="normal"></a>
                            <!--<a class="addthis_counter addthis_pill_style"></a>-->
                            </div>
                            <script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4e70275244deb51b"></script>
                            <!-- AddThis Button END -->
                        </div>
                    </div>
                    <div class="cb h15"><!----></div>

				</div>

			</div>
        </div>
<?php
	get_footer();
?>
