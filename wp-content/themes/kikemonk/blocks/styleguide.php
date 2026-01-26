<?php
/**
 * Block Name: Styleguide
 * Slug: styleguide
 * Description: Styleguide Block.
 * Keywords: Styleguide
 * Align: full
 */
?>
<section id="styleguide" data-block="styleguide" class="py-5 px-4 sm:px-0">
    <header id="style-head">
        <div class="container xl mx-auto">
            <div class="row">
                <div class="logotipo">
                    <a href="<?php bloginfo("url"); ?>">
                        <img src="<?php bloginfo(
                          "template_url"
                        ); ?>/assets/img/logos/spot-logo-color.svg" alt="">
                    </a>
                </div>
            </div>
        </div>
    </header>
    <div id="style-hero">
        <div class="container xl mx-auto">
            <div class="titles">
                <h1>Style Guide</h1>
            </div>
        </div>
    </div>

    <?php while (have_rows("sections")): the_row();
      // Colors
      if (get_row_layout() == "colors"): ?>
            <div class="container xl mx-auto">
                <div class="row">
                    <h3 class="section-title">Colors</h3>
                </div>
                <div class="row">
                    <?php while (have_rows("colors")):

                      the_row();
                      $color = get_sub_field("color");
                      if ($color == 'color1') {
                        $colorBG = "bg-primary";
                      } elseif ($color == 'color2') {
                        $colorBG = "bg-secondary";
                      } elseif ($color == 'color3') {
                        $colorBG = "bg-tertiary";
                      } elseif ($color == 'color4') {
                        $colorBG = "body-color";
                      } elseif ($color == 'color5') {
                        $colorBG = "bg-gray";
                      } elseif ($color == 'color6') {
                        $colorBG = "bg-gray-light";
                      } elseif ($color == 'color7') {
                        $colorBG = "bg-splogo-dark-blue";
                      } elseif ($color == 'color8') {
                        $colorBG = "bg-sp-turquoise";
                      } elseif ($color == 'color9') {
                        $colorBG = "bg-sp-orange";
                      } elseif ($color == 'color10') {
                        $colorBG = "bg-sp-purple";
                      } elseif ($color == 'color11') {
                        $colorBG = "bg-body";
                      } elseif ($color == 'color12') {
                        $colorBG = "bg-black";
                      }
                      ?>
                        <div class="color-module <?php echo $colorBG; ?>">
                            <span>
                                <?php the_sub_field("color_name"); ?>
                            </span>
                            <span>
                                <?php the_sub_field("color_hex"); ?>
                            </span>
                        </div>
                    <?php
                    endwhile; ?>
                </div>
            </div>
        <?php
        // Typography
        elseif (get_row_layout() == "typography"): ?>
            <div class="container xl mx-auto">
                <div class="row">
                    <h3 class="section-title">
                        Typography
                    </h3>
                </div>

                <div class="row">
                    <?php while (have_rows("headings")):

                      the_row();
                      $tag = get_sub_field("text_type");
                      $heading = get_sub_field("heading");
                      $dummy = get_sub_field("demo_text");
                      ?>
                        <div class="typo-module">
                            <?php if ($tag == "h1"): ?>
                                <h1>
                                    <?php echo $heading; ?>
                                </h1>
                            <?php elseif ($tag == "h2"): ?>
                                <h2>
                                    <?php echo $heading; ?>
                                </h2>
                            <?php elseif ($tag == "h3"): ?>
                                <h3>
                                    <?php echo $heading; ?>
                                </h3>
                            <?php elseif ($tag == "h4"): ?>
                                <h4>
                                    <?php echo $heading; ?>
                                </h4>
                            <?php elseif ($tag == "h5"): ?>
                                <h5>
                                    <?php echo $heading; ?>
                                </h5>
                            <?php elseif ($tag == "h6"): ?>
                                <h6>
                                    <?php echo $heading; ?>
                                </h6>
                            <?php elseif ($tag == "lead"): ?>
                                <p class="lead">
                                    <?php echo $heading; ?>
                                </p>
                            <?php elseif ($tag == "body"): ?>
                                <p>
                                    <?php echo $heading; ?>
                                </p>
                            <?php elseif ($tag == "small"): ?>
                                <p class="small">
                                    <?php echo $heading; ?>
                                </p>
                            <?php endif; ?>
                            
                            <p <?php if ($tag == "lead"):
                              echo 'class=""';
                            elseif ($tag == "small"):
                              echo 'class="small"';
                            endif; ?>>
                                <?php echo $dummy; ?>
                            </p>
                        </div>
                    <?php
                    endwhile; ?>
                </div>

                <div class="row">
                    <div class="col-md-6 list-block">
                        <?php while (have_rows("lists")):

                        the_row();
                        $type = get_sub_field("list_type");
                        if ($type == "normal"):
                            $name = "Bullet List (unordered)";
                        elseif ($type == "bullet"):
                            $name = "Bullet List (ordered)";
                        elseif ($type == "graphic"):
                            $name = "Unordered List, Arrows";
                        endif;
                        ?>
                        <div class="list-module">
                            <h5>
                                <?php echo $name; ?>
                            </h5>
                            <?php if ($type != "bullet"): ?>
                                <ul <?php if ($type == "graphic"):
                                echo 'class="graphic-list"';
                                endif; ?>>
                                    <?php while (have_rows("items")):
                                    the_row(); ?>
                                        <li><?php the_sub_field("item"); ?></li>
                                    <?php
                                    endwhile; ?>
                                </ul>
                            <?php else: ?>
                                <ol>
                                    <?php while (have_rows("items")):
                                    the_row(); ?>
                                        <li><?php the_sub_field("item"); ?></li>
                                    <?php
                                    endwhile; ?>
                                </ol>
                            <?php endif; ?>
                        </div>
                        <?php
                        endwhile; ?>
                    </div>
                    <div class="col-md-6 dark list-block">
                        <?php while (have_rows("lists")):

                        the_row();
                        $type = get_sub_field("list_type");
                        if ($type == "normal"):
                            $name = "Bullet List (unordered)";
                        elseif ($type == "bullet"):
                            $name = "Bullet List (ordered)";
                        elseif ($type == "graphic"):
                            $name = "Unordered List, Arrows";
                        endif;
                        ?>
                        <div class="list-module">
                            <h5>
                                <?php echo $name; ?>
                            </h5>
                            <?php if ($type != "bullet"): ?>
                                <ul <?php if ($type == "graphic"):
                                echo 'class="graphic-list"';
                                endif; ?>>
                                    <?php while (have_rows("items")):
                                    the_row(); ?>
                                        <li><?php the_sub_field("item"); ?></li>
                                    <?php
                                    endwhile; ?>
                                </ul>
                            <?php else: ?>
                                <ol>
                                    <?php while (have_rows("items")):
                                    the_row(); ?>
                                        <li><?php the_sub_field("item"); ?></li>
                                    <?php
                                    endwhile; ?>
                                </ol>
                            <?php endif; ?>
                        </div>
                        <?php
                        endwhile; ?>
                    </div>
                </div>
                
                <?php if(have_rows("blockquotes")) : ?>
                    <div class="blockquote-module">
                        <?php while (have_rows("blockquotes")):

                    the_row();
                    $style = get_sub_field("style");
                    if ($style == "long-quote"):
                        $title = "Block Quote Example";
                    elseif ($style == "short-quote"):
                        $title = "Block Quote Example";
                    endif;
                    ?>
                        <h6><?php echo $title; ?></h6>
                        <div class="row">
                            <div class="col-md-12" style="padding-right:90px">
                                <div class="<?php the_sub_field("style"); ?>">
                                    <?php the_sub_field("content"); ?>
                                </div>
                            </div>
                        </div>
                        <?php
                        endwhile; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php elseif (get_row_layout() == "buttons_and_links"): ?>
            <div class="container xl mx-auto">
                <div class="row">
                    <h3 class="section-title">
                        Buttons & Links
                    </h3>
                </div>
            </div>

            <?php while (have_rows("containers")):

              the_row();
              $bgStyle = get_sub_field("background_style");
              ?>
                <div class="<?php echo $bgStyle . "-bg"; ?>">
                    <div class="container xl mx-auto">
                        <div class="row">
                            <div class="col-md-3 col-xs-12">
                                <h5>Primary</h5>
                                <div class="buttons-module">
                                    <?php while (have_rows("buttons_primary")):
                                      the_row(); ?>
                                        
                                            <a href="" class="btn-<?php the_sub_field(
                                              "size"
                                            ); ?> btn btn-<?php the_sub_field(
   "color"
 ); ?>"><?php the_sub_field("label"); ?></a>
                                        
                                    <?php
                                    endwhile; ?>
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-12">
                                <h5>Secondary</h5>
                                <div class="buttons-module">
                                    <?php while (
                                      have_rows("buttons_secondary")
                                    ):
                                      the_row(); ?>
                                        
                                            <a href="" class="btn-<?php the_sub_field(
                                              "size"
                                            ); ?> btn btn-<?php the_sub_field(
   "color"
 ); ?> btn-outlined"><?php the_sub_field("label"); ?></a>
                                        
                                    <?php
                                    endwhile; ?>
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-12">
                                <h5>Tertiary</h5>
                                <div class="buttons-module">
                                    <?php while (have_rows("buttons_tertiary")):
                                      the_row(); ?>
                                        
                                            <a href="" class=" btn btn-<?php the_sub_field(
                                              "size"
                                            ); ?> btn-tertiary">Button <?php the_sub_field(
   "size"
 ); ?> </a>
                                        
                                    <?php
                                    endwhile; ?>
                                </div>
                            </div>
                            <div class="col-md-3 col-xs-12">
                                <h5>Misc</h5>
                                <div class="buttons-module">
                                    <?php while (have_rows("buttons_no_bg")):
                                      the_row(); ?>
                                        
                                            <a href="" class="no-bg-btn">
                                                <?php echo get_sub_field('link')['title']; ?>
                                            </a>
                                    <?php
                                    endwhile; ?>
                                </div>

                                <div class="buttons-module">
                                    <?php while (have_rows("hyperlinks")):
                                      the_row(); ?>
                                        
                                            <a href="">Hyperlinks</a>
                                    <?php
                                    endwhile; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            endwhile; ?>
        <?php 
        endif;
    endwhile; ?>
</section>