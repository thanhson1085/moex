<?php 
get_header();
?>
<div class="content-container">
    <div class="content page">
        <div class="left-part-container">
            <div class="left-part single">
			<?php get_template_part('loop','single'); ?>
            </div>
        </div>
        <div class="right-part-container">
            <div class="right-part">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
?>
