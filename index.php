<?php 
    $gov           = htmlentities($_GET['gov'], ENT_QUOTES);
    $office        = htmlentities($_GET['office'], ENT_QUOTES);
    $area          = htmlentities($_GET['area'], ENT_QUOTES);
    $topic_cat     = htmlentities($_GET['topic_cat'], ENT_QUOTES);
    $down          = htmlentities($_GET['down'], ENT_QUOTES);
    function option($value){
        global $$value;
        return $$value ?"<option selected value='".$$value."'>".$$value."</option>":"";
    }
?>
<!DOCTYPE html>
<html lang="en">
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
        var first=<?php echo $down ? 1 : 0 ; ?>;
        var page = 1;
        var max_page;
        $(document).ready(function() {
            list();    
        });
        function send() {
            page=1;
            list();
        }
        </script>
    </head>
    <body>
        <?php require "header.php"; ?>
        <div id="condition">
            <form class="form-inline">
                <h3>
                    條件輸入
                </h3>
                <label for="gov">
                    政府
                </label>
                <select class="form-control" type="text" id="gov" placeholder="如：中央政府、臺中市"/>
                <?php echo option('gov'); ?></select>
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
                <?php echo option('office'); ?></select>
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
                <h3>
                    條件輸入
                </h3>
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
                <?php echo option('area'); ?></select>
                <label for="visit">
                    參訪機關
                </label>
                <input class="form-control" type="text" id="visit"/>
                
                <label for="topic_cat">
                    主題分類
                </label>
                <select class="form-control" type="text" id="topic_cat"/>
                <?php echo option('topic_cat'); ?></select>
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
        <?php
            require "footer.php";
        ?>
    </body>
</html>
