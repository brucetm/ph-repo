<div id="header-bookmark-dropdown-elem" class="bookmark-wrap menu-item-has-children">
    <a href="javascript:void(0)"
       ref="bookmark_bell"
       class="bookmark-link header-bookmark-link">
       <span data-balloon-pos="down" data-balloon="<?php _e( 'bookmarks', 'buddyboss-theme' ); ?>">
            <i class="bookmark"></i>
    		
        </span>
    </a>
    <section class="bookmark-dropdown">
        <header class="notification-header">
            <h2 class="title"><?php _e( 'bookmarks', 'buddyboss-theme' ); ?></h2>
        </header>
        <div class="bookmark-filter">
            <ul class="filter-list">
                <li><button class="triger_categories" value="all-categories" class="active">all types</button></li>
                <li><button value="think">think</button></li>
                <li><button value="build">build</button></li>
                <li><button value="buy">buy</button></li>
            </ul>   
        </div>
        <div class="fav-light-box">
            <?php //echo do_shortcode('[user_favorites include_links="true" include_thumbnails="true" include_buttons="true" thumbnail_size="thumbnail" include_excerpts="true" ]');?>
        </div>
        
		<footer class="bookmark-footer">
          
            <div class="favmore">
    			<a href="<?php echo home_url( '/bookmarks/' );?>" class="delete-all">
    				<?php _e( 'see all', 'buddyboss-theme' ); ?>
    			</a>
            </div>
		</footer>
    </section>
</div>
