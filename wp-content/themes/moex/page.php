<?php 
get_header();
?>
<div class="content-container">
    <div class="content page">
        <div class="left-part-container">
            <div class="left-part page">
			<?php get_template_part('loop','page'); ?>
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
