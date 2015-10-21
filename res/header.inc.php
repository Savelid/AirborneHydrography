
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title><?php echo $titel ?></title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  	<div class="container">
  	<header class="my_header">
      <div class="my_header__inner">
        <img class="my_header__logo" src="img/Leica_Geosystems.png" alt="Leica geosystems logo">
        <h1 class="my_header__title"><?php echo $titel ?></h1>

<!--         <a id="menu" class="my_header__menu">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M2 6h20v3H2zm0 5h20v3H2zm0 5h20v3H2z"/>
          </svg>
        </a> -->

      </div>
    </header>
    <nav id="drawer" class="my_nav">
      <ul class="my_nav_list">
        <li class="my_nav_item"><a href="/overview/">Overview</a></li>
        <li class="my_nav_item"><a href="/systems/">Systems</a></li>
        <li class="my_nav_item"><a href="/parts/">Parts</a></li>
        <li class="my_nav_item"><a href="/log/">Log</a></li>
      </ul>
    </nav>
    <main>