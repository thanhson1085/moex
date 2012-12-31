<style>
	.list-news{width: 100%;}
	.list-news tr td{
		width: 25%;
		vertical-align: text-top;
		font-size: 12px;
	}
	.list-news tr td ul{
		margin: 0;
		padding-left: 25px;
	}
	.list-news h3{
		padding-left: 10px;
		font: normal 15px UTMAvoBold;
		text-transform: uppercase;
		padding-bottom: 10px;
	}
	.news{
		border-top: solid 1px #CCC;
	}
</style>
<div class="news">
		<table class="list-news">
			<tbody><tr>
				<td>
					<div class="block-news">
						<h3>Cuộc sống moEx </h3>
						<ul class="latestnews">
							<?php
							$bookmarks = get_bookmarks( array(
											'orderby'        => 'name',
											'order'          => 'ASC',
											'category_name'  => 'Cot1'
													  ));

									// Loop through each bookmark and print formatted output
									foreach ( $bookmarks as $bm ) { 
										printf( '<li><a class="relatedlink" href="%s">%s</a></li>', $bm->link_url, __($bm->link_name) );
									}
									?>
						</ul>		
					</div>
				</td>
				<td>
					<div class="block-news">
						<h3>Cam kết của moEx</h3>
						<ul class="latestnews">
							<?php
							$bookmarks = get_bookmarks( array(
											'orderby'        => 'name',
											'order'          => 'ASC',
											'category_name'  => 'Cot2'
													  ));

									// Loop through each bookmark and print formatted output
									foreach ( $bookmarks as $bm ) { 
										printf( '<li><a class="relatedlink" href="%s">%s</a></li>', $bm->link_url, __($bm->link_name) );
									}
									?>
						</ul>		
					</div>
				</td>

				<td>
					<div class="block-news">
						<h3>Giới thiệu về moEx</h3>
						<ul class="latestnews">
							<?php
							$bookmarks = get_bookmarks( array(
											'orderby'        => 'name',
											'order'          => 'ASC',
											'category_name'  => 'Cot3'
													  ));

									// Loop through each bookmark and print formatted output
									foreach ( $bookmarks as $bm ) { 
										printf( '<li><a class="relatedlink" href="%s">%s</a></li>', $bm->link_url, __($bm->link_name) );
									}
									?>
						</ul>		
					</div>
				</td>

				<td>
					<div class="block-news">
						<h3>Đối tác nói về moEx</h3>
						<ul class="latestnews">
							<?php
							$bookmarks = get_bookmarks( array(
											'orderby'        => 'name',
											'order'          => 'ASC',
											'category_name'  => 'Cot4'
													  ));

									// Loop through each bookmark and print formatted output
									foreach ( $bookmarks as $bm ) { 
										printf( '<li><a class="relatedlink" href="%s">%s</a></li>', $bm->link_url, __($bm->link_name) );
									}
									?>
						</ul>		
					</div>
				</td>

			</tr>
		</tbody></table>
	</div>
        <div class="cb h20"><!----></div>
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
                <div class="fr"><a class="mnb" href="<?php echo WP_MOBILE_THEME?>" title="Lái xe moEx">moEx Mobile</a></div>
                <div class="mnbSplit"><!----></div>
                <div class="fr"><a class="mnb" target="_blank" href="<?php echo admin_url()?>">Admin</a></div>
                <div class="mnbSplit"><!----></div>
                <div class="fr"><a class="mnb" target="_blank" href="<?php echo get_bloginfo("url")?>/erp/app.php/index">ERP</a></div>
                <div class="mnbSplit"><!----></div>
                <div class="fr"><a class="mnb" target="_blank" href="<?php echo get_bloginfo("url")?>/monitor">Ghi âm 1900565636</a></div>
				<?php endif;?>
                <div class="cb"><!----></div>
            </div>            
            <div class="cb"><!----></div>
        </div>
    </div>    
	<!--div class="facebook-bar" style="position: fixed; top: 40%; left: 0.5em;">
	<iframe id="f2c7dc7b" name="f105dc024" scrolling="no" style="border: none; overflow: hidden; height: 61px; width: 44px;" title="Like this content on Facebook." class="fb_ltr" src="http://www.facebook.com/plugins/like.php?api_key=&amp;locale=en_US&amp;sdk=joey&amp;channel_url=http%3A%2F%2Fstatic.ak.facebook.com%2Fconnect%2Fxd_arbiter.php%3Fversion%3D17%23cb%3Df14ed2171c%26origin%3Dhttp%253A%252F%252Fmoex.vn%252Ff1f3859014%26domain%3Dmoex.vn%26relation%3Dparent.parent&amp;href=http%3A%2F%2Fwww.facebook.com%2FMoexJSC&amp;node_type=link&amp;width=450&amp;font=tahoma&amp;layout=box_count&amp;colorscheme=light&amp;show_faces=false&amp;send=false&amp;extended_social_context=false">
</iframe>
	</div-->
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
        <a href="<?php echo get_bloginfo("url")?>?page_id=191&service=<?php echo $service;?>"><img src="<?php echo get_bloginfo("template_url")?>/pic/onecall.png" /></a>        
    </div>    
	</div>
	</div>
</body>
</html>
