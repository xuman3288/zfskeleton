/**
 * Created by xuman on 15-7-1.
 */
/*
//config example
[
    {
        "type": "kendoDropDownList",
        "label": "\u6e20\u9053",
        "name": "channel",
        "config": {
            "dataTextField": "name",
            "dataValueField": "id",
            "dataSource": [
                {
                    "name": "\u5b89\u5353\u6df7\u670d",
                    "id": 1
                },
                {
                    "name": "IOS\u5b98\u670d",
                    "id": 2
                },
                {
                    "name": "\u817e\u8baf",
                    "id": 3
                }
            ]
        }
    },
    {
        "type": "kendoDropDownList",
        "label": "\u6e38\u620f",
        "name": "game",
        "config": {
            "dataTextField": "name",
            "dataValueField": "id",
            "dataSource": [
                {
                    "name": "\u6b66\u6781\u5929\u4e0b",
                    "id": 1
                },
                {
                    "name": "\u5f81\u9014",
                    "id": 2
                },
                {
                    "name": "\u9ed1\u732b\u8b66\u957f",
                    "id": 3
                }
            ]
        }
    },
    {
        "type": "text",
        "label": "\u72b6\u6001",
        "name": "state",
        "config": []
    },
    {
        "type": "kendoDateTimePicker",
        "label": "\u66f4\u65b0\u65f6\u95f4",
        "name": "datetime",
        "config": {
            "format": "yyyy\/MM\/dd HH:mm:ss"
        }
    }
]
*/
var Former = {
    formTamplate: '<form class="form"></form>',
    tableTemplate: '<table class="k-table table"></table>',
    rowTemplate: '<tr><td style="text-align: right;"></td><td class="formItem"></td></tr>',
    Kendos: ['kendoDropDownList', 'kendoNumericTextBox'],
    inputs: ['checkbox', 'text', 'password', 'hidden'],
    itemTemplate: {
        'kendoInput': '<input name="#name#">',
        'input': '<input name="#name#" type="#type#">',
        'button': '<button class="k-button">提交</button>'
    },
    create: function (api, data, $container, callback) {
        var me = this;
        var form = $(me.formTamplate);
        var table = $(me.tableTemplate);
        var template = element = null;
        $.each(data, function (i, n) {
            var row = $(me.rowTemplate);
            row.find('td:first').html(n.label + "：");

            if (me.inputs.indexOf(n.type) != -1) {
                template = me.itemTemplate.input;
                template = template.replace('#name#', n.name);
                template = template.replace('#type#', n.type);
                element = $(template);
                row.find(".formItem").append(element);
            } else {
                template = me.itemTemplate.kendoInput;
                template = template.replace('#name#', n.name);
                element = $(template);
                row.find(".formItem").append(element);
                element[n.type](n.config);
            }
            table.append(row);
        });

        //submit button
        var row = me.rowTemplate;
        var template = me.itemTemplate['button'];
        row = $(row.replace('#label#', ''));
        row.find(".formItem").append(template);
        table.append(row);
        form.append(table);
        form.submit(function () {
            $.post(
                api,
                $(this).serialize(),
                function (ret) {
                    callback && callback(ret);
                },
                'json'
            );
            return false;
        });
        if ($container) {
            $container.append(form);
        } else {
            $('body').append(form);
        }


    }
}