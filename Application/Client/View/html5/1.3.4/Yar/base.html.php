<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{$pageTitle}</title>
    <link href="http://zui.sexy/docs/css/zui.min.css" rel="stylesheet">
    <style>
        h1, h2, h3 {
            margin:0;
            padding: 10px 0;
        }
        .container {
            margin-top: 5px;
            border: 1px solid #ccc;
        }
        td a {word-break: keep-all;}
        dt,dl,pre{margin:0 !important;}
        .article-content table>thead>tr>th, .article>.content table>thead>tr>th {vertical-align: top;}
        pre.code {
            padding: 0;
            border: 0px;
            border-radius: 0px;
        }
    </style>
    <script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
</head>
<body>
<div class="container">
    <block name="body"></block>
</div>
<block name="script"></block>
</body>
</html>
