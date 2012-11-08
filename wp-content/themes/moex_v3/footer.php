        <div id="Footer">
            <div class="fl">Bản quyền website thuộc về moEx.</div>
            <div id="Share">
                <a target="_blank" href="http://www.facebook.com/MoexJsc" class="share1">&nbsp;</a>
                <a href="#" class="share2">&nbsp;</a>
                <a href="#" class="share3">&nbsp;</a>
                <a href="#" class="share4">&nbsp;</a>
                <a href="#" class="share5">&nbsp;</a>
            </div>
            <div id="MenuBottom">
                <div class="fr"><a class="mnb" href="<?php echo get_bloginfo("url")?>/career" title="Nghề nghiệp">Nghề nghiệp</a></div>
                <div class="mnbSplit"><!----></div>
                <div class="fr"><a class="mnb" href="<?php echo get_bloginfo("url")?>?page_id=2" title="Liên hệ">Liên hệ</a></div>
                <div class="mnbSplit"><!----></div>
                <div class="fr"><a class="mnb" href="<?php echo get_bloginfo("url")?>?page_id=36" title="Giới thiệu">Giới thiệu</a></div>
				<?php if(current_user_can("edit_posts")):?>
                <div class="mnbSplit"><!----></div>
                <div class="fr"><a class="mnb" target="_blank" href="<?php echo admin_url()?>">Admin</a></div>
                <div class="mnbSplit"><!----></div>
                <div class="fr"><a class="mnb" target="_blank" href="<?php echo get_bloginfo("url")?>/erp/app.php/index">ERP</a></div>
				<?php endif;?>
                <div class="cb"><!----></div>
            </div>            
            <div class="cb"><!----></div>
        </div>
    </div>    
    <div id="OnCall">
		<?php 
			$page_id = $posts[0]->ID;
			switch($page_id){
				case 164:
					$service = 1; break;
				case 161:
					$service = 2; break;
				case 170:
					$service = 3; break;
				case 172:
					$service = 4; break;
				case 166:
					$service = 5; break;
				default:
					$service = 1; break;
			}
		?>
        <a href="<?php echo get_bloginfo("url")?>?page_id=191&service=<?php echo $service;?>"><img src="<?php echo get_bloginfo("template_url")?>/pic/adv/onCall.jpg" /></a>        
    </div>    
	</div>
	</div>
</body>
</html>
