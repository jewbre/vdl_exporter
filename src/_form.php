<?php
/**
 * Created by PhpStorm.
 * User: Vilim Stubičan
 * Date: 30.9.2015.
 * Time: 0:20
 */
/*
 * Partial for exporter form
 * */

global $wpdb;

$post_types = get_post_types();
?>
<div class="vdl-exporter-main-wrapper">
    <?php formStyles()?>
    <h2>VDL Exporter</h2>
    <form method="post" action="">

        <?php
            foreach($post_types as $p) {
                postTypeSection($p);
            }
        ?>

        <?php
        submit_button();
        ?>
    </form>
</div>

<?php
    function postTypeSection($post_type) {
        $args = array(
            "post_type" => $post_type,
            "paged" => -1,
            "posts_per_page" => -1,
            "orderby" => "title"
        );
        $query = new WP_Query($args);
        $posts = array();
        $results = $query->get_posts();
        foreach($results as $p) {
            $posts[$p->ID] = $p->post_title;
        }

        $disabled = empty($posts);
        ?>
        <fieldset>
            <label for="VDLExporterForm_<?=$post_type?>"><?=ucfirst($post_type)?></label>

            <?php
                if(!$disabled) {
                    ?>
            <div class="selection-holder" data-id="<?=$post_type?>-wrapper">
                <br>
                <select name="VDLExporterForm[<?=$post_type?>][select][]" multiple="multiple">
                    <?php
                        foreach($posts as $id => $name) echo "<option value='$id'>$name</option>";
                    ?>
                </select>
            </div>
                    <?php
                }
                ?>
        </fieldset>

        <?php
    }


function formStyles() {
    ?>
        <style>
            .vdl-exporter-main-wrapper {
                font-size: 18px;
            }
            .vdl-exporter-main-wrapper select {
                display:inline-block;
                width:400px;
                -webkit-border-radius: 10px;
                -moz-border-radius: 10px;
                border-radius: 10px;
                padding:10px;
                height:auto;
            }
            .vdl-exporter-main-wrapper input[type="checkbox"] {
                display:inline-block;
                vertical-align: middle;
            }
            .vdl-exporter-main-wrapper fieldset {
                border-bottom: 2px solid #a1a1a1;
                display:inline-block;
                width:100%;
                padding:10px;
            }
            .vdl-exporter-main-wrapper label {
                margin:10px 10px;
                display:inline-block;
                vertical-align: middle;
            }
            .vdl-exporter-main-wrapper .selection-holder {
                padding-left: 20px;
                font-size:14px;
                margin:10px 0px;
            }
            .vdl-exporter-main-wrapper .selection-holder label{
                margin:0px 10px;
            }

        </style>
    <?php
}