<!DOCTYPE html>
<html lang="zh-Hant-TW">
    <head>
        <?php
			require "connect_mysql.php";
			$element_kind      = htmlentities($_GET['element_kind'], ENT_QUOTES);
			$element_value     = htmlentities($_GET['element_value'], ENT_QUOTES);
		?>
        <meta charset="utf-8"/>
        <title>
            <?php echo $element_value."公務出國概況"; ?>
        </title>
        <base href="/report/" target="_blank"/>
        <link href="bootstrap.min.css" rel="stylesheet"/>
        <link href="style.css" rel="stylesheet"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js">
        </script>
        <script src="https://www.gstatic.com/charts/loader.js">
        </script>
        <script src="main.js">
        </script>
        <script>
		    var element={
            	kind:<?php echo "\"$element_kind\""; ?>,
            	value:<?php echo "\"$element_value\""; ?>
            }

		    $(document).on("click", ".navbar li", function() {
	            $(".navbar li").removeClass('select');
	            $(this).addClass("select");
	            $(".tab").removeClass('select');
	            $(".tab:eq(" + $(this).index() + ")").addClass('select');
            });
            
            function drawChart () {
            	for (var key in kind_list) {
	            	rank(eval(kind_list[key]));
	            	chart(eval(kind_list[key]));
	            }
            }
            var kind_list=['year','month','day','office','topic_cat','gov','area'];

            $(document).ready(function() {
            	switch(element.kind){
	            	case 'office':
	            		array_remove(kind_list,'office');
	            		array_remove(kind_list,'gov');
	            		$('.navbar li').eq(3).css('display', 'none');
	            		$('.navbar li').eq(5).css('display', 'none');
	            		break;
	            	case 'area':
	            		array_remove(kind_list,'area');
	            		$('.navbar li').eq(6).css('display', 'none');
	            		break;
	            	case 'topic_cat':
	            		array_remove(kind_list,'topic_cat');
	            		$('.navbar li').eq(4).css('display', 'none');
	            		break;
	            	case 'year':
	            		array_remove(kind_list,'year');
	            		$('.navbar li').eq(0).css('display', 'none');
	            		$('.navbar li').eq(1).addClass('select');
	            		$('.tabcontainer .tab').eq(0).css('display', 'none');
	            		$('.tabcontainer .tab').eq(1).addClass('select');
	            		break;
	            	case 'gov':
	            		array_remove(kind_list,'gov');
	            		$('.navbar li').eq(5).css('display', 'none');
	            		break;
	            }
            });
            
            google.charts.load('current', {packages: ["corechart","geochart","table"]});
		    google.charts.setOnLoadCallback(drawChart);
        </script>
    </head>
    <body>
        <?php
    		require "header.php";
    		echo "<input id=" . $element_kind . " type=hidden value=" . $element_value . ">";
    	?>
    	<input type="radio" name="search" style="display:none;" checked="checked" value="equal">
        <ul class="navbar">
            <li class="select">
                年份分布
            </li>
            <li class="">
                月份分布
            </li>
            <li class="">
                天數分布
            </li>
            <li class="">
                單位分布
            </li>
            <li class="">
                主題分布
            </li>
            <li class="">
                政府分布
            </li>
            <li class="">
                地區分布
            </li>
        </ul>
        <div class="tabcontainer">
            <div class="tab select">
                <h2 class="chart">
                    年份分布
                </h2>
                <div class="chart" id="year_chart">
                    <div class="loading">
                    </div>
                </div>
                <div class="rank" id="year_rank">
                </div>
            </div>
            <div class="tab">
                <h2 class="chart">
                    月份分布
                </h2>
                <div class="chart" id="month_chart">
                    <div class="loading">
                    </div>
                </div>
                <div class="rank" id="month_rank">
                </div>
            </div>
            <div class="tab">
                <h2 class="chart">
                    天數分布
                </h2>
                <div class="chart" id="day_chart">
                    <div class="loading">
                    </div>
                </div>
                <div class="rank" id="day_rank">
                </div>
            </div>
            <div class="tab">
                <h2 class="chart">
                    單位分布
                </h2>
                <div class="chart" id="office_chart">
                    <div class="loading">
                    </div>
                </div>
                <div class="rank" id="office_rank">
                </div>
            </div>
            <div class="tab">
                <h2 class="chart">
                    主題分布
                </h2>
                <div class="chart" id="topic_cat_chart">
                    <div class="loading">
                    </div>
                </div>
                <div class="rank" id="topic_cat_rank">
                </div>
            </div>
            <div class="tab">
                <h2 class="chart">
                    政府分布
                </h2>
                <div class="chart" id="gov_chart">
                    <div class="loading">
                    </div>
                </div>
                <div class="rank" id="gov_rank">
                </div>
            </div>
            <div class="tab">
                <h2 class="chart">
                    前往地區分布
                </h2>
                <div class="chart" id="area_chart">
                    <div class="loading">
                    </div>
                </div>
                <div class="chart" id="map">
                    <div class="loading">
                    </div>
                </div>
                <div class="chart" id="map_table">
                    <div class="loading">
                    </div>
                </div>
                <div class="rank" id="area_rank">
                </div>
            </div>
        </div>
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
