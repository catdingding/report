<!DOCTYPE html>
<html lang="zh-Hant-TW">
    <head>
        <meta charset="UTF-8"/>
        <title>
            統計資料
        </title>
        <base href="/report/" target="_blank" />
        <link rel="stylesheet" href="bootstrap.min.css"/>
        <link rel="stylesheet" href="style.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js">
        </script>
        <script src="https://www.gstatic.com/charts/loader.js">
        </script>
        <script src="main.js">
        </script>
        <script>
            google.charts.load('current', {packages: ["line","corechart","geochart","table"]});

            $(document).on("click", ".navbar li", function() {
            $(".navbar li").removeClass('select');
            $(this).addClass("select");
            $(".tab").removeClass('select');
            $(".tab:eq(" + $(this).index() + ")").addClass('select');
            });

            var element={
                type:"",
                value:""
            }

            google.charts.setOnLoadCallback(drawChart);

            function drawChart () {
                chart(year);
                chart(month);
                chart(day);
                chart(office);
                chart(topic_cat);
                chart(gov);
                chart(area);
                rank(year);
                rank(month);
                rank(day);
                rank(office);
                rank(topic_cat);
                rank(gov);
                rank(area);
            }

        </script>
    </head>
    <body>
        <?php
        require "header.php";
        ?>
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
                <div id="year_rank" class="rank">
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
                <div id="month_rank" class="rank">
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
                <div id="day_rank" class="rank">
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
                <div id="office_rank" class="rank">
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
                <div id="topic_cat_rank" class="rank">
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
                <div id="gov_rank" class="rank">
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
                <div id="area_rank" class="rank">
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
