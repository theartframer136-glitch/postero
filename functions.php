<?php
$theme          = wp_get_theme('postero');
define( 'POSTERO_VERSION', $theme['Version'] );
if ( ! isset( $content_width ) ) $content_width = 900;

require_once get_theme_file_path('inc/class-tgm-plugin-activation.php');
require_once get_theme_file_path('inc/class-main.php');
require_once get_theme_file_path('inc/functions.php');
require_once get_theme_file_path('inc/template-hooks.php');
require_once get_theme_file_path('inc/template-functions.php');

require_once get_theme_file_path('inc/merlin/vendor/autoload.php');
require_once get_theme_file_path('inc/merlin/class-merlin.php');
require_once get_theme_file_path('inc/merlin-config.php');

require_once get_theme_file_path('inc/class-customize.php');

if (postero_is_woocommerce_activated()) {
    require_once get_theme_file_path('inc/woocommerce/class-woocommerce-shordcode.php');
    require_once get_theme_file_path('inc/woocommerce/class-woocommerce.php');

    require_once get_theme_file_path('inc/woocommerce/class-woocommerce-adjacent-products.php');

    require_once get_theme_file_path('inc/woocommerce/woocommerce-functions.php');
    require_once get_theme_file_path('inc/woocommerce/woocommerce-template-functions.php');
    require_once get_theme_file_path('inc/woocommerce/woocommerce-template-hooks.php');
    require_once get_theme_file_path('inc/woocommerce/template-hooks.php');
    require_once get_theme_file_path('inc/woocommerce/class-woocommerce-settings.php');
    require_once get_theme_file_path('inc/merlin/includes/class-wc-widget-product-themes.php');
    require_once get_theme_file_path('inc/merlin/includes/product-360-view.php');
    require_once get_theme_file_path('inc/merlin/includes/product-technical-specs.php');
    require_once get_theme_file_path('inc/woocommerce/class-woocommerce-bought-together.php');
    require_once get_theme_file_path('inc/woocommerce/class-wpc-variation-swatches.php');
    require_once get_theme_file_path('inc/woocommerce/class-woocommerce-review-images.php');

    if(postero_is_mas_woocommerce_brands_activated()) {
        require get_theme_file_path('inc/woocommerce/class-woocommerce-artists.php');
    }

    if (class_exists('WeDevs_Dokan')) {
        require get_theme_file_path('inc/dokan/class-dokan.php');
        require get_theme_file_path('inc/dokan/dokan-template-functions.php');
        require get_theme_file_path('inc/dokan/dokan-template-hooks.php');
    }
}

if (postero_is_elementor_activated()) {
    require_once get_theme_file_path('inc/elementor/functions-elementor.php');
    require_once get_theme_file_path('inc/elementor/class-elementor.php');
    require_once get_theme_file_path('inc/megamenu/megamenu.php');
    require_once get_theme_file_path('inc/elementor/section-parallax.php');
    if (defined('ELEMENTOR_PRO_VERSION')) {
        require_once get_theme_file_path('inc/elementor/class-elementor-pro.php');
    }else {
        require_once get_theme_file_path('inc/elementor/class-fix-elementor.php');
    }

    if (function_exists('hfe_init')) {
        require_once get_theme_file_path('inc/header-footer-elementor/class-hfe.php');
        require_once get_theme_file_path('inc/merlin/includes/breadcrumb.php');
    }

    if (postero_is_woocommerce_activated()) {
        require_once get_theme_file_path('inc/elementor/elementor-control/class-elementor-control.php');
    }

}

if (!is_user_logged_in()) {
    require_once get_theme_file_path('inc/modules/class-login.php');
}else {
    require_once get_theme_file_path('inc/modules/media-custom-field.php');
}



// fetch the subcategories on the mobile device 
 add_action('wp_ajax_load_subcategories', 'load_subcategories');
add_action('wp_ajax_nopriv_load_subcategories', 'load_subcategories');

function load_subcategories(){

$parent_slug = $_POST['parent'];

/* GET PARENT CATEGORY */

$parent_term = get_term_by(
    'slug',
    $parent_slug,
    'product_cat'
);

if(!$parent_term){
    wp_die();
}


/* GET SUBCATEGORIES */

$args = array(
    'taxonomy' => 'product_cat',
    'parent' => $parent_term->term_id,
    'hide_empty' => false
);

$subcats = get_terms($args);


/* LOOP SUBCATEGORIES */

foreach($subcats as $subcat){

/* GET IMAGE ID */

$thumbnail_id = get_term_meta(
    $subcat->term_id,
    'thumbnail_id',
    true
);


/* GET IMAGE URL PROPERLY */

if($thumbnail_id){

$image_data = wp_get_attachment_image_src(
    $thumbnail_id,
    'thumbnail'
);

$image_url = $image_data[0];

}else{

/* DEFAULT IMAGE */

$image_url = wc_placeholder_img_src();

}

?>
<div class="sub-cat"
     data-subcat="<?php echo esc_attr($subcat->slug); ?>">

<img 
src="<?php echo esc_url($image_url); ?>" 
alt="<?php echo esc_attr($subcat->name); ?>"
>

<span>
<?php echo esc_html($subcat->name); ?>
</span>

</div>

<!-- <div class="sub-cat">

<img 
src="<?php //echo esc_url($image_url); ?>" 
alt="<?php //echo esc_attr($subcat->name); ?>"
>

<span><?php //echo esc_html($subcat->name); ?></span>

</div> -->

<?php

}

wp_die();

}


// product card dynamic for mobile
add_action('wp_ajax_load_products', 'load_products');
add_action('wp_ajax_nopriv_load_products', 'load_products');

function load_products(){

$subcategory_slug = $_POST['subcategory'];

$args = array(
'post_type' => 'product',
'posts_per_page' => 12,

'tax_query' => array(
array(
'taxonomy' => 'product_cat',
'field' => 'slug',
'terms' => $subcategory_slug
)
)
);

$query = new WP_Query($args);

if($query->have_posts()){

while($query->have_posts()){

$query->the_post();

global $product;


/* PRICE */

$price = $product->get_price();
$regular_price = $product->get_regular_price();

/* DISCOUNT */

if($regular_price){

$discount = round(
(($regular_price - $price) / $regular_price) * 100
);

}else{

$discount = 0;

}

/* RATING */

$rating = $product->get_average_rating();
$rating_count = $product->get_rating_count();

/* IMAGE */

// $image = $product->get_image();
/* MAIN IMAGE */
$main_image_id = $product->get_image_id();

$main_image = wp_get_attachment_image(
    $main_image_id,
    'woocommerce_thumbnail',
    false,
    array('class' => 'main-img')
);

/* FIRST GALLERY IMAGE */

$gallery_ids = $product->get_gallery_image_ids();

$hover_image = '';
$has_gallery = false;

if ( ! empty($gallery_ids) ) {

    $hover_image = wp_get_attachment_image(
        $gallery_ids[0],
        'woocommerce_thumbnail',
        false,
        array('class' => 'hover-img')
    );

    $has_gallery = true;

}

/* DESCRIPTION */

$desc = wp_trim_words(
$product->get_short_description(),
10
);

?>

<div class="product-card">

<!-- IMAGE -->

<div class="product-image">

<a href="<?php the_permalink(); ?>">

<div class="image-wrapper">
<?php echo $main_image; ?>

<?php 

if($has_gallery){

    echo $hover_image;

}else{

    echo '<div class="no-gallery-text">
            Image is not present
          </div>';

}

?>

</div>

</a>
<!-- Dynamic SALE Ribbon -->
<?php if ( $product->is_on_sale() ) : ?>
<div class="sale-ribbon">
Sale!
</div>
<?php endif; ?>
<!-- WISHLIST -->

 
<div class="wishlist-btn">

<a href="?add_to_wishlist=<?php echo $product->get_id(); ?>"
   class="add_to_wishlist single_add_to_wishlist custom-wishlist-btn"
   data-product-id="<?php echo $product->get_id(); ?>"
   data-product-type="simple"
   rel="nofollow">

<i class="fas fa-heart"></i>

</a>

</div>

</div>



<!-- INFO -->

<div class="product-info">

<h3 class="product-title">

<a href="<?php echo esc_url( get_permalink() ); ?>">

<?php the_title(); ?>

</a>

</h3>


<!-- RATING -->

<div class="rating">

<?php echo wc_get_rating_html($rating); ?>

<span class="rating-count">

(<?php echo $rating_count; ?>)

</span>
<!-- DIGITAL DOWNLOAD BUTTON -->
<a href="<?php echo esc_url( get_permalink() ); ?>"
class="digital-download-btn">
<i class="far fa-arrow-alt-circle-down"></i>
Digital Download
</a>
</div>


<!-- PRICE -->

<div class="price-section">

<span class="price">

<?php echo wc_price($price); ?>

</span>

<?php if($regular_price){ ?>

<span class="old-price">

<?php echo wc_price($regular_price); ?>

</span>

<span class="discount">

(<?php echo $discount; ?>% off)

</span>

<?php } ?>

</div>


<!-- DESCRIPTION -->

<p class="desc">

<?php echo $desc; ?>

</p>


<!-- ACTION BUTTONS -->

<div class="product-actions">

<a href="?add-to-cart=<?php echo $product->get_id(); ?>"

class="add-cart">

<i class="fas fa-shopping-cart"></i>
<span>Add to Cart</span>


</a>

<a href="#"

class="quick-view-btn"

data-product-id="<?php echo $product->get_id(); ?>">

<i class="fas fa-eye"></i>

<span>Quick View</span>

</a>
	

</div>

</div>

</div>

<?php

}

}

wp_die();

}



/* YouTube Playlist Circle Slider */

function youtube_playlist_circle_slider() {

$playlists = array(

array(
'id' => 'PLGBybcBXIw_lQljzmXChlyGvFksK2x9wD',
'title' => 'Lord Buddha'
),

array(
'id' => 'PLGBybcBXIw_nI5uDT8qSHIIiemunmYHYu',
'title' => 'Lord venkateswara'
),

array(
'id' => 'PLGBybcBXIw_kMhIqVF5sLxpY-QyJGGS7O',
'title' => 'Lord Ganesha'
),

array(
'id' => 'PLGBybcBXIw_m8PI0C9rRK-2yDdyMKwc34',
'title' => 'Goddess Durga'
),

array(
'id' => 'PLGBybcBXIw_nWQpJSzp9axUi2EAVWHOGT',
'title' => 'Lord Shiva'
),
array(
'id' => 'PLGBybcBXIw_nyhjQz7jRPAmtyyNLNiSPH',
'title' => 'Lord Krishna'
),
array(
'id' => 'PLGBybcBXIw_n5y5yNLGIR_WAE16v0pbdF',
'title' => 'Radha Krishna'
),
array(
'id' => 'PLGBybcBXIw_nAQGoKqF2eBOgEwbec1QKb',
'title' => '7 horses'
)

);

ob_start();
?>

<div class="circle-gallery-slider">

<?php foreach($playlists as $playlist){ ?>

<div class="circle-item video-circle">

<iframe
src="https://www.youtube.com/embed/videoseries?list=<?php echo $playlist['id']; ?>&autoplay=1&mute=1&loop=1&controls=0"
frameborder="0"
allow="autoplay"
allowfullscreen>
</iframe>
</div>
<?php } ?>

</div>

<?php

return ob_get_clean();

}

add_shortcode(
'youtube_circle_slider',
'youtube_playlist_circle_slider'
);
/* Drag Scroll JS for Circle Slider */

function circle_slider_drag_script() {
?>

<script>

document.addEventListener("DOMContentLoaded", function () {

  const slider =
    document.querySelector(".circle-gallery-slider");

  if (!slider) return;

  let isDown = false;
  let startX;
  let scrollLeft;

  slider.addEventListener("mousedown", (e) => {

    isDown = true;
    slider.classList.add("dragging");

    startX = e.pageX - slider.offsetLeft;
    scrollLeft = slider.scrollLeft;

  });

  slider.addEventListener("mouseleave", () => {

    isDown = false;
    slider.classList.remove("dragging");

  });

  slider.addEventListener("mouseup", () => {

    isDown = false;
    slider.classList.remove("dragging");

  });

  slider.addEventListener("mousemove", (e) => {

    if (!isDown) return;

    e.preventDefault();

    const x = e.pageX - slider.offsetLeft;
    const walk = (x - startX) * 1.5;

    slider.scrollLeft = scrollLeft - walk;

  });

});

</script>

<?php
}

add_action(
'wp_footer',
'circle_slider_drag_script'
);
/* ===================================================
   CUSTOM PRODUCT AUTOPLAY SLIDER
   PURE JS â€” SINGLE ROW FIXED
=================================================== */

function custom_woocommerce_product_slider() {

ob_start();

?>

<style>

/* WRAPPER */

.custom-product-slider {
overflow: hidden;
position: relative;
width: 100%;
}

/* TRACK */

.custom-product-track {

display: flex;
flex-wrap: nowrap; /* â­ prevents multi-row */

gap: 25px;

overflow: hidden;

scroll-behavior: smooth;

}

/* SLIDES */

.product-slide {

flex: 0 0 calc(25% - 18px);
max-width: calc(25% - 18px);

}

/* TABLET */

@media (max-width:1024px){

.product-slide {

flex: 0 0 calc(33.33% - 18px);
max-width: calc(33.33% - 18px);

}

}

/* MOBILE */

@media (max-width:768px){

.product-slide {

flex: 0 0 calc(50% - 18px);
max-width: calc(50% - 18px);

}

}

@media (max-width:480px){

.product-slide {

flex: 0 0 100%;
max-width: 100%;

}

}

/* IMAGE HOVER */

.image-wrapper {
position: relative;
overflow: hidden;
}

.image-wrapper img {
width: 100%;
transition: opacity 0.4s ease;
}

.image-wrapper img.hover-image {

position: absolute;
top: 0;
left: 0;
opacity: 0;

}

.product-card:hover img.hover-image {
opacity: 1;
}

.product-card:hover img:first-child {
opacity: 0;
}
/* IMAGE WRAPPER */

.image-wrapper {
    position: relative;
    overflow: hidden;
}

/* MAIN IMAGE */

.image-wrapper img:first-child {
    position: relative;
    z-index: 1;
}

/* HOVER IMAGE */

.hover-image {

    position: absolute;
    top: 0;
    left: 0;

    width: 100%;
    height: 100%;

    object-fit: cover;

    opacity: 0;

    transition: opacity 0.3s ease;

    z-index: 2;
}

/* SHOW HOVER IMAGE */

.product-image:hover .hover-image {
    opacity: 1;
}

/* HIDE MAIN IMAGE */

.product-image:hover img:first-child {
    opacity: 0;
}

/* FALLBACK TEXT */

.no-gallery-text {

    position: absolute;

    top: 0;
    left: 0;
    right: 0;
    bottom: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    background: rgba(0,0,0,0.6);

    color: #fff;

    font-size: 13px;

    text-align: center;

    opacity: 0;

    z-index: 3;

    transition: opacity 0.3s ease;

}

/* SHOW TEXT */

.product-image:hover .no-gallery-text {
    opacity: 1;
}
	/* Quick View */
.quick-view-btn {
    background: #000;
    color: #fff;
    padding: 12px 18px;
    border-radius: 10px;
    text-align: center;
    text-decoration: none;
    font-size: 14px;
    white-space: nowrap;
    gap:5px;
    display: flex;
    align-items: center;
    justify-content: center;

    flex: 1; /* Makes both buttons balanced */
}
</style>



<div class="custom-product-slider">

<div class="custom-product-track">

<?php

$args = array(

'post_type' => 'product',

'posts_per_page' => 12,

'orderby' => 'date',
'order' => 'DESC',

'post_status' => 'publish',

'tax_query' => array(
array(
'taxonomy' => 'product_cat',
'field' => 'slug',

'terms' => array(

'banners-signage',
'backdrops',
'banner-stands',
'fabric-cloth-banners',
'fence-banners',
'vinyl-banners'

),

'operator' => 'IN',

),
),

);

$query = new WP_Query($args);

if ($query->have_posts()) :

while ($query->have_posts()) :
$query->the_post();

global $product;


/* IMAGES */

$main_image = get_the_post_thumbnail(
get_the_ID(),
'woocommerce_thumbnail'
);

$gallery_ids = $product->get_gallery_image_ids();

$hover_image = '';
$has_gallery = false;

if (!empty($gallery_ids)) {

    $hover_image = wp_get_attachment_image(
        $gallery_ids[0],
        'woocommerce_thumbnail',
        false,
        array('class'=>'hover-image')
    );

    $has_gallery = true;
}

/* PRICE */

$price = $product->get_price();
$regular_price = $product->get_regular_price();

$discount = '';

if ($regular_price) {

$discount = round(
(($regular_price - $price) / $regular_price) * 100
);

}


/* RATING */

$rating = $product->get_average_rating();
$rating_count = $product->get_rating_count();


/* DESCRIPTION */

$desc = wp_trim_words(
get_the_excerpt(),
12
);

?>



<div class="product-slide">

<div class="product-card">


<!-- IMAGE -->

<div class="product-image">

<a href="<?php the_permalink(); ?>">

<div class="image-wrapper">

<?php echo $main_image; ?>

<?php 

if($has_gallery){

    echo $hover_image;

}else{

    echo '<div class="no-gallery-text">
            Image is not present
          </div>';

}

?>

</div>

</a>

<?php if ( $product->is_on_sale() ) : ?>
<div class="sale-ribbon">Sale!</div>
<?php endif; ?>

<div class="wishlist-btn">

<a href="<?php echo esc_url(
add_query_arg(
'add-to-wishlist',
$product->get_id()
)
); ?>" 
class="wishlist-icon">

<i class="fas fa-heart"></i>

</a>

</div>

</div>



<!-- INFO -->

<div class="product-info">


<h3 class="product-title">

<a href="<?php echo esc_url(get_permalink()); ?>">

<?php the_title(); ?>

</a>

</h3>



<div class="rating">

<?php echo wc_get_rating_html($rating); ?>

<span class="rating-count">

(<?php echo $rating_count; ?>)

</span>


<a href="<?php echo esc_url(get_permalink()); ?>"

class="digital-download-btn">

<i class="far fa-arrow-alt-circle-down"></i>

Digital Download

</a>

</div>



<div class="price-section">

<span class="price">

<?php echo wc_price($price); ?>

</span>


<?php if($regular_price){ ?>

<span class="old-price">

<?php echo wc_price($regular_price); ?>

</span>


<span class="discount">

(<?php echo $discount; ?>% off)

</span>

<?php } ?>

</div>



<p class="desc">

<?php echo $desc; ?>

</p>



<div class="product-actions">

<a href="?add-to-cart=<?php echo $product->get_id(); ?>"

class="add-cart">

<i class="fas fa-shopping-cart"></i>

<span>Add to Cart</span>

</a>


<a href="#"

class="quick-view-btn"

data-product-id="<?php echo $product->get_id(); ?>">

<i class="fas fa-eye"></i>

<span>Quick View</span>

</a>


</div>

</div>

</div>

</div>



<?php

endwhile;

wp_reset_postdata();

endif;

?>

</div>

</div>



<script>

document.addEventListener("DOMContentLoaded", function(){

const slider = document.querySelector(".custom-product-track");

if(!slider) return;

let scrollAmount = 0;
let slideWidth = 0;

function initSlider(){

const slides = document.querySelectorAll(".product-slide");

if(slides.length === 0) return;

slideWidth = slides[0].offsetWidth + 25;

}

function autoSlide(){

scrollAmount += slideWidth;

if(scrollAmount >= slider.scrollWidth - slider.clientWidth){

scrollAmount = 0;

slider.scrollTo({
left: 0,
behavior: "auto"
});

return;

}

slider.scrollTo({
left: scrollAmount,
behavior: "smooth"
});

}

initSlider();

setInterval(autoSlide, 3000);

window.addEventListener("resize", initSlider);

});

</script>

<?php

return ob_get_clean();

}

add_shortcode(
'woocommerce_product_slider',
'custom_woocommerce_product_slider'
);
/* ===================================================
   RANDOM PRODUCT IMAGE GRID
   RANDOM PRODUCTS + HOVER GALLERY
=================================================== */
function load_fancybox_assets() {

    wp_enqueue_style(
        'fancybox-css',
        'https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css'
    );

    wp_enqueue_script(
        'fancybox-js',
        'https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js',
        array(),
        null,
        true
    );

}

add_action('wp_enqueue_scripts', 'load_fancybox_assets');
function random_product_image_grid() {

ob_start();

?>

<style>

/* GRID WRAPPER */

.random-product-grid {

display: grid;

grid-template-columns: repeat(4, 1fr);

gap: 30px;

margin:  0;

}

/* RESPONSIVE */

@media (max-width:1024px){

.random-product-grid {

grid-template-columns: repeat(3, 1fr);

}

}

@media (max-width:768px){

.random-product-grid {

grid-template-columns: repeat(2, 1fr);

}

}

@media (max-width:480px){
.random-product-grid {
grid-template-columns: repeat(2, 1fr);
gap: 15px; /* optional: smaller spacing on mobile */
 }
}

/* IMAGE CARD */

.random-product-item {

position: relative;

overflow: hidden;
}

/* IMAGE WRAPPER */

.random-product-item .image-wrapper {

position: relative;

overflow: hidden;

}

/* MAIN IMAGE */

.random-product-item img {

width: 100%;

height: 300px;

object-fit: cover;

transition: opacity 0.4s ease,
transform 0.4s ease;

}

/* HOVER IMAGE */

.random-product-item img.hover-image {

position: absolute;

top: 0;
left: 0;

opacity: 0;

}

/* HOVER EFFECT */

.random-product-item:hover img.hover-image {

opacity: 1;

}

.random-product-item:hover img:first-child {

opacity: 0;

}

.random-product-item:hover img {

transform: scale(1.05);

}
	/* NO GALLERY TEXT */

.no-gallery-text {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    background: rgba(0,0,0,0.6);
    color: #fff;

    font-size: 14px;
    font-weight: 500;

    opacity: 0;
    transition: opacity 0.3s ease;

    text-align: center;
    padding: 10px;
}

/* SHOW TEXT ON HOVER */

.random-product-item:hover .no-gallery-text {
    opacity: 1;
}

</style>



<div class="random-product-grid">

<?php

$args = array(

'post_type' => 'product',

'posts_per_page' => 8,

'post_status' => 'publish',

'tax_query' => array(
    array(
        'taxonomy' => 'product_cat',
        'field'    => 'slug',
        'terms'    => 'personalised-prints',
    ),
),

);

$query = new WP_Query($args);

if ($query->have_posts()) :

while ($query->have_posts()) :
$query->the_post();

global $product;


/* MAIN IMAGE */

$main_image = get_the_post_thumbnail(
get_the_ID(),
'large'
);


/* GALLERY IMAGE */

$gallery_ids = $product->get_gallery_image_ids();

$hover_image = '';
$has_gallery = false;

if (!empty($gallery_ids)) {

    $hover_image = wp_get_attachment_image(
        $gallery_ids[0],
        'large',
        false,
        array(
            'class'=>'hover-image',
            'loading'=>'lazy'
        )
    );

    $has_gallery = true;
}

?>


<div class="random-product-item">

<div class="image-wrapper">

<a href="<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>" 
   data-fancybox="product-<?php the_ID(); ?>">

<?php echo $main_image; ?>

<?php 

if($has_gallery){

    echo $hover_image;

}else{

    echo '<div class="no-gallery-text">
            Gallery image is not present
          </div>';

}

?>

</a>

<?php

/* Hidden gallery images for Fancybox */

if (!empty($gallery_ids)) {

    foreach ($gallery_ids as $gallery_id) {

        $gallery_url = wp_get_attachment_url($gallery_id);

        echo '<a href="'.$gallery_url.'" 
                data-fancybox="product-'.get_the_ID().'" 
                style="display:none;">
              </a>';

    }

}

?>

</div>

</div>


<?php

endwhile;

wp_reset_postdata();

endif;

?>

</div>

<?php

return ob_get_clean();

}

add_shortcode(
'random_product_grid',
'random_product_image_grid'
);
// Trending Now Modern Card Slider (All-in-One)

function trending_now_full_card_slider() {

ob_start();

$args = array(
'post_type' => 'product',
'posts_per_page' => 20,
'post_status' => 'publish'
);

$query = new WP_Query($args);
?>

<style>

/* ===== Slider Wrapper ===== */

.trending-wrapper {
position: relative;
display: flex;
align-items: center;
width: 100%;
}

/* ===== Slider ===== */

.trending-slider {
display: flex;
gap: 25px;
overflow-x: auto;
scroll-behavior: smooth;
padding: 10px;
}

.trending-slider::-webkit-scrollbar {
display: none;
}

/* ===== Card ===== */

.trending-card {

flex: 0 0 calc(25% - 19px); /* 4 cards */

background: #fff;
border-radius: 18px;
overflow: hidden;
box-shadow: 0 8px 25px rgba(0,0,0,0.08);
transition: 0.3s;

}

.trending-card:hover {
transform: translateY(-6px);
}

/* ===== Image ===== */

.trending-image {

position: relative;
overflow: hidden;
}

.trending-image img {

width: 100%;
height: 250px;
object-fit: cover;
transition: 0.4s;
}

/* Hover Gallery */

/* MAIN IMAGE */

.main-img {
position: relative;
z-index: 1;
transition: opacity 0.3s ease;
}

/* HOVER IMAGE */

.hover-img {

position: absolute;
top: 0;
left: 0;

width: 100%;
height: 100%;

object-fit: cover;

opacity: 0;

z-index: 2;

transition: opacity 0.3s ease;

}

/* SHOW HOVER */

.trending-card:hover .hover-img {
opacity: 1;
}

/* HIDE MAIN */

.trending-card:hover .main-img {
opacity: 0;
}

/* Wishlist */

.wishlist-icon {

position: absolute;
top: 12px;
right: 12px;
background: #fff;
width: 36px;
height: 36px;
border-radius: 50%;
display: flex;
align-items: center;
justify-content: center;
font-size: 18px;
box-shadow: 0 3px 12px rgba(0,0,0,0.15);
z-index: 2;
}

/* Content */

.trending-content {
padding: 16px;
}

/* Title */

.trending-title {

font-size: 15px;
font-weight: 500;
margin-bottom: 8px;
line-height: 1.4;
font-family: "Georgia", Sans-serif !important;
}

/* Label */

.digital-label {

font-size: 13px;
color: #555;
margin-bottom: 8px;
}

/* Price */

.trending-price {

font-size: 18px;
font-weight: bold;
margin-bottom: 10px;
}

/* Description */

.trending-desc {

font-size: 13px;
color: #777;
margin-bottom: 14px;
}

/* Buttons */
/* Container */
.trending-buttons {
    display: flex;
    gap: 10px;
}

/* BOTH buttons - unified base */
.trending-buttons .add-cart-btn,
.trending-buttons .quick-view-btn {
    flex: 1; /* equal width */
    display: flex;
    align-items: center;
    justify-content: center;
    height: 46px; /* same height */
    padding: 0 12px; /* remove uneven vertical padding */
    border-radius: 10px;
    font-size: 14px;
    text-decoration: none;
    white-space: nowrap;
}

/* Add to Cart */
.add-cart-btn {
    background: #8b6a2b;
    color: #fff;
}

/* Quick View */
.quick-view-btn {
    background: #000;
    color: #fff;
}

/* NO GALLERY TEXT */

.no-gallery-text {

position: absolute;
top: 0;
left: 0;
right: 0;
bottom: 0;

display: flex;
align-items: center;
justify-content: center;

background: rgba(0,0,0,0.6);
color: #fff;

font-size: 13px;
text-align: center;

opacity: 0;
transition: 0.3s;

z-index: 3;
}

/* SHOW TEXT */

.trending-card:hover .no-gallery-text {
opacity: 1;
}
/* ===== Tablet â€” 2 Cards ===== */

@media (max-width:1024px){

.trending-card {

flex: 0 0 calc(33.33% - 17px); /* 3 cards */

}

.trending-image img {

height: 220px;

}

}

/* ===== Mobile â€” 1 Card ===== */

@media (max-width:768px){

.trending-card {

flex: 0 0 100%; /* 1 card */

}

.trending-image img {

height: 200px;

}

}

/* ===== Small Mobile ===== */

@media (max-width:480px){

.trending-card {

flex: 0 0 100%;

}

.trending-image img {

height: 180px;

}

}
/* Hide default wishlist button ONLY inside your custom card */

.custom-product-card .woosw-btn-wrap,
.custom-product-card .woosw-btn-wrap * {

    display: none !important;

}

/* Keep YOUR custom icon visible */

.custom-product-card .wishlist-icon {

    display: inline-flex !important;

}
@media (max-width: 820px) {
    .trending-buttons .add-cart-btn,
    .trending-buttons .quick-view-btn {
        height: 44px;
        font-size: 13px;
        padding: 0 10px;
    }
}
</style>

<div class="trending-wrapper">



<div class="trending-slider">

<?php
if ($query->have_posts()) :
while ($query->have_posts()) : $query->the_post();

$product = wc_get_product(get_the_ID());

$featured_id = get_post_thumbnail_id();
$gallery_ids = $product->get_gallery_image_ids();

$featured_img =
wp_get_attachment_image_url(
$featured_id,
'large'
);

$has_gallery = !empty($gallery_ids);

$gallery_img = '';

if($has_gallery){

    $gallery_img =
    wp_get_attachment_image_url(
        $gallery_ids[0],
        'large'
    );

}

$featured_img =
wp_get_attachment_image_url(
$featured_id,
'large'
);

$has_gallery = !empty($gallery_ids);

$gallery_img = '';

if($has_gallery){

$gallery_img =
wp_get_attachment_image_url(
$gallery_ids[0],
'large'
);

}$regular_price = '';
$sale_price = '';
$discount = '';

if ( $product->is_type('variable') ) {

    $regular_price = $product->get_variation_regular_price('max', true);
    $sale_price    = $product->get_variation_sale_price('min', true);

} else {

    $regular_price = $product->get_regular_price();
    $sale_price    = $product->get_sale_price();

}

/* If no sale price */

if ( !$sale_price ) {
    $sale_price = $regular_price;
}

/* Calculate Discount */

if ( $sale_price && $regular_price && $sale_price < $regular_price ) {

    $discount = round(
        (($regular_price - $sale_price) / $regular_price) * 100
    );

}
	/* RATING */

$rating = $product->get_average_rating();
$rating_count = $product->get_rating_count();
?>

<div class="trending-card">

<div class="trending-image">
<div class="custom-product-card">

    <!-- your wishlist icon -->
    <div class="wishlist-btn">

<a href="<?php echo esc_url(
add_query_arg(
'add-to-wishlist',
$product->get_id()
)
); ?>" 
class="wishlist-icon">

<i class="fas fa-heart"></i>

</a>

</div>

    <!-- rest of your card -->

</div>

<a href="<?php the_permalink(); ?>">

<img class="main-img"
src="<?php echo esc_url($featured_img); ?>">

<?php if($has_gallery){ ?>

<img class="hover-img"
src="<?php echo esc_url($gallery_img); ?>">

<?php } else { ?>

<div class="no-gallery-text">
No gallery image is present
</div>

<?php } ?>

</a>

</div>


<div class="trending-content">

<h3 class="trending-title">
<a href="<?php the_permalink(); ?>">
<?php the_title(); ?>
</a>
</h3>


<div class="rating">

<?php if ( $rating_count > 0 ) : ?>

<?php echo wc_get_rating_html($rating); ?>

<span class="rating-count">

(<?php echo $rating_count; ?>)

</span>

<?php endif; ?>

<a href="<?php echo esc_url(get_permalink()); ?>"
class="digital-download-btn">

<i class="far fa-arrow-alt-circle-down"></i>

Digital Download

</a>

</div>

<div class="price-section">

<span class="price">

<?php echo wc_price($sale_price); ?>

</span>

<?php if ( $discount ) : ?>

<span class="old-price">

<?php echo wc_price($regular_price); ?>

</span>

<span class="discount">

(<?php echo $discount; ?>% off)

</span>

<?php endif; ?>

</div>

<p class="trending-desc">
<?php echo wp_trim_words(
get_the_excerpt(),
12
); ?>
</p>

<div class="trending-buttons">

<a href="?add-to-cart=<?php
echo $product->get_id();
?>"
class="add-cart-btn">
<i class="fas fa-shopping-cart"></i>
 Add to Cart

</a>

<a href="#"
class="quick-view-btn"
data-product-id="<?php echo $product->get_id(); ?>">

<i class="fas fa-eye"></i>
<span>Quick View</span>

</a>
</div>

</div>

</div>

<?php
endwhile;
wp_reset_postdata();
endif;
?>

</div>



</div>

<script>

document.addEventListener("DOMContentLoaded", function(){

const slider = document.querySelector(".trending-slider");

if(!slider) return;

let scrollAmount = 0;
let slideWidth = 0;

function initSlider(){

const slides = document.querySelectorAll(".trending-card");

if(slides.length === 0) return;

/* card width + gap */

slideWidth = slides[0].offsetWidth + 25;

}

/* Auto Slide */

function autoSlide(){

scrollAmount += slideWidth;

if(scrollAmount >= slider.scrollWidth - slider.clientWidth){

scrollAmount = 0;

slider.scrollTo({
left: 0,
behavior: "auto"
});

return;

}

slider.scrollTo({
left: scrollAmount,
behavior: "smooth"
});

}

initSlider();

/* slide every 3 seconds */

setInterval(autoSlide, 3000);

/* recalc on resize */

window.addEventListener("resize", initSlider);

});

</script>

<?php

return ob_get_clean();

}

add_shortcode(
'trending_now_cards',
'trending_now_full_card_slider'
);

/* NEW ARRIVAL PRODUCT GRID WITH GALLERY HOVER */
add_shortcode('new_arrival_products', function () {

    ob_start();

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => 6,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC'
    );

    $loop = new WP_Query($args);

?>

<style>

.new-arrival-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr); 
    gap: 30px;
}


@media (max-width:1024px){
    .new-arrival-grid{
        grid-template-columns: repeat(3,1fr);
    }
}

@media (max-width:768px){
    .new-arrival-grid{
        grid-template-columns: repeat(2,1fr);
    }
}

.new-product-card {
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    transition: 0.3s;
}

.new-product-card:hover {
    transform: translateY(-5px);
}

/* IMAGE */

.product-image-wrapper {
    position: relative;
    overflow: hidden;
}

.product-image-wrapper img {
    width: 100%;
    display: block;
    transition: 0.4s;
}

/* Gallery hover image */

.product-image-wrapper .hover-img {
    position: absolute;
    top: 0;
    left: 0;
    opacity: 0;
}

.new-product-card:hover .hover-img {
    opacity: 1;
}

/* CONTENT */

.product-content {
    padding: 12px 10px 15px;
}

/* Rating */

.product-rating {
    font-size: 13px;
    color: #bbb;
    margin-bottom: 5px;
}

/* Title */

.product-title {
    font-size: 15px;
    font-weight: 500;
    line-height: 1.4;
    margin-bottom: 8px;
}

.product-title a {
    text-decoration: none;
    color: #222;
}

/* Wishlist + View buttons */



/* Price */

.product-price {
    margin-top: 8px;
    font-weight: 600;
}
/* No gallery fallback */

.no-gallery-msg {

    position: absolute;
    top: 0;
    left: 0;

    width: 100%;
    height: 100%;

    background: rgba(255,255,255,0.92);

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 14px;
    font-weight: 500;

    color: #444;

    text-align: center;
    padding: 10px;

    opacity: 0;
    transition: 0.3s;

}

/* Show message only on hover */

.new-product-card:hover .no-gallery-msg {

    opacity: 1;

}
</style>


<div class="new-arrival-grid">

<?php

if ($loop->have_posts()) :

while ($loop->have_posts()) : $loop->the_post();

global $product;

/* Featured image */

$featured_img = get_the_post_thumbnail_url(
    $product->get_id(),
    'large'
);

/* Gallery image */

$gallery_ids = $product->get_gallery_image_ids();

$hover_img = '';

if (!empty($gallery_ids)) {

    $hover_img = wp_get_attachment_image_url(
        $gallery_ids[0],
        'large'
    );

}

?>
<div class="new-product-card">
<div class="product-image-wrapper">

<a href="<?php the_permalink(); ?>">

<img src="<?php echo esc_url($featured_img); ?>">

<?php if ($hover_img) : ?>

<img class="hover-img"
src="<?php echo esc_url($hover_img); ?>">

<?php else : ?>

<div class="no-gallery-msg">

Gallery image not present

</div>

<?php endif; ?>

</a>

</div>
<div class="product-content">

<div class="product-rating">

<?php echo wc_get_rating_html(
$product->get_average_rating()
); ?>

</div>


<div class="product-title">

<a href="<?php the_permalink(); ?>">
<?php the_title(); ?>
</a>

</div>


<!-- Buttons BELOW TITLE -->

<div class="product-actions">

<a href="<?php echo esc_url(
add_query_arg(
'add_to_wishlist',
$product->get_id()
)
); ?>">

â™¡ Wishlist

</a>

<a href="<?php the_permalink(); ?>">

ðŸ‘ View

</a>

</div>


<div class="product-price">

<?php echo $product->get_price_html(); ?>

</div>

</div>

</div>

<?php

endwhile;

wp_reset_postdata();

endif;

?>

</div>

<?php

return ob_get_clean();

});




/* ===================================== */
/* COMPLETE QUICK VIEW PRODUCT SYSTEM */
/* ===================================== */


/* Enable WooCommerce Gallery Support */

add_action('after_setup_theme', function(){

add_theme_support('wc-product-gallery-zoom');
add_theme_support('wc-product-gallery-lightbox');
add_theme_support('wc-product-gallery-slider');

});


/* Load Required WooCommerce Scripts */

add_action('wp_enqueue_scripts', function(){

if(class_exists('WooCommerce')){

wp_enqueue_script('wc-single-product');
wp_enqueue_script('flexslider');
wp_enqueue_script('zoom');
wp_enqueue_script('photoswipe');
wp_enqueue_script('photoswipe-ui-default');

wp_enqueue_style('photoswipe');

}

});


/* AJAX LOAD PRODUCT */

add_action('wp_ajax_load_quick_product', 'custom_load_quick_product');
add_action('wp_ajax_nopriv_load_quick_product', 'custom_load_quick_product');

function custom_load_quick_product(){

$product_id = intval($_POST['product_id']);

if(!$product_id){
wp_die();
}

global $post;

$post = get_post($product_id);

setup_postdata($post);


/* Important WooCommerce wrapper */

echo '<div class="woocommerce quickview-product">';

/* Load full product template */

wc_get_template_part('content','single-product');

echo '</div>';

wp_reset_postdata();

wp_die();

}



/* QUICK VIEW MODAL SHORTCODE */

add_shortcode('quick_view_system', function(){

ob_start();
?>

<!-- QUICK VIEW MODAL -->

<div id="quickViewModal" class="quick-view-modal">

<div class="quick-view-wrapper">

<span class="quick-close">&times;</span>

<div id="quickViewContent">

Loading...

</div>

</div>

</div>



<style>

/* ============================= */
/* QUICK VIEW CLEAN LAYOUT */
/* ============================= */

/* MODAL */

.quick-view-modal {

display:none;
position:fixed;

z-index:9999;

left:0;
top:0;

width:100%;
height:100%;

background:rgba(0,0,0,0.7);

overflow:auto;

}

.quick-view-wrapper {

background:#fff;

margin:40px auto;

padding:20px;

width:95%;
max-width:1300px;

border-radius:10px;

position:relative;

}

.quick-close {

position:absolute;

right:15px;
top:10px;

font-size:26px;

cursor:pointer;

z-index:10;

}

body.modal-open {
overflow:hidden;
}


/* MATCH PRODUCT PAGE LAYOUT */

.quickview-product .product {

display:flex;
flex-wrap:wrap;

}


/* Gallery Left */

.quickview-product .woocommerce-product-gallery {

width:50%;

}


/* Summary Right */

.quickview-product .summary {

width:50%;
padding-left:40px;

}


/* Fix Images */

.quickview-product img {

max-width:100%;
height:auto;

}


/* Fix Thumbnails */

.quickview-product .flex-control-thumbs {

display:flex !important;
flex-direction:column;

gap:10px;

max-height:400px;
overflow-y:auto;

}

.quickview-product .flex-control-thumbs li {

width:80px !important;

}


/* Mobile */

@media(max-width:768px){

.quickview-product .woocommerce-product-gallery,
.quickview-product .summary {

width:100%;

}

}
/* Fix WooCommerce Gallery Layout Properly */

.quickview-product .woocommerce-product-gallery {

display: flex;
align-items: flex-start;

gap: 15px;

}

/* Thumbnail column */

.quickview-product .flex-control-thumbs {

display: flex !important;

flex-direction: column;

gap: 10px;

width: 90px;

flex-shrink: 0;

}

/* Each thumbnail */

.quickview-product .flex-control-thumbs li {

width: 80px !important;

}

/* Main image wrapper */

.quickview-product .woocommerce-product-gallery__wrapper {

flex: 1;

}

/* Main image */

.quickview-product .woocommerce-product-gallery__wrapper img {

width: 100%;

height: auto;

display: block;

}
	.woocommerce.quickview-product .content-single-wrapper{
    display:flex !important;
}
	/* Keep tab content below tabs */

.woocommerce.quickview-product .woocommerce-tabs {

width: 100%;

clear: both;

margin-top: 40px;

}


/* Tab buttons row */

.woocommerce.quickview-product .wc-tabs {

display: flex;

list-style: none;

gap: 10px;

padding-left: 0;

}


/* Fix tab panel position */

.woocommerce.quickview-product .woocommerce-Tabs-panel {

width: 100%;

margin-top: 20px;

display: block;

}
	/* Only inside Quick View */

.woocommerce.quickview-product form.cart {

display: flex;

align-items: center;

gap: 15px;

flex-wrap: wrap;

}

	/* ================================= */
/* WISHLIST BUTTON STYLE - QUICK VIEW ONLY */
/* ================================= */

.quickview-product .woosw-btn {

background:#c6922e03;   /* your gold color */

color:black ;

border-radius: 50px;

padding: 0px 0px;

font-size: 11x;

font-weight: 600;

border: none;

display: inline-flex;

align-items: center;

justify-content: center;

gap: 8px;

cursor: pointer;

transition: 0.3s;

}





/* Make full width on mobile */

@media(max-width:768px){

.quickview-product .woosw-btn {

width: 100%;

}

}
/* Hide Description heading (h2) only in Quick View */

.quickview-product #tab-description h2 {

display: none;

}
	/* Move thumbnails to LEFT of main image */

.quickview-product .woocommerce-product-gallery {

display: flex;

flex-direction: row; /* normal order */

gap: 15px;

}

/* Thumbnails FIRST (left side) */

.quickview-product .flex-control-thumbs {

order: 1;

display: flex !important;

flex-direction: column;

gap: 10px;

width: 90px;

flex-shrink: 0;

}

/* Main image SECOND (right side) */

.quickview-product .woocommerce-product-gallery__wrapper {

order: 2;

flex: 1;

}
</style>



<script>

jQuery(document).on("click",".quick-view-btn",function(e){

e.preventDefault();

var product_id = jQuery(this).data("product-id");

/* Open Modal */

jQuery("#quickViewModal").fadeIn();

jQuery("body").addClass("modal-open");


/* Load Product */

jQuery.ajax({

url: "<?php echo admin_url('admin-ajax.php'); ?>",

type: "POST",

data: {

action: "load_quick_product",
product_id: product_id

},

success: function(response){

jQuery("#quickViewContent").html(response);


/* Reinitialize WooCommerce Gallery */

setTimeout(function(){

if(typeof jQuery.fn.wc_product_gallery !== 'undefined'){

jQuery('.woocommerce-product-gallery').each(function(){

jQuery(this).wc_product_gallery();

});

}

},400);

}

});

});


/* Close Button */

jQuery(document).on("click",".quick-close",function(){

jQuery("#quickViewModal").fadeOut();

jQuery("body").removeClass("modal-open");

});


/* Close Outside */

jQuery(document).on("click","#quickViewModal",function(e){

if(e.target.id === "quickViewModal"){

jQuery("#quickViewModal").fadeOut();

jQuery("body").removeClass("modal-open");

}

});


/* ESC Close */

jQuery(document).keyup(function(e){

if(e.key === "Escape"){

jQuery("#quickViewModal").fadeOut();

jQuery("body").removeClass("modal-open");

}

});

</script>

<?php

return ob_get_clean();

});
 add_action('woocommerce_payment_gateways', 'add_zelle_gateway_class');

 function add_zelle_gateway_class($methods) {

   if (!class_exists('WC_Payment_Gateway')) return $methods;

     class WC_Gateway_Zelle extends WC_Payment_Gateway {

         public function __construct() {

             $this->id = 'zelle';
             $this->method_title = 'Zelle Payment';
            $this->method_description = 'Pay using Zelle';

             $this->has_fields = false;
             $this->init_form_fields();
             $this->init_settings();

             $this->title        = $this->get_option('title');
             $this->description  = $this->get_option('description');
             $this->zelle_contact = $this->get_option('zelle_contact');

            add_action(
                'woocommerce_update_options_payment_gateways_' . $this->id,
                array($this, 'process_admin_options')
             );

            add_action(
                'woocommerce_thankyou_' . $this->id,
                 array($this, 'thankyou_page')
            );
        }

         public function init_form_fields() {

             $this->form_fields = array(

                'enabled' => array(
                    'title'   => 'Enable/Disable',
                   'type'    => 'checkbox',
                    'label'   => 'Enable Zelle',
                    'default' => 'yes'                ),

                'title' => array(
                    'title'   => 'Title',
                     'type'    => 'text',
                     'default' => 'Pay via Zelle'
                 ),

                'description' => array(
                    'title'   => 'Description',
                    'type'    => 'textarea',
                     'default' => 'Pay securely using Zelle'
                ),

                'zelle_contact' => array(
    			'title'   => 'Zelle Phone Number',
   			    'type'    => 'text',
   			    'default' => '+1 (302) 290-4906'
				)
            );
        }

         public function process_payment($order_id) {

             $order = wc_get_order($order_id);

             $order->update_status('on-hold', 'Awaiting Zelle Payment');

            wc_reduce_stock_levels($order_id);

            WC()->cart->empty_cart();

            return array(
                'result'   => 'success',
                 'redirect' => $this->get_return_url($order)
             );
         }

        public function thankyou_page($order_id) {

    $order = wc_get_order($order_id);

    echo '<div style="
        padding:25px;
        border:1px solid #e5e5e5;
        border-radius:12px;
        background:#fafafa;
         max-width:600px;
     ">';

     echo '<h2 style="margin-bottom:15px; font-size:30px;">
        Complete Your Zelle Payment
    </h2>';

     echo '<p>
        Send payment to:
     </p>';

    echo '<p style="
        font-size:22px;
        font-weight:700;
        color:#6c2bd9;
        margin-bottom:20px;
    ">
        ' . esc_html($this->zelle_contact) . '
    </p>';

    echo '<p>
        <strong>Order ID:</strong> #' . $order->get_id() . '
    </p>';

     echo '<p style="margin-bottom:25px;">
         Use your Order ID as payment reference.
    </p>';

     echo '
     <button id="open-zelle-btn" style="
         background:#6c2bd9;
        color:#fff;
         border:none;
         padding:14px 24px;
         border-radius:8px;
         font-size:16px;
         cursor:pointer;
         font-weight:600;
     ">
         Open Zelle App
     </button>
     ';

     echo '
     <div id="zelle-fallback" style="
        display:none;
         margin-top:20px;
         padding:15px;
         background:#fff3f3;
        border:1px solid #ffcccc;
        border-radius:8px;
        color:#cc0000;
     ">
        Zelle app is not installed or not supported on this device.<br><br>

        Please open your banking app manually and send payment to:<br>

       <strong>' . esc_html($this->zelle_contact) . '</strong><br><br>

       Or place a new order using Cash on Delivery.
    </div>
    ';

     echo '</div>';

    ?>

    <script>

     document.getElementById('open-zelle-btn').onclick = function () {

        var start = Date.now();

        // Try deep link
       window.location.href = 'zelle://';
        setTimeout(function () {

            // If app not opened
            if (Date.now() - start < 2500) {

                document.getElementById('zelle-fallback').style.display = 'block';
           }

        }, 2000);
    };

    </script>

     <?php
 }
    }

     $methods[] = 'WC_Gateway_Zelle';

     return $methods;
 }




// Force USD as default currency
add_filter('woocommerce_currency', function() { return 'USD'; });

// Force USD symbol
add_filter('woocommerce_currency_symbol', function($symbol, $currency) { if ($currency === 'USD') return '$'; return $symbol; }, 10, 2);



// Fix Login and Register page URLs
add_filter('login_url', function() { return home_url('/my-account/'); });
add_filter('register_url', function() { return home_url('/my-account/?action=register'); });
add_filter('logout_url', function() { return home_url('/my-account/'); });

// Fix footer demo links
add_filter('wp_nav_menu_items', function($items, $args) {
  $items = str_replace('demo2wpopal.b-cdn.net/postero', 'theartframer.us', $items);
  $items = str_replace('chocolate-chicken-365829.hostingersite.com', 'theartframer.us', $items);
  return $items;
}, 10, 2);



// Enable WooCommerce registration on my-account page
add_action('init', function() {
  update_option('woocommerce_enable_myaccount_registration', 'yes');
  update_option('woocommerce_enable_checkout_login_reminder', 'yes');
  update_option('woocommerce_registration_generate_password', 'yes');
  update_option('woocommerce_registration_generate_username', 'no');
});



// Hide Themes filter from shop sidebar
add_action('wp_head', function() {
  echo '<style>
  .widget_product_tag_cloud, 
  .postero-product-themes,
  .wc-layered-nav-terms.pa_themes,
  .widget[id*=themes],
  .sidebar .widget:has(.pa_themes),
  li.pa_themes,
  .woocommerce-widget-layered-nav:has(a[href*=themes]) {
    display: none !important;
  }
  </style>';
});



// Hide Themes section from shop sidebar
add_action('wp_head', function() {
  echo '<style>.postero-product-themes-widget, .widget_postero_product_themes, .sidebar .widget:has(.product-themes) { display:none!important; }</style>';
});

// Add Size filter to shop sidebar
add_action('dynamic_sidebar_before', function($index) {
  if ($index === 'shop-sidebar' || $index === 'sidebar-shop' || strpos($index,'shop') !== false) {
    echo '<div class="widget widget_layered_nav"><h2 class="widget-title">Filter By Size</h2><ul>';
    $sizes = ['2x3-ft','2x3-5-ft','24-ft','2x5-ft','2-5x3-ft','2-5x4-ft','2-5x5-ft','3x4-ft','3x5-ft','3x6-ft','4x3-ft','4x4-ft','4x5-ft','4x6-ft'];
    $labels = ['2x3 ft','2x3.5 ft','2x4 ft','2x5 ft','2.5x3 ft','2.5x4 ft','2.5x5 ft','3x4 ft','3x5 ft','3x6 ft','4x3 ft','4x4 ft','4x5 ft','4x6 ft'];
    foreach($sizes as $i => $slug) {
      $url = home_url('/product-category/art-accessories/frame-sizes/')  . $slug . '/';
      echo '<li><a href="' . esc_url($url) . '">' . $labels[$i] . '</a></li>';
    }
    echo '</ul></div>';
  }
});



// Hide Themes widget - nuclear CSS approach
add_action('wp_head', function() {
  echo '<style>
  /* Hide themes widget by title text */
  .widget-title:contains(Themes) ~ *,
  .woocommerce-widget-layered-nav-list,
  .widget_postero_product_themes,
  [class*=themes-widget],
  [class*=product_themes],
  [class*=product-themes] {display:none!important;}
  /* Target sidebar widget containing animal/architecture links */
  .sidebar li a[href*=product-themes] { display:none!important; }
  .sidebar .widget:has(a[href*=product-themes]) { display:none!important; }
  </style>';
});


// Fix price filter + enqueue missing WC scripts
add_action('wp_enqueue_scripts', function() {
  if (is_shop() || is_product_category() || is_product_tag()) {
    wp_enqueue_script('wc-price-slider');
    wp_enqueue_style('woocommerce-layout');
    wp_enqueue_style('woocommerce-smallscreen');
    wp_enqueue_style('woocommerce-general');
  }
});

// Fix price filter FILTER button submission
add_action('wp_footer', function() {
  if (is_shop() || is_product_category()) { ?>
  <script>
  jQuery(document).ready(function($) {
    $(document).on('click', '.price_slider_wrapper .button, .widget_price_filter .button', function(e) {
      e.preventDefault();
      var form = $(this).closest('form');
      if (form.length) { form.submit(); }
      else {
        var url = new URL(window.location.href);
        var minPrice = $('.price_slider_amount #min_price').val();
        var maxPrice = $('.price_slider_amount #max_price').val();
        if (minPrice) url.searchParams.set('min_price', minPrice);
        if (maxPrice) url.searchParams.set('max_price', maxPrice);
        window.location.href = url.toString();
      }
    });
  });
  </script>
  <?php }
});
