<!DOCTYPE html>
<html lang="zh-Hant-TW">
    <head>
        <meta charset="UTF-8"/>
        <title>
            公務出國報告查詢統計網
        </title>
        <link rel="stylesheet" href="bootstrap.min.css"/>
        <link rel="stylesheet" href="style.css"/>
        <link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.full.min.js"></script>
        <script src="select.js"></script>
        <script src="main.js"></script>
        <script>
        var page = 1;
        var max_page;
        $(document).ready(function() {
            report();
        });
        
        function send() {
            page=1;
            report();
        }
        </script>
    </head>
    <body>
        <?php require "header.php";?>
        <div id="condition">
            <?php 
                require 'form_condition.php';
            ?>
            <form class="form-inline">
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
            <button class="btn btn-info" onclick="sample('office=郵政','plan_name=出國觀摩','search=similar')">郵政+出國觀摩</button>
            <button class="btn btn-info" onclick="sample('gov=臺北市','office=高級中學','search=similar')">臺北市+高級中學</button>
            <button class="btn btn-info" onclick="sample('area=中國大陸','office=大學','search=similar')">中國大陸+大學</button>
            <button class="btn btn-info" onclick="sample('member_name=劉政鴻','search=equal')">劉政鴻</button>
        </div>
        <button class="send btn btn-success" onclick="send();">
            查詢
        </button>
        <div class="search">
        <div class="page">
            <button class="btn btn-info">
                上一頁
            </button>
            <span></span>
            <button class="btn btn-info">
                下一頁
            </button>
        </div>
        <table id="list">
            <thead>
                <tr>
                    <th>
                        識別碼
                    </th>
                    <th>
                        報告名稱
                    </th>
                    <th>
                        人員
                    </th>
                    <th>
                        出國期間
                    </th>
                    <th>
                        主辦機關
                    </th>
                    <th>
                        前往地區
                    </th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
        <div class="page">
            <button class="btn btn-info">
                上一頁
            </button>
            <span></span>
            <button class="btn btn-info">
                下一頁
            </button>
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
