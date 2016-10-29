<div>
    <div>
        <?= $this->topBlock ?>
    </div>

    <div class="inline-block" class="inline-block" style="display: flex; flex-wrap">
        <?= $this->chartBlock ?>
    </div>
    <div  class="inline-block" style="padding: 0 20px; width: 100%">
        <h2><?= $this->title ?></h2>
        <?= $this->description ?>
    </div>

    <div  class="inline-block">
        <?= $this->bottomBlock ?>
    </div>
</div>