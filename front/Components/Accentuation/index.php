<table class="vul-t-data-table"><?= ($this->title ? '<caption>' . $this->title . '</caption>' : '') ?>

<?php

foreach ($this->rows as $row) {
    $aspect = current($row);
    echo '<tr>';
    echo '<td><a href="" data-type="point" data-id="' . $aspect->point1->info->id  . '">' . next($row) . '</a></td>
        <td><a href="" data-type="aspect" data-id="' . $aspect->aspect->id  . '">' . next($row) . '</a></td>
        <td><a href="" data-type="point" data-id="' . $aspect->point1->info->id  . '">' . next($row) . '</a></td>';
    echo '</tr>';
}
?>

</table>