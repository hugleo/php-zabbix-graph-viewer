const zabbixUrl = '/zabbix';

function generateUrl() {
    graphId = $("#graphid").val();
    hostId = $("#hostid").val();
    var url = '';
    if (graphId) {
        url = zabbixUrl + '/zabbix.php';
        var obj = { view_as: 'showgraph', action: 'charts.view', filter_search_type: 0, 'filter_hostids[]': hostId, 'filter_graphids[]': graphId, filter_set: 1 };
        url = url + '?' + $.param(obj);
    }
    return url;
}

function loadIframe(iframeName, url) {
    var $iframe = $('#' + iframeName);
    if ( $iframe.length ) {
        $iframe.attr('src',url);
        return false;
    }
    return true;
}

$('#dropdowns').cascadingDropdown({
    selectBoxes: [
        {
            selector: '.step1',
            source: function(request, response) {
                $.getJSON('app.php?step1', request, function(data) {
                    var selectOnlyOption = data.length <= 1;
                    response($.map(data, function(item, index) {
                        return {
                            label: item.label,
                            value: item.value,
                            selected: index == 0
                        };
                    }));
                }).fail(function() { // Maybe login cookie problem
                    url = zabbixUrl + '/index.php';
                    $(location).attr('href', url); // Redirect to zabbix login page
                });
            }
        },

        {
            selector: '.step2',
            requires: ['.step1'],
            source: function(request, response) {
                $.getJSON('app.php', request, function(data) {
                    var selectOnlyOption = data.length <= 1;
                    response($.map(data, function(item, index) {
                        return {
                            label: item.label,
                            value: item.value,
                            selected: selectOnlyOption
                        };
                    }));
                });
            }
        },
        {
            selector: '.step3',
            requires: ['.step1', '.step2'],
            requireAll: true,
            source: function(request, response) {
                $.getJSON('app.php', request, function(data) {
                    response($.map(data, function(item, index) {
                        return {
                            label: item.label,
                            value: item.value,
                            selected: index == 0
                        };
                    }));
                });
            },
            onChange: function(event, value, requiredValues, requirementsMet){
                url = generateUrl();
        loadIframe('zabbixifr', url);
            }
        }
    ]
});


