<!DOCTYPE html>
<html lang="zh-Hant-TW">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0">
        <title>
            圖表產生器
        </title>
        <link rel="stylesheet" href="bootstrap.min.css"/>
        <link rel="stylesheet" href="style.css">
        <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script src="main.js"></script>
        <script src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.full.min.js"></script>
        <script src="select.js"></script>
        <script>
            if (filename==='onlychart.php' || filename==='imgchart.php') {
                var css = document.createElement('link');
                css.setAttribute("rel","stylesheet");
                css.setAttribute("type","text/css");
                css.setAttribute("href",'clearchart.css');
                document.getElementsByTagName("head")[0].appendChild(css);
            }

            google.charts.load('current', {packages: ["line","corechart","geochart","table"]});
            google.charts.setOnLoadCallback(ready);

            function ready() {
                googlechart=1;
            }

			$(document).ready(function () {
                $("input").change(function(){
                    if ($('input:radio:checked[name="kind"]').val()!=undefined && $('input:radio:checked[name="search"]').val()!=undefined) {
                        $(".send").attr('disabled', false);
                    };
                });
	        });

            function makechart () {
	            var kind = eval($('input:radio:checked[name="kind"]').val());
	            $(".chart").remove();
	            $("#diychart").append('<div class="chart" id="'+kind.name+'_chart"><div class="loading"></div></div>');
	            if (kind.name=="area") {
	            	$("#diychart").append('<div class="chart" id="map"><div class="loading"></div></div>');
	            	$("#diychart").append('<div class="chart" id="map_table"><div class="loading"></div></div>');
	            };
	            $("html,body").animate({
		            scrollTop: $('#diychart').offset().top
		        }, 500);
                condition.obj={};
                chart(kind);
            }
        </script>
    </head>
    <body>
    <?php 
      require "header.php";
    ?>
    <div id="condition">
        <?php 
          require 'form_condition.php';
        ?>
        <form class="form-inline open">
            <div class="hamburger"></div>
            <h3>
                圖表選擇
            </h3>
            <input class="form-control" type="radio" id="area_chart_type" name="kind" value="area"/>
            <label for="area_chart_type">
                地區分布
            </label>
            
            <input class="form-control" type="radio" id="day_chart_type" name="kind" value="day"/>
            <label for="day_chart_type">
                出國日長分布
            </label>
            
            <input class="form-control" type="radio" id="office_chart_type" name="kind" value="office"/>
            <label for="office_chart_type">
                主辦機關分布
            </label>
            
            <input class="form-control" type="radio" id="topic_cat_chart_type" name="kind" value="topic_cat"/>
            <label for="topic_cat_chart_type">
                主題分布
            </label>
            
            <input class="form-control" type="radio" id="year_chart_type" name="kind" value="year"/>
            <label for="year_chart_type">
                年份分布
            </label>
            
            <input class="form-control" type="radio" id="month_chart_type" name="kind" value="month"/>
            <label for="month_chart_type">
                月份分布
            </label>
            
            <input class="form-control" type="radio" id="gov_chart_type" name="kind" value="gov"/>
            <label for="gov_chart_type">
                政府分布
            </label>
            
            <h3>
                搜尋要求
            </h3>
            <input class="form-control" type="radio" id="equal" name="search" checked="checked" value="equal"/>
            <label for="equal">
                完全符合
            </label>
            
            <input class="form-control" type="radio" id="similar" name="search" value="similar"/>
            <label for="similar">
                部分符合
            </label>
        </form>

    </div>
        <div class="sample">
            <span>推薦搜索：</span>
            <button class="btn btn-info" onclick="sample('area=中國大陸','search=equal','kind=year')">中國大陸：年份分布</button>
            <button class="btn btn-info" onclick="sample('topic_cat=公共工程','kind=area','search=equal')">公共工程：地區分布</button>
            <button class="btn btn-info" onclick="sample('office=高級中學','kind=office','search=similar')">高級中學：主辦機關分布</button>
            <button class="btn btn-info" onclick="sample('gov=苗栗縣','kind=year','search=equal')">苗栗縣：年份分布</button>
        </div>
        
        <button class="send btn btn-success" onclick="makechart();" disabled="disable">
            生成圖表
        </button>
        <div id="diychart"></div>
        <div id="disqus_thread"></div>
            <script>
            $(document).ajaxStop(function () {
                if (disqus===0) {
                    var d = document, s = d.createElement('script');
                    s.src = '//gong-wu-chu-guo-bao-gao-cha-xun-tong-ji-wang.disqus.com/embed.js';
                    s.setAttribute('data-timestamp', +new Date());
                    (d.head || d.body).appendChild(s);
                    disqus=1;
                }
            });
            </script>
        <?php 
            require "footer.php";
        ?>
    </body>
</html>
