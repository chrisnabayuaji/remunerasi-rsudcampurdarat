<!DOCTYPE html>
<html lang="id">

<head>
  @include('app::template.header')

  @include('app::template.scripts')
</head>

<body>

  @include('app::template.sidebar')

  @include('app::template.topbar')

  @include($content)

  @include('app::template.modals')
</body>

</html>