<div>

    <div class="inline-block" class="inline-block" style="display: flex; flex-wrap">
        <?= $this->chartBlock ?>

    </div>

    <div class="inline-block">
        <h2>Chart JSON Definition</h2>
        You can construct varios charts by defining them by json-format. Select an example and see
        a selected chart in both visual and json representation.
        <form>
            <select name="example">
                <option value="example-1">Example 1</option>
                <option value="example-2" <?= $_GET['example'] == 'example-2' ? 'selected': '' ?>>Example 2</option>
                <option value="example-3" <?= $_GET['example'] == 'example-3' ? 'selected': '' ?>>Example 3</option>
            </select>
            <input type="hidden" name="ctrl" value="examples">
            <input type="hidden" name="action" value="jsonchart">

            <button>Show</button>
        </form>
        <textarea readonly style="width: 600px; height: 600px"><?= $this->text ?></textarea>
    </div>
</div>

<script>

</script>