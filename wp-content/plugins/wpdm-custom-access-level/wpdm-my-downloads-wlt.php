
<div class="w3eden">

<?php global $post; if($files) {

    echo "<div class='row'>";
    $cols = isset($cols) && in_array($cols, array(1,2,3,4))? $cols: 1;
    $cols = 12/$cols;
    while($files->have_posts()):  $files->the_post();


    $vars = (array)$post;
    echo "<div class='col-md-{$cols}'>";
    echo FetchTemplate($template, $vars);
    echo "</div>";

endwhile; echo "</div>"; } else { echo "<div class='alert alert-warning'>Sorry! No files shared for you.</div>"; } ?>
<div style="clear: both"></div>
</div>
