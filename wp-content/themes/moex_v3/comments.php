<?php
    if ( post_password_required() ) { ?>
        <p class="nocomments">Bài này muốn xem bạn phải đăng nhập.</p>
    <?php
        return;
    }
?>
 
<!-- You can start editing here. -->
    <div class="clear"></div>
    <div id="comment-content" class="gird_8">
<?php if ( have_comments() ) : ?>
            <h3 id="comments"><?php comments_number('Chưa có Comment', 'Một Comment', '% Comments' );?></h3>
            <ol class="commentlist">
<?php wp_list_comments();?>
            </ol>
         <?php else : // this is displayed if there are no comments so far ?>
            <?php if ( comments_open() ) : ?>
                <!-- If comments are open, but there are no comments. -->
             <?php else : // comments are closed ?>
                <!-- If comments are closed. -->
                <p class="nocomments">Comments đã đóng.</p>
            <?php endif; ?>
        <?php endif; ?>
        <?php if ( comments_open() ) : ?>
        <div id="respond">
            <div class="cb h15"><!----></div>
            <div class="header ctext sdtext"><?php comment_form_title( 'Gửi bình luận', 'Trả lời cho bài %s' ); ?></div>
            <div class="cancel-comment-reply">
                <small><?php cancel_comment_reply_link(); ?></small>
            </div>
            <?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
            <p>Bạn phải <a href="<?php echo wp_login_url( get_permalink() ); ?>">đăng nhập</a> để có thể comment.</p>
            <?php else : ?>
        <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
                    <div class="cb h10"><!----></div>
                    <div class="boxcontent">
                        <div class="boxcontent2">
							<?php if ( is_user_logged_in() ) : ?>
							<?php else : ?>
                            <div class="cb h10"><!----></div>
                            <div class="cot1c"><label for="tbHoTen">Họ tên:</label></div>
                            <div class="cot2c">
                				<input type="text" name="author" class="textbox" id="tbHoTen" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
                            </div>
                            <div class="cb h8"><!----></div>
                            <div class="cot1c"><label for="tbEmail">Email:</label></div>
                            <div class="cot2c">
                				<input type="text" class="textbox" name="email" id="tbEmail" value="<?php echo esc_attr($comment_author_email); ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
                            </div>                         
                			<?php endif; ?>
                            <div class="cb h8"><!----></div>
                            <div class="cot1c"><label for="tbNoiDung">Nội dung</label></div>
                            <div class="cot2c">
                				<textarea name="comment" id="idNoiDung" cols="20" rows="2" class="textbox" tabindex="4" style="height: 70px"></textarea>
                            </div>                                                            
                            <div class="cb h10"><!----></div> 
                        </div>
                    </div>    
                    <div class="pt10">
                        <a class="btOK" tabindex="5" href="javascript:void(0)" onclick="submitcomment()"><span><span>Gửi</span></span></a>                    
                    </div>                
					<script type="text/javascript">
						function submitcomment(){
							$("#commentform").submit();	
						}
					</script>
                <?php comment_id_fields(); ?>
                </p>
                <?php do_action('comment_form', $post->ID); ?>
        </form>
        <?php endif; // If registration required and not logged in ?>
        </div>
<?php endif; // if you delete this the sky will fall on your head ?>
</div>
