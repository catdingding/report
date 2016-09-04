<!DOCTYPE html>
<html lang="zh-Hant-TW">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
        <title>
            公務出國報告查詢統計網
        </title>
        <base href="/report/" target="_blank"/>
        <link href="bootstrap.min.css" rel="stylesheet"/>
        <link href="test.css" rel="stylesheet"/>
    </head>
    <body>
        <?php
    		require "header.php";
    	?>
        <article>
            <h2>
                專案簡介
            </h2>
            <p>
                政府的公務出國考察經常被認為是在消化預算[注1]或是出國旅遊[注2]，在資源分配上也備受質疑[注3]。本計畫希望能夠整合各公務出國資訊網的資料，使公務出國的狀況能以
                <a href="http://catding.twbbs.org/report/statistics.php">
                    更直觀方式呈現
                </a>
                ，也提供
                <a href="http://catding.twbbs.org/report/search.php">
                    更方便的管道
                </a>
                查詢報告。
                <br/>
                <br/>
                [注1]：到2015年底為止的統計，10~12月的出國次數即佔了超過33%，而1~3月僅佔11%多。
                <br/>
                [注2]：如各種"傑出人員出國觀摩"。
                <br/>
                [注3]：地方政府的公務出國，第一名台北市的次數及超過第二至五名的加總(高雄、台中、新北、屏東)。
                <br/>
                注意：以上統計均不含金門縣及新竹市(金門縣無相關網站、新竹市開放的欄位過少)
                <br/>
            </p>
        </article>
        <article id="site">
            <h2>
                本站內容
            </h2>
            <div>
                <div>
                	<h3>報告查詢</h3>
                    <p>
                        自行設定各式條件，檢索中央與地方政府的出國資料及報告。
                    </p>
                    <p>
                        注意同名同姓者！
                    </p>
                    <a class="btn btn-success" href="search.php">
                        報告查詢
                    </a>
                </div>
                <div>
                	<h3>圖表生成</h3>
                    <p>
                        想知道哪一年開始大量前往中國?
                    </p>
                    <p>
                        想知道公共工程都前往哪裡考察?
                    </p>
                    <p>
                        按我就對了！
                    </p>
                    <a class="btn btn-success" href="diychart.php">
                        圖表生成
                    </a>
                </div>
                <div>
                	<h3>統計資料</h3>
                    <p>
                        以圖表和列表，呈現所有資料的年分、月份、地區、主辦機關.......的分布，讓你一目瞭然。
                    </p>
                    <a class="btn btn-success" href="statistics.php">
                        統計資料
                    </a>
                </div>
                <div>
                	<h3>物件頁面</h3>
                    <p>
                        有發現單位、地區、年分、主題、政府都能點下去嗎?
                    </p>
                    <p>
                        各物件的頁面會清楚呈現出國狀況，有如專屬的統計資料頁面喔。
                    </p>
                    <a class="btn btn-success" href="element/office/%E8%8B%97%E6%A0%97%E7%B8%A3%E6%94%BF%E5%BA%9C">
                        查看範例
                    </a>
                </div>
            </div>
        </article>
        <article>
            <h2>
                統計成果
            </h2>
            <ul>
                <li>
                    一到四季公務出國次數的比例分別約為：11%、24%、31%、33%
                </li>
                <li>
                    最常前往的地區：美國、中國大陸、日本
                </li>
                <li>
                    公務出國次數的逐年變化：到2013年基本上皆逐年上漲，但2014、2015皆略為降低
                </li>
                <li>
                    政府分布：中央佔75%、台北市佔8%、高雄市佔3.4%、台中市佔1.8%
                </li>
                <li>
                    主題分布：前三名為教育文化(<span data-tooltip="教育文化和教育都是同層級的分類，原因不明">含教育</span>)、財政經濟、公共工程
                </li>
                <li>	
					以上統計均不含金門縣及新竹市
                </li>
            </ul>
        </article>
        <article>
            <h2>
                參與專案
            </h2>
            <p>
                無論您是UX/UI設計師、前/後端工程師、有公務出國考察經驗者，或是任何有想法、有興趣的人，都歡迎加入本專案。
                <br/>
                <br/>
                g0v hackpad：
                <a href="https://g0v.hackpad.com/E0G6gZDQ2ZZ">
                    https://g0v.hackpad.com/E0G6gZDQ2ZZ
                </a>
                <br/>
                facebook 社團：
                <a href="https://www.facebook.com/groups/1125748634116081/">
                    https://www.facebook.com/groups/1125748634116081/
                </a>
            </p>
        </article>
        <div id="disqus_thread">
        </div>
        <script>
            (function () {
                var d = document, s = d.createElement('script');
                s.src = '//gong-wu-chu-guo-bao-gao-cha-xun-tong-ji-wang.disqus.com/embed.js';
                s.setAttribute('data-timestamp', +new Date());
                (d.head || d.body).appendChild(s);
            })();
        </script>
        <?php 
    		require "footer.php";
    	?>
    </body>
</html>
