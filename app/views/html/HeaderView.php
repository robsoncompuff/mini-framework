<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="description" content="<?= $this->getUrl()->getDescription();?>" />
    <meta name="keywords" content="<?= $this->getUrl()->getKeywords();?>" />
    <meta name="robots" content="<?= $this->getUrl()->getRobots();?>">
    <meta name="author" content="Robson Morais">
    <link rel="shortcut icon" href="//static.bn-static.com/img-42105/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/app/assets/css/core/Reset.css?version=<?=$version?>">
    <link rel="stylesheet" href="/app/assets/css/core/Pattern.css?version=<?=$version?>">
    <link rel="stylesheet" href="/app/assets/css/core/Layout.css?version=<?=$version?>">
    <link rel="stylesheet" href="/app/assets/css/core/Util.css?version=<?=$version?>">
    <link rel="stylesheet" href="/app/assets/css/<?=mb_strtolower($this->getUrl()->getController())?>/<?=ucfirst($this->getUrl()->getAction())?>.css?version=<?=$version?>">
    <script src="/app/assets/js/core/jquery.min.js?version=<?=$version?>"></script>
    <script src="/app/assets/js/core/modernizr.js?version=<?=$version?>"></script>
    <script src="/app/assets/js/<?=mb_strtolower($this->getUrl()->getController())?>/<?=ucfirst($this->getUrl()->getAction())?>.js?version=<?=$version?>" ></script>
    <title><?= $this->getUrl()->getTitle();?></title>
</head>
<body>
    <header id="header">
        <h1 class="title">Desafio Olx</h1>
    </header>
    <nav id="nav">
        <div class="container clearfix">
            <ul>
                <li>
                    <figure>
                        <img src="/app/assets/svg/addWhite.svg">
                    </figure>
                    <a href="/">Novo Atendimento</a>
                </li>
                <li>
                    <figure>
                        <img src="/app/assets/svg/listWhite.svg">
                    </figure>
                    <a href="/atendimentos">Atendimentos</a>
                </li>
            </ul>
        </div>
    </nav>
    <main id="main">
        <div class="container">