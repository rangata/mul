<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laravel</title>

    <!-- Latest compiled and minified CSS -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/vuetify/dist/vuetify.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>
<div id="app">
    {{--<v-app>--}}
        {{--<v-navigation-drawer--}}
                {{--fixed--}}
                {{--:clipped="$vuetify.breakpoint.mdAndUp"--}}
                {{--app--}}
                {{--v-model="drawer"--}}
        {{-->--}}
            {{--<v-list>--}}
                {{--<v-list-tile @click="router.push('/')">--}}
                    {{--Списък с пациенти--}}
                {{--</v-list-tile>--}}
                {{--<v-list-tile>wef--}}
                {{--</v-list-tile>--}}
            {{--</v-list>--}}
        {{--</v-navigation-drawer>--}}
        {{--<v-toolbar--}}
                {{--color="blue darken-3"--}}
                {{--dark--}}
                {{--app--}}
                {{--:clipped-left="$vuetify.breakpoint.mdAndUp"--}}
                {{--fixed--}}
        {{-->--}}
            {{--<v-toolbar-title style="width: 300px" class="ml-0 pl-3">--}}
                {{--<v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>--}}
                {{--<span class="hidden-lg-only">ФДМ - Пловдив </span>--}}
            {{--</v-toolbar-title>--}}
        {{--</v-toolbar>--}}
        {{--<router-view></router-view>--}}

    {{--</v-app>--}}
    <app></app>
</div>

<script src="{{ asset('js/app.js') }}"></script>
{{--TODO: Should be removed when app goes in production mode. Raganta 9.8.2018--}}
{{--@if(config('app.env') == 'local')--}}
    {{--<script src="http://localhost:35729/livereload.js"></script>--}}
{{--@endif--}}
</body>
</html>
