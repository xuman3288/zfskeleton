/**
 * Created by xuman on 15-7-1.
 */

var Former = {
    tableTemplate : '<table class="k-table table"></table>',
    rowTemplate   : '<tr><td>#label#</td><td class="formItem"></td></tr>',
    Kendos        : ['kendoDropDownList','kendoNumericTextBox'],
    itemTemplate  : {
        'kendoDropDownList' : '<input name="#name#">'
    },
    create : function (data) {
        var table = $(this.tableTemplate);
        var me = this;
        $.each(data, function(i, n){
            var row = me.rowTemplate;

            row = $(row.replace('#label#', n.label));
            var element = $(me.itemTemplate[n.type]);
            console.log(n.type);

            row.find(".formItem").append(element);
            if(n.type == 'kendoDropDownList') {
                element.kendoDropDownList(n.config);
            }

            table.append(row);
        });
        $('body').append(table);
    }
}