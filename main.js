//常用函數
function array_remove(array,value) {
    for (var i = 0; i < array.length; i++) {
        if (array[i]==value) {
            array.splice(i,1);
            i--;
        }
    }
}


//通用配置
var ready = [];

function kind() {
    this.name = arguments[0];
    this.page = 1;
    this.zh = arguments[1];
    this.chart_title = arguments[2];
    this.unit = arguments[3];
}
var year = new kind('year', '年份', '考察年份分布圖', '年');
var month = new kind('month', '月份', '考察月份分布圖', '月');
var day = new kind('day', '天數', '考察天數分布圖(1~50天)', '天');
var office = new kind('office', '主辦機關', '前50次數主辦機關分布圖', '');
var topic_cat = new kind('topic_cat', '主題', '考察主題分布圖', '');
var gov = new kind('gov', '政府', '政府分布圖', '');
var area = new kind('area', '地區', '前50考察地區分布圖', '');
//一般搜尋區
$(document).on("click", ".search .page button:nth-of-type(1)", function() {
    page -= 1;
    list();
});
//下一頁
$(document).on("click", ".search .page button:nth-of-type(2)", function() {
    page += 1;
    list();
});
$(document).on("click", ".search .page span button", function() {
    page = parseInt($(this).prev("input").val());
    if (page > max_page) {
        page = max_page;
    };
    list();
});

function list() {
    if (first != 0) {
        $("html,body").animate({
            scrollTop: 490
        }, 500);
    };
    first = 1;
    $.getJSON("api/search.php", {
        gov: $("#gov").val(),
        id: $("#id").val(),
        plan_name: $("#plan_name").val(),
        report_name: $("#report_name").val(),
        report_date: $("#report_date").val(),
        report_page: $("#report_page").val(),
        office: $("#office").val(),
        member_name: $("#member_name").val(),
        member_office: $("#member_office").val(),
        member_unit: $("#member_unit").val(),
        member_job: $("#member_job").val(),
        member_level: $("#member_level").val(),
        member_num: $("#member_num").val(),
        start_date: $("#start_date").val(),
        end_date: $("#end_date").val(),
        area: $("#area").val(),
        visit: $("#visit").val(),
        type: $("#type").val(),
        keyword: $("#keyword").val(),
        note: $("#note").val(),
        topic_cat: $("#topic_cat").val(),
        adm_cat: $("#adm_cat").val(),
        summary: $("#summary").val(),
        search: $('input:radio:checked[name="search"]').val(),
        page: page
    }, function(json) {
        max_page = json.summary.max_page;
        if (page <= 1) {
            $(".page button:nth-child(1)").attr('disabled', true);
        } else {
            $(".page button:nth-child(1)").attr('disabled', false);
        }
        if (page >= max_page) {
            $(".page button:nth-child(3)").attr('disabled', true);
        } else {
            $(".page button:nth-child(3)").attr('disabled', false);
        }
        $("#list tbody tr").remove();
        $(".page span").empty();
        $(".page span").append('第<input type="number" min="1" max="' + json.summary.max_page + '" value="' + json.summary.page + '">頁/共' + json.summary.max_page + '頁<button class="btn btn-primary">GO</button>');
        $.each(json.data, function(index, item) {
            var area = item.area;
            area = area.split(",");
            for (var i = 0; i < area.length; i++) {
                area[i] = "<a href='element/area/" + area[i] + "'>" + area[i] + "</a>";
            };
            area = area.join(",");
            $("#list tbody").append("<tr><td>" + item.id + "</td>" + "<td><a target='_blank' href='report/" + item.id + "'>" + item.report_name + "</a></td>" + "<td>" + item.member_name + "</td>" + "<td>" + item.date + "</td>" + "<td><a href='element/office/" + item.office + "'>" + item.office + "</a></td>" + "<td>" + area + "</td></tr>");
        })
    });
}
//排行區
//未使用：搜尋
function search(kind) {
    kind.page = 1;
    rank(kind);
}

function change_page(kind) {
    $("html,body").animate({
        scrollTop: $('#' + kind.name + '_rank').offset().top - 70
    }, 500);
    $("#" + kind.name + "_rank button").attr('disabled', true);
}
//上一頁
$(document).on("click", ".rank .page button:nth-of-type(1)", function() {
    var kind = eval($(this).parents(".rank").data('kind'));
    change_page(kind);
    kind.page -= 1;
    rank(kind);
});
//下一頁
$(document).on("click", ".rank .page button:nth-of-type(2)", function() {
    var kind = eval($(this).parents(".rank").data('kind'));
    change_page(kind);
    kind.page += 1;
    rank(kind);
});
//跳頁
$(document).on("click", ".rank .page span button", function() {
    var kind = eval($(this).parents(".rank").data('kind'));
    change_page(kind);
    kind.page = parseInt($(this).prev("input").val());
    if (kind.page > kind.max_page) {
        kind.page = kind.max_page;
    };
    rank(kind);
});
//排行生成
function rank(kind) {
    if ($.inArray(kind.name, ready) === -1) {
        ready.push(kind.name);
        $("#" + kind.name + "_rank").data('kind', kind.name);
        if ($.inArray(kind.name, ['year', 'month', 'day']) >= 0) {
            th_rank = "";
            th_list = "";
        } else {
            th_rank = "<th>排行</th>";
            th_list = "<th>報告連結</th>";
        }
        $("#" + kind.name + "_rank").append('<div class = "page"></div><table class="list"><thead><tr>' + th_rank + '<th>' + kind.zh + '</th><th>考察次數</th>' + th_list + '</tr></thead><tbody></tbody></table><div class = "page"></div>');
    }
    $.getJSON("api/rank.php", {
        gov: $("#gov").val(),
        id: $("#id").val(),
        plan_name: $("#plan_name").val(),
        report_name: $("#report_name").val(),
        report_date: $("#report_date").val(),
        report_page: $("#report_page").val(),
        office: $("#office").val(),
        member_name: $("#member_name").val(),
        member_office: $("#member_office").val(),
        member_unit: $("#member_unit").val(),
        member_job: $("#member_job").val(),
        member_level: $("#member_level").val(),
        member_num: $("#member_num").val(),
        start_date: $("#start_date").val(),
        end_date: $("#end_date").val(),
        area: $("#area").val(),
        visit: $("#visit").val(),
        type: $("#type").val(),
        keyword: $("#keyword").val(),
        note: $("#note").val(),
        topic_cat: $("#topic_cat").val(),
        adm_cat: $("#adm_cat").val(),
        summary: $("#summary").val(),
        year: $("#year").val(),
        search: $('input:checked[name="search"]').val(),
        kind: kind.name,
        page: kind.page
    }, function(json) {
        kind.max_page = parseInt(json.summary.max_page);
        //頁數控制區
        $("#" + kind.name + "_rank .page").empty();
        if (kind.page <= 1) {
            $("#" + kind.name + "_rank .page").append('<button class="btn btn-info" disabled="true">上一頁</button>');
        } else {
            $("#" + kind.name + "_rank .page").append('<button class="btn btn-info">上一頁</button>');
        }
        if (kind.page >= kind.max_page) {
            $("#" + kind.name + "_rank .page").append('<button class="btn btn-info" disabled="true">下一頁</button>');
        } else {
            $("#" + kind.name + "_rank .page").append('<button class="btn btn-info">下一頁</button>');
        }
        $("#" + kind.name + "_rank .page").append('<span>第<input type="number" min="1" max="' + json.summary.max_page + '" value="' + json.summary.page + '">頁/共' + json.summary.max_page + '頁<button class="btn btn-primary">GO</button></span>');
        //列表
        $("#" + kind.name + "_rank .list tbody").empty();
        var start, td_rank, td_list;
        if (kind.name === 'area') {
            start = 1;
        } else {
            start = (kind.page - 1) * 25 + 1;
        }
        $.each(json.data, function(index, item) {
            if ($.inArray(kind.name, ['year', 'month', 'day']) >= 0) {
                td_rank = "";
                td_list = "";
            } else {
                td_rank = "<td>" + (start + index) + "</td>";
                td_list = "<td><a href ='index.php?" + element.kind + "=" + element.value + "&" + kind.name + "=" + item.name + "&down=1'>" + "報告列表" + "</td>";
                if (!element.kind) {
                    td_list = "<td><a href ='index.php?" + kind.name + "=" + item.name + "&down=1'>" + "報告列表" + "</td>";
                };
            }
            $("#" + kind.name + "_rank .list tbody").append("<tr>" + td_rank + "<td><a href ='element/" + kind.name + "/" + item.name + " ' > " + item.name + kind.unit + "</a></td>" + "<td>" + item.number + "</td>" + td_list + "</tr>")
        })
    });
}
//圖表區
function chart(kind) {
    $.getJSON("api/chart.php", {
        gov: $("#gov").val(),
        id: $("#id").val(),
        plan_name: $("#plan_name").val(),
        report_name: $("#report_name").val(),
        report_date: $("#report_date").val(),
        report_page: $("#report_page").val(),
        office: $("#office").val(),
        member_name: $("#member_name").val(),
        member_office: $("#member_office").val(),
        member_unit: $("#member_unit").val(),
        member_job: $("#member_job").val(),
        member_level: $("#member_level").val(),
        member_num: $("#member_num").val(),
        start_date: $("#start_date").val(),
        end_date: $("#end_date").val(),
        area: $("#area").val(),
        visit: $("#visit").val(),
        type: $("#type").val(),
        keyword: $("#keyword").val(),
        note: $("#note").val(),
        topic_cat: $("#topic_cat").val(),
        adm_cat: $("#adm_cat").val(),
        summary: $("#summary").val(),
        year: $("#year").val(),
        search: $('input:checked[name="search"]').val(),
        kind: kind.name
    }, function(json) {
        if (kind.name === "area") {
            var data = new google.visualization.DataTable(json.core);
        } else {
            var data = new google.visualization.DataTable(json);
        }
        var options = {
            hAxis: {
                title: kind.zh
            },
            vAxis: {
                title: '考察次數'
            },
            title: kind.chart_title,
            focusTarget: 'category',
            chartArea: {
                'width': '65%',
                'height': '75%'
            },
        };
        var chart = new google.visualization.ColumnChart(document.getElementById(kind.name + '_chart'));
        chart.draw(data, options);
        //地圖
        if (kind.name === "area") {
            var data = new google.visualization.DataTable(json.map);
            var options = {
                tooltip: {
                    trigger: 'both',
                },
                colorAxis: {
                    minValue: 0,
                    colors: ['#F6F2ED', '#1A6F1F']
                }
            };
            var chart = new google.visualization.GeoChart(document.getElementById('map'));
            chart.draw(data, options);
            var table = new google.visualization.Table(document.getElementById('map_table'));
            table.draw(data, {
                width: '100%'
            });
            google.visualization.events.addListener(table, 'select', function() {
                chart.setSelection(table.getSelection());
            });
            google.visualization.events.addListener(chart, 'select', function() {
                table.setSelection(chart.getSelection());
            });
        };
    });
}