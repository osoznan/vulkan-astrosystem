<table class="vul-t-data-table">
    <?php

    echo ($this->title ? '<caption>' . $this->title . '</caption>' : '');

    foreach ($this->rows as $row) {
        echo '<tr>';
        if (is_array($row)) {
            foreach ($row as $col) {
                echo '<td>', $col, '</td>';
            }
        } else {
            echo '<td class="vul-t-data-table-title" colspan="1000">', $row, '</caption>';
        }
        echo '</tr>';
    }

    ?>

</table>