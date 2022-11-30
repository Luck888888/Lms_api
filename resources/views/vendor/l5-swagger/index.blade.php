<!-- HTML for static distribution bundle build -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ __("API documentation") }}</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Source+Code+Pro:300,600|Titillium+Web:400,600,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ l5_swagger_asset("default", 'swagger-ui.css') }}" >
    <link rel="icon" type="image/png" href="{{ l5_swagger_asset("default", 'favicon-32x32.png') }}" sizes="32x32" />
    <link rel="icon" type="image/png" href="{{ l5_swagger_asset("default", 'favicon-16x16.png') }}" sizes="16x16" />
    <style>
        html
        {
            box-sizing: border-box;
            overflow: -moz-scrollbars-vertical;
            overflow-y: scroll;
        }
        *,
        *:before,
        *:after
        {
            box-sizing: inherit;
        }

        body {
            margin:0;
            background: #fafafa;
        }
    </style>
</head>

<body>

<div id="swagger-ui"></div>

<script src="{{ l5_swagger_asset("default", 'swagger-ui-bundle.js') }}"> </script>
<script src="{{ l5_swagger_asset("default", 'swagger-ui-standalone-preset.js') }}"> </script>
{{ config("l5-swagger.$documentation.paths.docs_json") }}
<script>
    window.onload = function() {
        // Build a system
        const ui = SwaggerUIBundle({
            dom_id: '#swagger-ui',

            url: "{{ route('l5-swagger.default.docs', config("l5-swagger.documentations.$documentation.paths.docs_json")) }}",
            operationsSorter: {!! isset($operationsSorter) ? '"' . $operationsSorter . '"' : 'null' !!},
            configUrl: {!! isset($configUrl) ? '"' . $configUrl . '"' : 'null' !!},
            validatorUrl: {!! isset($validatorUrl) ? '"' . $validatorUrl . '"' : 'null' !!},
            oauth2RedirectUrl: "{{ route('l5-swagger.default.oauth2_callback') }}",

            requestInterceptor: function(request) {
                {{--request.headers['X-CSRF-TOKEN'] = '{{ csrf_token() }}';--}}
                    return request;
            },

            presets: [
                SwaggerUIBundle.presets.apis,
                SwaggerUIStandalonePreset
            ],

            plugins: [
                SwaggerUIBundle.plugins.DownloadUrl
            ],

            layout: "BaseLayout",
            persistAuthorization: {!! config('l5-swagger.defaults.persist_authorization') ? 'true' : 'false' !!},
        })

        window.ui = ui
    }
</script>
</body>

</html>
