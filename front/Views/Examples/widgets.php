<div>
    <div class="inline-block" class="inline-block" style="display: flex; flex-wrap">
        <?= $this->chartBlock ?>
    </div>
    <div  class="inline-block" style="text-align: center; padding: 0 20px;">

        <h2>Standard Widgets</h2>
        <p style="text-align: left;">
            You can create you own widgets and insert them into Vulkan Views.
            The standard widgets are demonstrated there.
        </P>

        <div>
            <?= $this->chartDataInput ?>
        </div>

        <b>Widgets: </b>
        <a onclick="showWidget(0)">General</a> |
        <a onclick="showWidget(1)">Accentuation</a> |
        <a onclick="showWidget(2)">Aspect List</a> |
        <a onclick="showWidget(3)">Aspect Cross Table</a> |
        <a onclick="showWidget(4)">Essential Dignity</a> |
        <?= $this->analysesBlock ?>
    </div>
    <div  class="inline-block">
        <?= $this->block7 ?>
    </div>
</div>

<script>

    window.onload = function() {
        let widgets = document.getElementsByClassName('vul-component');

        function hideAll() {
            for (el of widgets) {
                el.style.display = 'none'
            }
        }

        showWidget = function(idx) {
            hideAll();
            widgets[idx].style.display = '';
        }

        showWidget(0);

        let chartInputs = document.getElementsByClassName('vul-chart-data-input');

        function sendData() {
            let arr = [];
            for (el of chartInputs) {
                for (child of el.childNodes) {
                    if (child.tagName == 'INPUT') {
                        arr.push(child.getAttribute('name') + '=' + child.getAttribute('value'))
                    }
                }
            }

            arr.push('section=' + document.getElementById('selected-section').value);
            window.location.href = "?" + arr.join('&');
        }

        document.getElementById('vul-chart-data-input-send').onclick = function(event) {
            let arr = [];
            for (let el of $('.vul-chart-data-input input')) {
                arr.push(el.attr('name'))
            };
            localStorage.setItem($('[name="section"]').val(), JSON.stringify(arr));
        };
    };

</script>

<style>
    .vul-t-component {
        display: none;
    }
    .vul-t-data-table {
        width: 400px
    }
</style>