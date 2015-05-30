<!DOCTYPE html>
<html ng-app>
<head>
    <title>{{ isset($title) ? $title : 'battle-calc' }}</title>
    {{ HTML::style('//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css') }}
    {{ HTML::script('//code.jquery.com/jquery-1.9.1.js') }}
    {{ HTML::script('//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js') }}
</head>
<body>
@yield('menu')
<div class="container">
    @yield('content')
</div>
</body>
</html>