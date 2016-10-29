<?php
foreach ($this->sections as $section) {
    $forSigns = $forHouses = '';
?>
    <span class="vul-t-data-table-title"><?= $section->title ?></span>
<table class="vul-t-data-table">
    <tr><td>
    <?php
    foreach ($section->points as $point) {
        $dignity = $this->ess->getPointDignity($point->info->id, $point->getSignId());
        if ($dignity) {
            $forSigns .= '<tr><td>'. $point->info->caption . '</td><td>' . \vulkan\Core\Essential\EssDignity::DIGNITY_NAMES[$dignity] . '</td></tr>';
        }
    }

    if ($forSigns) {
        echo '<table class="vul-t-data-table"><td colspan="2">By sign</td>', $forSigns, '</table>';
    }

    ?>

        </td></tr>
        <tr><td>
    <?php
    $section->calculateHousesOfPoints();

    foreach ($section->points as $point) {
        $dignity = $this->ess->getPointDignity($point->info->id, $point->getHouseId());
        if ($dignity) {
            $forHouses .= '<tr><td>'. $point->info->caption . '</td>
                <td>' . \vulkan\Core\Essential\EssDignity::DIGNITY_NAMES[$dignity] . '</td></tr>';
        }
    }

    if ($forHouses) {
        echo '<table class="vul-t-data-table"><td colspan="2">By house</td>', $forHouses, '</table>';
    }
    ?>

        </td></tr>
</table>
<?php } ?>