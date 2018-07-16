<div class="cform">
    <textarea id="chartXLabels" hidden>{LABELS}</textarea>
    <textarea id="chartDatasets" hidden>{DATASETS}</textarea>
    <div class="cf-table-caption cf-caption">
        <span>{TITLE}</span>
    </div>

    <div class="analytic-container">
        <form action="" method="GET">
            <input type="hidden" name="view" value="{VIEW}">
            <div class="uk-margin" uk-margin>
                <span class="uk-text-middle"> Период </span>
                <div uk-form-custom="target: true">
                    <input name="period" value="{START_DATE} - {END_DATE}" class="uk-input uk-form-width-medium period-select" type="text" placeholder="Выберите диапазон" />
                </div>
            </div>
            <div class="uk-margin" uk-margin>
                <label><input class="uk-checkbox" type="checkbox" name="unique_visits" value="true" {UNIQUE_VISITORS}> Уникальные посетители </label>
            </div>
            <div class="uk-margin" uk-margin>
                <button type='submit' class="uk-button uk-button-default period-submit">Применить</button>
                <button type='submit' name='download' value='1' class="uk-button uk-button-default excel-submit">Выгрузить в EXCEL</button>
            </div>
        </form>
        <div class="decorator-rule"></div>
        <div>
            <canvas id="canvas"></canvas>
        </div>
        <div class="decorator-rule"></div>
        <table class="analytic-summary">
            {SUMMARY}
                <tr>
                    <td><span style="color:{SUMMARY_COLOR}">{SUMMARY_TITLE}</span></td>
                    <td>{SUMMARY_VALUE}</td>
                </tr>
            {/SUMMARY}
            <tr>
                <td>Всего:</td>
                <td>{SUMMARY_TOTAL}</td>
            </tr>
        </table>
    </div>
</div>