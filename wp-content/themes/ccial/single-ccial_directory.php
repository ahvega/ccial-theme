<?php
/**
 * Single Directory Entry Template
 * 
 * @package CCIAL
 * @version 1.0.0
 * @since 1.0.0
 */

get_header(); ?>

<div id="main" class="clearfix">
    <div class="fusion-row">
        <div class="fusion-content-boxes content-boxes columns fusion-columns-1 fusion-columns-total-1 fusion-content-boxes-1 content-boxes-icon-on-top row content-boxes-container">
            <div class="fusion-content-box content-box content-box-column content-box-1 content-box-hover-timeline">
                <div class="fusion-content-box-hover content-box-hover">
                    <div class="fusion-content-box-content">
                        <div class="fusion-content-box-content-inner">
                            
                            <?php while (have_posts()) : the_post(); ?>
                            
                            <article id="post-<?php the_ID(); ?>" <?php post_class('directory-entry-single'); ?>>
                                
                                <!-- Entry Header -->
                                <header class="entry-header">
                                    <h1 class="entry-title"><?php the_title(); ?></h1>
                                    
                                    <div class="entry-meta">
                                        <?php 
                                        $directory_types = get_the_terms(get_the_ID(), 'directory_type');
                                        $countries = get_the_terms(get_the_ID(), 'country_region');
                                        ?>
                                        
                                        <?php if ($directory_types && !is_wp_error($directory_types)): ?>
                                            <span class="entry-type">
                                                <?php echo esc_html($directory_types[0]->name); ?>
                                            </span>
                                        <?php endif; ?>
                                        
                                        <?php if ($countries && !is_wp_error($countries)): ?>
                                            <span class="entry-country">
                                                <?php echo esc_html($countries[0]->name); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </header>
                                
                                <!-- Featured Image -->
                                <?php if (has_post_thumbnail()): ?>
                                <div class="entry-featured-image">
                                    <?php the_post_thumbnail('large'); ?>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Entry Content -->
                                <div class="entry-content">
                                    <?php the_content(); ?>
                                </div>
                                
                                <!-- Directory Information -->
                                <div class="directory-information">
                                    
                                    <!-- Directory Type -->
                                    <?php if ($directory_types && !is_wp_error($directory_types)): ?>
                                    <div class="directory-section">
                                        <h3><?php _e('Directory Type', 'ccial'); ?></h3>
                                        <p><?php echo esc_html($directory_types[0]->name); ?></p>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <!-- Country/Region -->
                                    <?php if ($countries && !is_wp_error($countries)): ?>
                                    <div class="directory-section">
                                        <h3><?php _e('Country/Region', 'ccial'); ?></h3>
                                        <p><?php echo esc_html($countries[0]->name); ?></p>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <!-- Excerpt -->
                                    <?php if (has_excerpt()): ?>
                                    <div class="directory-section">
                                        <h3><?php _e('Description', 'ccial'); ?></h3>
                                        <p><?php the_excerpt(); ?></p>
                                    </div>
                                    <?php endif; ?>
                                    
                                </div>
                                
                            </article>
                            
                            <?php endwhile; ?>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.directory-entry-single {
    max-width: 800px;
    margin: 0 auto;
}

.entry-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #eee;
}

.entry-title {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    color: #333;
}

.entry-meta {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
}

.entry-meta span {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
}

.entry-type {
    background-color: #0073aa;
    color: white;
}

.entry-country {
    background-color: #f0f0f0;
    color: #333;
}

.entry-featured-image {
    margin: 2rem 0;
    border-radius: 8px;
    overflow: hidden;
}

.entry-featured-image img {
    width: 100%;
    height: auto;
    display: block;
}

.directory-information {
    margin-top: 2rem;
}

.directory-section {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background-color: #f9f9f9;
    border-radius: 8px;
    border-left: 4px solid #0073aa;
}

.directory-section h3 {
    margin-top: 0;
    margin-bottom: 1rem;
    color: #0073aa;
    font-size: 1.5rem;
}

.directory-section p {
    margin-bottom: 0.5rem;
}

@media (max-width: 768px) {
    .entry-title {
        font-size: 2rem;
    }
    
    .entry-meta {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .directory-section {
        padding: 1rem;
    }
}
</style>

<?php get_footer(); ?>
