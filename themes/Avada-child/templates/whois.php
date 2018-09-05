<?php
/**
 * Template Name: WHOIS
 * A full-width template.
 *
 * @package Avada
 * @subpackage Templates
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
  exit( 'Direct script access denied.' );
}
?>

<?php get_header(); ?>
<div id="content" class="full-width">
  <?php while ( have_posts() ) : the_post(); ?>
    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <?php echo wp_kses_post( fusion_render_rich_snippets_for_pages() ); ?>
      <?php avada_featured_images_for_pages(); ?>
      <div class="post-content">
        <?php the_content(); ?>
        <?php fusion_link_pages(); ?>
        <?php
          function getWhois($domain) 
          {
            $domain = strtolower($_REQUEST['domain']);
            $host = 'whois.appdetex.com';
            $fp = fsockopen($host, 43);
            if (!$fp) 
            {
              return array('success' => false,'message' => 'Sorry, the WHOIS service is not currently available.','error' => 27);
            }
            fwrite($fp, "$domain\r\n");
            stream_set_timeout($fp, 5);
              $resp = '';
              while (!feof($fp)) 
              {
                $resp .= fread($fp, 1024 * 1024);
            }
            return $resp;
          }
          echo '<div class="fusion-row" style="margin-top: 20px; margin-bottom: 20px;"><pre>';
          print_r(getWhois('$domain'));
          echo "</pre></div>";
        ?>
      </div>
      <?php if ( ! post_password_required( $post->ID ) ) : ?>
        <?php if ( Avada()->settings->get( 'comments_pages' ) ) : ?>
          <?php wp_reset_postdata(); ?>
          <?php comments_template(); ?>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  <?php endwhile; ?>
</div>
<?php get_footer();

/* Omit closing PHP tag to avoid "Headers already sent" issues. */
