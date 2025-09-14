<?php
/**
 * Single Academy Resource Template
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
                            
                            <article id="post-<?php the_ID(); ?>" <?php post_class('academy-resource-single'); ?>>
                                
                                <!-- Resource Header -->
                                <header class="resource-header">
                                    <h1 class="resource-title"><?php the_title(); ?></h1>
                                    
                                    <div class="resource-meta">
                                        <?php 
                                        $academic_categories = get_the_terms(get_the_ID(), 'portfolio_category');
                                        $competencies = get_the_terms(get_the_ID(), 'portfolio_skills');
                                        $topics = get_the_terms(get_the_ID(), 'portfolio_tags');
                                        ?>
                                        
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
                                </header>
                                
                                <!-- Featured Image -->
                                <?php if (has_post_thumbnail()): ?>
                                <div class="resource-featured-image">
                                    <?php the_post_thumbnail('large'); ?>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Resource Content -->
                                <div class="resource-content">
                                    <?php the_content(); ?>
                                </div>
                                
                                <!-- Academy Information -->
                                <div class="academy-information">
                                    
                                    <!-- Academic Category -->
                                    <?php if ($academic_categories && !is_wp_error($academic_categories)): ?>
                                    <div class="academy-section">
                                        <h3><?php _e('Academic Category', 'ccial'); ?></h3>
                                        <p><?php echo esc_html($academic_categories[0]->name); ?></p>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <!-- Competencies -->
                                    <?php if ($competencies && !is_wp_error($competencies)): ?>
                                    <div class="academy-section">
                                        <h3><?php _e('Competencies Developed', 'ccial'); ?></h3>
                                        <div class="competencies-list">
                                            <?php foreach ($competencies as $competency): ?>
                                                <span class="competency-tag"><?php echo esc_html($competency->name); ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <!-- Topics -->
                                    <?php if ($topics && !is_wp_error($topics)): ?>
                                    <div class="academy-section">
                                        <h3><?php _e('Related Topics', 'ccial'); ?></h3>
                                        <div class="topics-list">
                                            <?php foreach ($topics as $topic): ?>
                                                <span class="topic-tag"><?php echo esc_html($topic->name); ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <!-- Excerpt -->
                                    <?php if (has_excerpt()): ?>
                                    <div class="academy-section">
                                        <h3><?php _e('Description', 'ccial'); ?></h3>
                                        <p><?php the_excerpt(); ?></p>
                                    </div>
                                    <?php endif; ?>
                                    
                                </div>
                                
                                <!-- Navigation -->
                                <nav class="academy-navigation">
                                    <div class="nav-previous">
                                        <?php previous_post_link('%link', __('← Previous Resource', 'ccial')); ?>
                                    </div>
                                    <div class="nav-next">
                                        <?php next_post_link('%link', __('Next Resource →', 'ccial')); ?>
                                    </div>
                                </nav>
                                
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
.academy-resource-single {
    max-width: 900px;
    margin: 0 auto;
}

.resource-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #eee;
}

.resource-title {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    color: #333;
    font-weight: 700;
}

.resource-meta {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
}

.resource-meta span {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
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

.resource-featured-image {
    margin: 2rem 0;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.resource-featured-image img {
    width: 100%;
    height: auto;
    display: block;
}

.academy-information {
    margin-top: 2rem;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
}

.academy-section {
    padding: 1.5rem;
    background-color: #f9f9f9;
    border-radius: 8px;
    border-left: 4px solid #0073aa;
}

.academy-section h3 {
    margin-top: 0;
    margin-bottom: 1rem;
    color: #0073aa;
    font-size: 1.3rem;
    font-weight: 600;
}

.academy-section p {
    margin-bottom: 0.5rem;
    line-height: 1.6;
}

.competencies-list,
.topics-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.competency-tag,
.topic-tag {
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
}

.competency-tag {
    background-color: #28a745;
    color: white;
}

.topic-tag {
    background-color: #6c757d;
    color: white;
}

.academy-navigation {
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.academy-navigation a {
    padding: 0.75rem 1.5rem;
    background-color: #0073aa;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-weight: 500;
    transition: background-color 0.3s ease;
}

.academy-navigation a:hover {
    background-color: #005a87;
    color: white;
}

@media (max-width: 768px) {
    .resource-title {
        font-size: 2rem;
    }
    
    .resource-meta {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .academy-information {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .academy-section {
        padding: 1rem;
    }
    
    .academy-navigation {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>

<?php get_footer(); ?>
