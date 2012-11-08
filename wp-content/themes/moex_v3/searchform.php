                <div id="SearchBox">
					<form role="search" method="get" id="searchform" action="<?php echo get_bloginfo("url")?>">
                    <div class="fr">
                        <input id="Text1" name="s" type="text" class="textbox" value="Tìm kiếm..." onclick="if(this.value=='Tìm kiếm...') this.value=''" onblur="if(this.value=='') this.value='Tìm kiếm...'"/>
                    </div>
                    <div class="fr">
                        <input id="Submit1" type="submit" value=" " onclick="window.location='#'" class="btsearch" title="Click để tìm kiếm"/>
                    </div>
                    <div class="cb"><!----></div>
					</form>
                </div>
