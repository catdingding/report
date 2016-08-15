<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <?php
            require "connect_mysql.php";
            $id     = $_GET["id"];
            $sql    = "SELECT report.*,gov.link FROM report LEFT JOIN gov ON report.gov=gov.name WHERE id='$id' ";
            $result = $db->query($sql);
            $row    = $result->fetch();
        ?>
        <title>
            <?php echo $row["report_name"]; ?>
        </title>
        <base href="/report/"/>
        <link rel="stylesheet" href="bootstrap.min.css"/>
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <?php
            require "header.php";
        ?>
        <table class="report">
            <tr>
                <th>
                    政府
                </th>
                <td>
                    <?php echo "<a target='_blank' href='element/gov/" . $row["gov"] . "'>" . $row["gov"] . "</a>"; ?>
                </td>
                <th>
                    報告識別碼
                </th>
                <td>
                    <?php echo $row["id"]; ?>
                </td>
            </tr>
            <tr>
                <th>
                    計畫名稱
                </th>
                <td>
                    <?php echo $row["plan_name"]; ?>
                </td>
                <th>
                    報告名稱
                </th>
                <td>
                    <?php echo $row["report_name"]; ?>
                </td>
            </tr>
            <tr>
                <th>
                    主辦機關
                </th>
                <td>
                    <?php echo "<a target='_blank' href='element/office/" . $row["office"] . "'>" . $row["office"] . "</a>"; ?>
                </td>
                <th>
                    前往地區
                </th>
                <td>
                    <?php
                    $area=[];
                    for ($i=0; $i <count(explode(",", $row["area"])) ; $i++) { 
                        $area[$i]="<a target='_blank' href='element/area/" . explode(",", $row["area"])[$i] . "'>" . explode(",", $row["area"])[$i] . "</a>";
                    }
                    echo implode('、', $area);
                    ?>
                </td>
            </tr>
            <tr>
                <th>
                    主題分類
                </th>
                <td>
                    <?php echo "<a target='_blank' href='element/topic_cat/" . $row["topic_cat"] . "'>" . $row["topic_cat"] . "</a>"; ?>
                </td>
                <th>
                    施政分類
                </th>
                <td>
                    <?php echo $row["adm_cat"]; ?>
                </td>
            </tr>
            <tr>
                <th>
                    出國期間
                </th>
                <td>
                    <?php echo $row["start_date"] . "至" . $row["end_date"]; ?>
                </td>
                <th>
                    出國人數
                </th>
                <td>
                    <?php echo $row["member_num"]; ?>
                </td>
            </tr>
        </table>
        <table class="report">
            <tr>
                <th>
                    人員姓名
                </th>
                <th>
                    人員機關
                </th>
                <th>
                    人員單位
                </th>
                <th>
                    人員職稱
                </th>
                <th>
                    人員官職
                </th>
            </tr>
            <?php
for ($i = 0; $i< count(explode(",", $row["member_name"])); $i++) {
    echo "<tr>";
    echo "<td>" . explode(",", $row["member_name"])[$i] . "</td>";
    echo "<td>" . "<a target='_blank' href='element/office/" . explode(",", $row["member_office"])[$i] . "'>" . explode(",", $row["member_office"])[$i] . "</a>" . "</td>";
    echo "<td>" . explode(",", $row["member_unit"])[$i] . "</td>";
    echo "<td>" . explode(",", $row["member_job"])[$i] . "</td>";
    echo "<td>" . explode(",", $row["member_level"])[$i] . "</td>";
    echo "</tr>";
}
?>
            </table>
            <table class="report">
                <tr>
                    <th>
                        參訪機關
                    </td>
                    <td>
                        <?php echo $row["visit"]; ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        關鍵詞
                    </td>
                    <td>
                        <?php echo $row["keyword"]; ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        報告摘要
                    </td>
                    <td>
                        <?php echo $row["summary"]; ?>
                    </td>
                </tr>
                <tr>
                    <th>電子全文檔下載
                    </th>
                    <td>
                    <?php 
                        for ($i=0; $i <count(explode(",", $row["main_file"])) ; $i++) { 
                            echo "<a class='btn btn-primary' href='";
                            echo explode(",", $row["main_file"])[$i];
                            echo "'>";
                            echo "電子全文檔".($i+1);
                            echo "</a>";
                        }
                    ?>
                    </td>
                </tr>
                <tr>
                    <th>附件檔下載
                    </th>
                    <td>
                    <?php
                        if ($row["other_file"]) {
                            for ($i=0; $i <count(explode(",", $row["other_file"])) ; $i++) { 
                                echo "<a class='btn btn-primary' href='";
                                echo explode(",", $row["other_file"])[$i];
                                echo "'>";
                                echo "附件檔".($i+1);
                                echo "</a>";
                            }
                        }else{
                            echo "無";
                        }
                    ?>
                    </td>
                </tr>
                <tr>
                    <th>
                        原文連結
                    </th>
                    <td>
                        <a class="btn btn-primary" href=
                            <?php
                            if ($row["gov"] === "中央政府") {
                                echo "http://report.nat.gov.tw/ReportFront/report_detail.jspx?sysId=" . $row["id"];
                            } else {
                                echo $row["link"] . "/OpenFront/report/report_detail.jsp?sysId=" . $row["id"];
                            }
                            ?>
                        >
                            按我前往
                        </a>
                    </td>
                </tr>
            </table>
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
