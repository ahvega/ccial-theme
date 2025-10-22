<?php
/**
 * Academy Category Archive Template
 * 
 * @package CCI AL
 * @version 1.0.0
 * @since 1.0.0
 */

get_header(); ?>

<div id="main" class="clearfix">
    <div class="fusion-row">
        
        <!-- Category Header -->
        <div class="academy-category-header">
            <?php 
            $term = get_queried_object();
            $current_lang = get_locale();
            $is_spanish = (strpos($current_lang, 'es') === 0);
            ?>
            
            <h1 class="category-title">
                <?php 
                if ($is_spanish) {
                    echo sprintf(__('Academia - %s', 'ccial'), $term->name);
                } else {
                    echo sprintf(__('Academy - %s', 'ccial'), $term->name);
                }
                ?>
            </h1>
            
            <?php if ($term->description): ?>
                <p class="category-description">
                    <?php echo esc_html($term->description); ?>
                </p>
            <?php endif; ?>
            
            <div class="category-breadcrumb">
                <a href="<?php echo $is_spanish ? home_url('/academia/') : home_url('/academy/'); ?>">
                    <?php echo $is_spanish ? __('Academia', 'ccial') : __('Academy', 'ccial'); ?>
                </a>
                <span class="separator"> / </span>
                <span class="current"><?php echo esc_html($term->name); ?></span>
            </div>
        </div>
        
        <!-- Category Resources -->
        <div class="academy-category-resources">
            <?php if (have_posts()): ?>
                
                <div class="academy-grid">
                    <?php while (have_posts()): the_post(); 
                        $academic_categories = get_the_terms(get_the_ID(), 'portfolio_category');
                        $competencies = get_the_terms(get_the_ID(), 'portfolio_skills');
                        $topics = get_the_terms(get_the_ID(), 'portfolio_tags');
                        $featured_image = get_the_post_thumbnail(get_the_ID(), 'medium');
                    ?>
                    
                    <article class="academy-resource">
                        
                        <?php if ($featured_image): ?>
                            <div class="resource-image">
                                <a href="<?php the_permalink(); ?>">
                                    <?php echo $featured_image; ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="resource-content">
                            <h2 class="resource-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            
                            <div class="resource-meta">
                                <?php if ($academic_categories && !is_wp_error($academic_categories)): ?>
                                    <span class="resource-category">
                                        <?php echo esc_html($academic_categories[0]->name); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($competencies && !is_wp_error($competencies)): ?>
                                    <span class="resource-competency">
                                        <?php echo esc_html($competencies[0]->name); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <?php if (get_the_excerpt()): ?>
                                <div class="resource-excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($topics && !is_wp_error($topics)): ?>
                                <div class="resource-topics">
                                    <?php foreach (array_slice($topics, 0, 3) as $topic): ?>
                                        <span class="topic-tag"><?php echo esc_html($topic->name); ?></span>
                                    <?php endforeach; ?>
                                    <?php if (count($topics) > 3): ?>
                                        <span class="more-topics">+<?php echo count($topics) - 3; ?> more</span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="resource-actions">
                                <a href="<?php the_permalink(); ?>" class="read-more">
                                    <?php _e('View Details', 'ccial'); ?>
                                </a>
                            </div>
                        </div>
                        
                    </article>
                    
                    <?php endwhile; ?>
                </div>
                
                <!-- Pagination -->
                <div class="academy-pagination">
                    <?php
                    echo paginate_links(array(
                        'prev_text' => __('Previous', 'ccial'),
                        'next_text' => __('Next', 'ccial'),
                        'mid_size' => 2,
                    ));
                    ?>
                </div>
                
            <?php else: ?>
                
                <div class="no-resources">
                    <h3><?php _e('No resources found in this category', 'ccial'); ?></h3>
                    <p><?php _e('Try browsing other categories or check back later for new resources.', 'ccial'); ?></p>
                </div>
                
            <?php endif; ?>
        </div>
        
    </div>
</div>

<style>
.academy-category-header {
    text-align: center;
    margin-bottom: 3rem;
    padding: 3rem 2rem;
    background: linear-gradient(135deg, #0073aa 0%, #005a87 100%);
    color: white;
    border-radius: 12px;
}

.category-title {
    font-size: 3rem;
    margin-bottom: 1rem;
    font-weight: 700;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.category-description {
    font-size: 1.2rem;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto 1.5rem;
    line-height: 1.6;
}

.category-breadcrumb {
    font-size: 1rem;
    opacity: 0.8;
}

.category-breadcrumb a {
    color: white;
    text-decoration: none;
}

.category-breadcrumb a:hover {
    text-decoration: underline;
}

.category-breadcrumb .separator {
    margin: 0 0.5rem;
}

.category-breadcrumb .current {
    font-weight: 600;
}

.academy-category-resources {
    margin-bottom: 3rem;
}

.academy-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.academy-resource {
    background-color: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #e9ecef;
}

.academy-resource:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.15);
}

.resource-image {
    height: 220px;
    overflow: hidden;
    position: relative;
}

.resource-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.academy-resource:hover .resource-image img {
    transform: scale(1.05);
}

.resource-content {
    padding: 1.5rem;
}

.resource-title {
    margin: 0 0 1rem 0;
    font-size: 1.4rem;
    font-weight: 600;
    line-height: 1.3;
}

.resource-title a {
    color: #333;
    text-decoration: none;
}

.resource-title a:hover {
    color: #0073aa;
}

.resource-meta {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.resource-meta span {
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
}

.resource-category {
    background-color: #0073aa;
    color: white;
}

.resource-competency {
    background-color: #28a745;
    color: white;
}

.resource-excerpt {
    margin-bottom: 1rem;
    color: #666;
    line-height: 1.6;
    font-size: 0.95rem;
}

.resource-topics {
    margin-bottom: 1rem;
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.topic-tag {
    padding: 0.2rem 0.6rem;
    background-color: #6c757d;
    color: white;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

.more-topics {
    padding: 0.2rem 0.6rem;
    background-color: #f8f9fa;
    color: #6c757d;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
    border: 1px solid #e9ecef;
}

.resource-actions {
    text-align: right;
}

.read-more {
    display: inline-block;
    padding: 0.6rem 1.2rem;
    background-color: #0073aa;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 500;
    transition: background-color 0.3s ease;
}

.read-more:hover {
    background-color: #005a87;
    color: white;
}

.academy-pagination {
    text-align: center;
    margin-top: 3rem;
}

.academy-pagination .page-numbers {
    display: inline-block;
    padding: 0.6rem 1.2rem;
    margin: 0 0.25rem;
    background-color: #f8f9fa;
    color: #0073aa;
    text-decoration: none;
    border-radius: 6px;
    transition: background-color 0.3s ease;
    font-weight: 500;
}

.academy-pagination .page-numbers:hover,
.academy-pagination .page-numbers.current {
    background-color: #0073aa;
    color: white;
}

.no-resources {
    text-align: center;
    padding: 4rem 2rem;
    background-color: #f8f9fa;
    border-radius: 12px;
    border: 1px solid #e9ecef;
}

.no-resources h3 {
    color: #666;
    margin-bottom: 1rem;
    font-size: 1.5rem;
}

.no-resources p {
    color: #888;
    font-size: 1.1rem;
}

@media (max-width: 768px) {
    .category-title {
        font-size: 2.5rem;
    }
    
    .category-description {
        font-size: 1.1rem;
    }
    
    .academy-grid {
        grid-template-columns: 1fr;
    }
    
    .academy-category-header {
        padding: 2rem 1rem;
    }
}
</style>

<?php get_footer(); ?>
