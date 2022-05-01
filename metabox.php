<?php
/****
Plugin Name:Image & Gallery Metabox
Plugin URI:
Author: Milton
Author URI:
Description: Our 2019 default theme is designed to show off the power of the block editor. It features custom styles for all the default blocks, and is built so that what you see in the editor looks like what you'll see on your website. Twenty Nineteen is designed to be adaptable to a wide range of websites, whether you’re running a photo blog, launching a new business, or supporting a non-profit. Featuring ample whitespace and modern sans-serif headlines paired with classic serif body text, it's built to be beautiful on all screen sizes.
Version: 1.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain:metabox 
******/

class Image_Gallery{
    public function __construct(){
        add_action( 'admin_menu',array($this,'omb_add_metabox'));
        add_action('save_post',array($this,'omb_save_metaboxes'));
        add_action( 'admin_enqueue_scripts', array($this,'load_admin_assets') );
    }
    public function load_admin_assets(){
        wp_enqueue_script( 'admin-main', plugin_dir_url(__FILE__).'assets/js/admin-main.js', array('jquery'),true,time());
    }
    public function omb_add_metabox(){
        add_meta_box( 
            'omb_image_gallary',
            __('Image & Gallery','metabox'),
            array($this,'omb_display_metabox'),
            'post'
        );
    }
    public function is_sceure($action,$nonce_field,$post_id){
        $nonce=isset($_POST[$nonce_field]) ? $_POST[$nonce_field]:'';
        if($nonce==''){
            return false;
        }
        if(!wp_verify_nonce( $nonce, $action )){
            return false;
        }
        
        if(!current_user_can( 'edit_post',$post_id )){
            return false;
        }
        if(wp_is_post_autosave( $post_id )){
            return false;
        }
        if(wp_is_post_revision( $post_id )){
            return false;
        }
        return true;
    }
    public function omb_save_metaboxes($post_id){
        if(!$this->is_sceure('omb_metabox','omb_image_feild',$post_id)){
            return $post_id;
        }
        $image_id=isset($_POST['omb_image_id']) ? $_POST['omb_image_id']:'';
        $image_url=isset($_POST['omb_image_url']) ? $_POST['omb_image_url']:'';

        $images_id=isset($_POST['omb_images_id']) ? $_POST['omb_images_id']:'';
        $images_url=isset($_POST['omb_images_url']) ? $_POST['omb_images_url']:'';

        update_post_meta( $post_id, 'omb_image_id', $image_id);
        update_post_meta( $post_id, 'omb_image_url', $image_url);

        update_post_meta( $post_id, 'omb_images_id', $images_id);
        update_post_meta( $post_id, 'omb_images_url', $images_url);
    }
    public function omb_display_metabox($post){
        wp_nonce_field( 'omb_metabox', 'omb_image_feild' );
        $image_id=esc_attr(get_post_meta( $post->ID, 'omb_image_id',true));
        $image_url=esc_url(get_post_meta( $post->ID, 'omb_image_url',true));

        $images_id=esc_attr(get_post_meta( $post->ID, 'omb_images_id',true));
        $images_url=esc_url(get_post_meta( $post->ID, 'omb_images_url',true));
        $label=__('Image','metabox');
        $label2=__('Gallery Image','metabox');
        $metabox= <<<EOD
                 <div>
                    <label>{$label}</label>
                    <button id="upload_image">Upload Image</button>
                    <input type="hidden" name="omb_image_id" id="omb_image_id" value="{$image_id}"/>
                    <input type="hidden" name="omb_image_url" id="omb_image_url" value="{$image_url}"/>
                    <div id="preview_image"></div>
                 </div>
        EOD;
        $metabox .=<<<EOD
                 <div>
                    <label>{$label2}</label>
                    <button id="upload_images">Upload Images</button>
                    <button id="remove_images">Remove Images</button>
                    <input type="hidden" name="omb_images_id" id="omb_images_id" value="{$images_id}"/>
                    <input type="hidden" name="omb_images_url" id="omb_images_url" value="{$images_url}"/>
                    <div id="preview_images"></div>
                 </div>
        EOD;
        echo $metabox;
    }
}
new Image_Gallery();