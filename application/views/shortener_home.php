<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Сократить ссылку онлайн без регистраций и смс</title>
    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url()."assets/vendor/bootstrap/bootstrap.min.css" ?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url()."assets/css/style.css" ?>" rel="stylesheet">
</head>

<body class="text-center">
    <div class="form-shortener">
        <h1 class="h3 mb-3 font-weight-normal">Сократить ссылку</h1>
        <div class="form-group">
            <label for="link" class="sr-only">Вставьте длинный URL-адрес</label>
            <input type="text" name="link" id="link" class="form-control" placeholder="Вставить длинный URL-адрес" required autofocus>
        </div>

        <button class="btn btn-lg btn-primary btn-block shorten" type="button">Укоротить ссылку</button>

        <input type="hidden" name="<?=$csrf['name'];?>" value="<?=$csrf['hash'];?>" />
        <div class="alert alert-primary hide"></div>
        <div class="alert alert-danger hide"></div>

        <p class="mt-5 mb-3 text-muted">&copy; <a href="https://spb.hh.ru/resume/6118d0a0ff05afe9560039ed1f6b3970377234" target="_blank">Titov Sergey</a>  <?php echo date('Y') ?></p>
    </div>

    <!-- js scripts -->
    <script src='<?php echo base_url()."assets/vendor/jquery.js" ?>' type="text/javascript"></script>
    <script src='<?php echo base_url()."assets/js/shortener.js" ?>' type="text/javascript"></script>
</body>
</html>
