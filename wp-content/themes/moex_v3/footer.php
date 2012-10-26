        <div id="Footer">
            <div class="fl">Bản quyền website thuộc về moEx.</div>
            <div id="Share">
                <a href="#" class="share1">&nbsp;</a>
                <a href="#" class="share2">&nbsp;</a>
                <a href="#" class="share3">&nbsp;</a>
                <a href="#" class="share4">&nbsp;</a>
                <a href="#" class="share5">&nbsp;</a>
            </div>
            <div id="MenuBottom">
                <div class="fr"><a class="mnb" href="<?php echo get_bloginfo("url")?>?page_id=36" title="Nghề nghiệp">Nghề nghiệp</a></div>
                <div class="mnbSplit"><!----></div>
                <div class="fr"><a class="mnb" href="<?php echo get_bloginfo("url")?>?page_id=2" title="Liên hệ">Liên hệ</a></div>
                <div class="mnbSplit"><!----></div>
                <div class="fr"><a class="mnb" href="<?php echo get_bloginfo("url")?>?page_id=36" title="Giới thiệu">Giới thiệu</a></div>
				<?php if(current_user_can("edit_posts")):?>
                <div class="mnbSplit"><!----></div>
                <div class="fr"><a class="mnb" href="<?php echo admin_url()?>">Admin</a></div>
                <div class="mnbSplit"><!----></div>
                <div class="fr"><a class="mnb" href="<?php echo get_bloginfo("url")?>/erp/app.php/index">ERP</a></div>
				<?php endif;?>
                <div class="cb"><!----></div>
            </div>            
            <div class="cb"><!----></div>
        </div>
    </div>    
	</div>
	</div>
</body>
</html>
