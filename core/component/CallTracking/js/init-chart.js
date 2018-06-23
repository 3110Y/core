window.jQuery(function(){
    'use strict';
    // noinspection SpellCheckingInspection
    var
        timeFormat = 'DD.MM.YYYY',
        labels = JSON.parse($('textarea#chartXLabels').text()),
        datasets = JSON.parse($('textarea#chartDatasets').text());
    window.console.log(labels,datasets);
    var
        config = {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                title: {
                    text: ''
                },
                scales: {
                    xAxes: [{
                        type: 'time',
                        time: {
                            format: timeFormat,
                            tooltipFormat: timeFormat,
                            displayFormats: {
                                'millisecond': timeFormat,
                                'second': timeFormat,
                                'minute': timeFormat,
                                'hour': timeFormat,
                                'day': timeFormat,
                                'week': timeFormat,
                                'month': timeFormat,
                                'quarter': timeFormat,
                                'year': timeFormat
                            }
                        },
                        scaleLabel: {
                            display: true,
                            labelString: ''

                        }
                    }],
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Количество'
                        },
                        ticks: {
                            beginAtZero:true,
                            stepSize: 1
                        }
                    }]
                }
            }
        },
        context2d = document.getElementById('canvas').getContext('2d'),
        $dateInterval = $('.period-select');

    window.myLine = new Chart(context2d, config);

    // noinspection SpellCheckingInspection
    $dateInterval.datepicker({
        type: 'date-range',
        readonly: false,
        disabled: false,
        format: 'dd.MM.yyyy',
        align: 'left',
        placeholder: 'Выберите диапазон',
        weekStart: 1,
        lang: 'ru-RU',
        defaultValue: $dateInterval.val()
    });
});

