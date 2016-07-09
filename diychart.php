<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <title>
            圖表產生器
        </title>
        <link rel="stylesheet" href="bootstrap.min.css"/>
        <link rel="stylesheet" href="style.css">
        <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.full.min.js"></script>
        <script src="select.js"></script>
        <script src="main.js"></script>
        <script>
            google.charts.load('current', {packages: ["line","corechart","geochart","table"]});
			$(document).ready(function () {
	           $("input").change(function(){
				if ($('input:radio:checked[name="kind"]').val()!=undefined && $('input:radio:checked[name="search"]').val()!=undefined) {
					$("#search").attr('disabled', false);
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
	            chart(kind);
	            $("html,body").animate({
		            scrollTop: 590
		        }, 500);
            }
        </script>
        
    </head>
    <body>
    <?php 
      require "header.php";
    ?>
    <div id="condition">
        <form class="form-inline">
            <h3>
                條件輸入
            </h3>
            <label for="gov">
                政府
            </label>
            <select class="form-control" type="text" id="gov"/>     
            </select>
            <label for="id">
                識別碼
            </label>
            <input class="form-control" type="text" id="id"/>
            
            <label for="plan_name">
                計畫名稱
            </label>
            <input class="form-control" type="text" id="plan_name"/>
            
            <label for="report_name">
                報告名稱
            </label>
            <input class="form-control" type="text" id="report_name"/>
            
           
            <label for="office">
                主辦機關
            </label>
            <select class="form-control" type="text" id="office"/>
            </select>
            <label for="member_name">
                人員姓名
            </label>
            <select class="form-control" type="text" id="member_name"/>
            </select>
            <label for="member_office">
                人員機關
            </label>
            <input class="form-control" type="text" id="member_office"/>
            
            <label for="member_unit">
                人員單位
            </label>
            <input class="form-control" type="text" id="member_unit"/>
            
            <label for="member_job">
                人員職稱
            </label>
            <input class="form-control" type="text" id="member_job"/>            
        </form>
        <form class="form-inline">
        <h3>條件輸入</h3>
            <label for="start_date">
                起始日期
            </label>
            <input class="form-control" type="date" id="start_date"/>
            
            <label for="end_date">
                結束日期
            </label>
            <input class="form-control" type="date" id="end_date"/>
            
            <label for="area">
                前往地區
            </label>
            <select class="form-control" type="text" id="area" placeholder="如：香港、日本"/>
            </select>
            
            <label for="visit">
                參訪機關
            </label>
            <input class="form-control" type="text" id="visit"/>
            
            <label for="topic_cat">
                主題分類
            </label>
            <select class="form-control" type="text" id="topic_cat"/>
            </select>
            <label for="adm_cat">
                施政分類
            </label>
            <select class="form-control" type="text" id="adm_cat"/>
            </select>
            <label for="summary">
                報告摘要
            </label>
            <input class="form-control" type="text" id="summary"/>
            
            <label for="keyword">
                關鍵詞
            </label>
            <input class="form-control" type="text" id="keyword"/>
            
        </form>
        <form class="form-inline">
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
        
        <button class="send btn btn-success" onclick="makechart();" disabled="disable">
            生成圖表
        </button>
        <div id="diychart"></div>
        <?php 
            require "footer.php";
        ?>
    </body>
</html>
