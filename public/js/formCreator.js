/**
 * Created by xuman on 15-7-1.
 */

var Former = {
    formTamplate  : '<form class="form"></form>',
    tableTemplate : '<table class="k-table table"></table>',
    rowTemplate   : '<tr><td style="text-align: right;"></td><td class="formItem"></td></tr>',
    Kendos        : ['kendoDropDownList','kendoNumericTextBox'],
    inputs        : ['checkbox','text','password'],
    itemTemplate  : {
        'kendoDropDownList' : '<input name="#name#">',
        'input' : '<input name="#name#" type="#type#">',
        'button' : '<button class="k-button">提交</button>'
    },
    create : function (data, $container) {
        var me = this;
        var form = $(me.formTamplate);
        var table = $(me.tableTemplate);
        $.each(data, function(i, n){
            var row = $(me.rowTemplate);
            row.find('td:first').html(n.label + "：");

            if(me.inputs.indexOf(n.type) != -1) {
                var template = me.itemTemplate.input;
                template = template.replace('#name#', n.name);
                template = template.replace('#type#', n.type);
                var element = $(template);
                row.find(".formItem").append(element);
            } else {
                var template = me.itemTemplate[n.type];
                template = template.replace('#name#', n.name);
                var element = $(template);
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
        form.submit(function(){
            $.post(
                '',
                $(this).serialize(),
                function(ret){

                },
                'json'
            );
            return false;
        });
        if($container) {
            $container.append(form);
        } else {
            $('body').append(form);
        }


    }
}