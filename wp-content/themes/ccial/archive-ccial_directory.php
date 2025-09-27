<?php
/**
 * Directory Archive Template
 * 
 * @package CCI AL
 * @version 1.0.0
 * @since 1.0.0
 */

get_header(); ?>

<div id="main" class="clearfix">
    <div class="fusion-row">
        
        <!-- Directory Header -->
        <div class="directory-header">
            <h1 class="directory-title"><?php _e('Directory', 'ccial'); ?></h1>
            <p class="directory-description">
                <?php _e('Explore our network of National Associations and Camp Sites across Latin America.', 'ccial'); ?>
            </p>
        </div>
        
        <!-- Directory Filters -->
        <div class="directory-filters">
            <form method="get" class="filter-form">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="directory_type"><?php _e('Type:', 'ccial'); ?></label>
                        <select name="directory_type" id="directory_type">
                            <option value=""><?php _e('All Types', 'ccial'); ?></option>
                            <?php
                            $directory_types = get_terms(array(
                                'taxonomy' => 'directory_type',
                                'hide_empty' => false,
                            ));
                            
                            if ($directory_types && !is_wp_error($directory_types)) {
                                foreach ($directory_types as $type) {
                                    $selected = isset($_GET['directory_type']) && $_GET['directory_type'] === $type->slug ? 'selected' : '';
                                    echo '<option value="' . esc_attr($type->slug) . '" ' . $selected . '>' . esc_html($type->name) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="country_region"><?php _e('Country:', 'ccial'); ?></label>
                        <select name="country_region" id="country_region">
                            <option value=""><?php _e('All Countries', 'ccial'); ?></option>
                            <?php
                            $countries = get_terms(array(
                                'taxonomy' => 'country_region',
                                'hide_empty' => false,
                                'orderby' => 'name',
                            ));
                            
                            if ($countries && !is_wp_error($countries)) {
                                foreach ($countries as $country) {
                                    $selected = isset($_GET['country_region']) && $_GET['country_region'] === $country->slug ? 'selected' : '';
                                    echo '<option value="' . esc_attr($country->slug) . '" ' . $selected . '>' . esc_html($country->name) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <button type="submit" class="filter-submit"><?php _e('Filter', 'ccial'); ?></button>
                        <a href="<?php echo get_post_type_archive_link('ccial_directory'); ?>" class="filter-reset">
                            <?php _e('Reset', 'ccial'); ?>
                        </a>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Directory Entries -->
        <div class="directory-entries">
            <?php if (have_posts()): ?>
                
                <div class="directory-grid">
                    <?php while (have_posts()): the_post(); 
                        $directory_types = get_the_terms(get_the_ID(), 'directory_type');
                        $countries = get_the_terms(get_the_ID(), 'country_region');
                        $featured_image = get_the_post_thumbnail(get_the_ID(), 'medium');
                    ?>
                    
                    <article class="directory-entry">
                        
                        <?php if ($featured_image): ?>
                            <div class="entry-image">
                                <a href="<?php the_permalink(); ?>">
                                    <?php echo $featured_image; ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="entry-content">
                            <h2 class="entry-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            
                            <div class="entry-meta">
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
                            
                            <?php if (get_the_excerpt()): ?>
                                <div class="entry-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="entry-actions">
                                <a href="<?php the_permalink(); ?>" class="read-more">
                                    <?php _e('View Details', 'ccial'); ?>
                                </a>
                            </div>
                        </div>
                        
                    </article>
                    
                    <?php endwhile; ?>
                </div>
                
                <!-- Pagination -->
                <div class="directory-pagination">
                    <?php
                    echo paginate_links(array(
                        'prev_text' => __('Previous', 'ccial'),
                        'next_text' => __('Next', 'ccial'),
                        'mid_size' => 2,
                    ));
                    ?>
                </div>
                
            <?php else: ?>
                
                <div class="no-entries">
                    <h3><?php _e('No directory entries found', 'ccial'); ?></h3>
                    <p><?php _e('Try adjusting your filters or check back later for new entries.', 'ccial'); ?></p>
                </div>
                
            <?php endif; ?>
        </div>
        
    </div>
</div>

<style>
.directory-header {
    text-align: center;
    margin-bottom: 3rem;
    padding: 2rem 0;
    background: linear-gradient(135deg, #0073aa 0%, #005a87 100%);
    color: white;
    border-radius: 8px;
}

.directory-title {
    font-size: 3rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.directory-description {
    font-size: 1.2rem;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto;
}

.directory-filters {
    background-color: #f8f9fa;
    padding: 2rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.filter-form {
    max-width: 1000px;
    margin: 0 auto;
}

.filter-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-group label {
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #333;
}

.filter-group select {
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 1rem;
}

.filter-submit,
.filter-reset {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 4px;
    font-size: 1rem;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.filter-submit {
    background-color: #0073aa;
    color: white;
}

.filter-submit:hover {
    background-color: #005a87;
}

.filter-reset {
    background-color: #6c757d;
    color: white;
    margin-left: 0.5rem;
}

.filter-reset:hover {
    background-color: #545b62;
    color: white;
}

.directory-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.directory-entry {
    background-color: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.directory-entry:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.entry-image {
    height: 200px;
    overflow: hidden;
}

.entry-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.directory-entry:hover .entry-image img {
    transform: scale(1.05);
}

.entry-content {
    padding: 1.5rem;
}

.entry-title {
    margin: 0 0 1rem 0;
    font-size: 1.5rem;
}

.entry-title a {
    color: #333;
    text-decoration: none;
}

.entry-title a:hover {
    color: #0073aa;
}

.entry-meta {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.entry-meta span {
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
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

.entry-excerpt {
    margin-bottom: 1rem;
    color: #666;
    line-height: 1.6;
}

.entry-actions {
    text-align: right;
}

.read-more {
    display: inline-block;
    padding: 0.5rem 1rem;
    background-color: #0073aa;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-size: 0.9rem;
    transition: background-color 0.3s ease;
}

.read-more:hover {
    background-color: #005a87;
    color: white;
}

.directory-pagination {
    text-align: center;
    margin-top: 3rem;
}

.directory-pagination .page-numbers {
    display: inline-block;
    padding: 0.5rem 1rem;
    margin: 0 0.25rem;
    background-color: #f8f9fa;
    color: #0073aa;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

.directory-pagination .page-numbers:hover,
.directory-pagination .page-numbers.current {
    background-color: #0073aa;
    color: white;
}

.no-entries {
    text-align: center;
    padding: 3rem;
    background-color: #f8f9fa;
    border-radius: 8px;
}

.no-entries h3 {
    color: #666;
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .directory-title {
        font-size: 2rem;
    }
    
    .filter-row {
        grid-template-columns: 1fr;
    }
    
    .filter-group {
        margin-bottom: 1rem;
    }
    
    .directory-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php get_footer(); ?>
