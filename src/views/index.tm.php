<?php
header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST');

header("Access-Control-Allow-Headers: X-Requested-With");
?>
<html>
<head>

    <link rel="icon" type="image/png" href="favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="favicon-16x16.png" sizes="16x16" />
    <link href='css/typography.css' media='screen' rel='stylesheet' type='text/css'/>
    <link href='css/reset.css' media='screen' rel='stylesheet' type='text/css'/>
    <link href='css/screen.css' media='screen' rel='stylesheet' type='text/css'/>
    <link href='css/reset.css' media='print' rel='stylesheet' type='text/css'/>
    <link href='css/print.css' media='print' rel='stylesheet' type='text/css'/>

    <script src='lib/object-assign-pollyfill.js' type='text/javascript'></script>
    <script src='lib/jquery-1.8.0.min.js' type='text/javascript'></script>
    <script src='lib/jquery.slideto.min.js' type='text/javascript'></script>
    <script src='lib/jquery.wiggle.min.js' type='text/javascript'></script>
    <script src='lib/jquery.ba-bbq.min.js' type='text/javascript'></script>
    <script src='lib/handlebars-2.0.0.js' type='text/javascript'></script>
    <script src='lib/lodash.min.js' type='text/javascript'></script>
    <script src='lib/backbone-min.js' type='text/javascript'></script>
    <script src='swagger-ui.js' type='text/javascript'></script>
    <script src='lib/highlight.9.1.0.pack.js' type='text/javascript'></script>
    <script src='lib/highlight.9.1.0.pack_extended.js' type='text/javascript'></script>
    <script src='lib/jsoneditor.min.js' type='text/javascript'></script>
    <script src='lib/marked.js' type='text/javascript'></script>
    <script src='lib/swagger-oauth.js' type='text/javascript'></script>

    <!-- enabling this will enable oauth2 implicit scope support -->
    <script src='lang/translator.js' type='text/javascript'></script>
    <script src='lang/zh-cn.js' type='text/javascript'></script>

    <script type="text/javascript">
        $(function () {
            var url = window.location.search.match(/url=([^&]+)/);
            if (url && url.length > 1) {
                url = decodeURIComponent(url[1]);
            } else {
                url = "http://petstore.swagger.io/v2/swagger.json";
            }

            hljs.configure({
                highlightSizeThreshold: 5000
            });

            // Pre load translate...
            if(window.SwaggerTranslator) {
                window.SwaggerTranslator.translate();
            }

            var docTitle = "<?php echo $docTitle; ?>";
            var docUrl = "<?php echo $urlToDocs; ?>";
            $('.logo__title').html(docTitle);
            $('.authorize__btn').parent('div').remove();
            loadDoc(docUrl);
        });

        function log() {
            if ('console' in window) {
                console.log.apply(console, arguments);
            }
        }

        function loadDoc(value){

            window.swaggerUi = new SwaggerUi({
                url: value,
                dom_id: "swagger-ui-container",
                supportedSubmitMethods: ['get', 'post', 'put', 'delete', 'patch'],
                onComplete: function(swaggerApi, swaggerUi){
                    if(typeof initOAuth == "function") {
                        initOAuth({
                            clientId: "your-client-id",
                            clientSecret: "your-client-secret-if-required",
                            realm: "your-realms",
                            appName: "your-app-name",
                            scopeSeparator: ",",
                            additionalQueryStringParams: {}
                        });
                    }

                    if(window.SwaggerTranslator) {
                        window.SwaggerTranslator.translate();
                    }
                },
                onFailure: function(data) {
                    log("Unable to Load SwaggerUI");
                },
                docExpansion: "none",
                jsonEditor: false,
                defaultModelRendering: 'schema',
                showRequestHeaders: false
            });

            window.swaggerUi.load();
        }
    </script>
</head>

<body class="swagger-section">
<div id='header'>
    <div class="swagger-ui-wrap">
        <img class="logo__img" alt="swagger" height="30" width="30" src="favicon-32x32.png" /><span class="logo__title">API 文档</span></a>
        <form id='api_selector'>
            <div class='input hidden'><input placeholder="http://example.com/api" id="input_baseUrl" name="baseUrl" type="text" style="display: none;"/></div>
            <div id='auth_container'></div>
        </form>
    </div>
</div>

<div id="message-bar" class="swagger-ui-wrap" data-sw-translate>&nbsp;</div>
<div id="swagger-ui-container" class="swagger-ui-wrap"></div>
</body>
</html>

