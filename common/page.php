<?php

namespace common;

include_once "menu.php";
class APage
{
    public function __construct(AContent $content){
        $this->start_page($content);
        $this->create_menu();
        $this->create_title($content);
        $this->show_content($content);
        $this->create_footer();
        $this->finish_page();
    }

    private function start_page(AContent $content){
        ?>
        <html lang="ru">
        <head>
            <title><?php $content->create_title() ?></title>
            <link rel="stylesheet" href="css/main.css" type="text/css"/>
            <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
            <script type="application/javascript" src="js/bootstrap.bundle.min.js"></script>
        </head>
        <body>
        <?php
    }

    private function create_menu()
    {
        ?>
        <nav class="navbar bg-primary navbar-expand-lg" data-bs-theme="dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><img src="img/logo.png" alt="логотип" width="64"/></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
<!--                        <a class="nav-link active" aria-current="page" href="#">Home</a>-->
<!--                        <a class="nav-link" href="#">Features</a>-->
<!--                        <a class="nav-link" href="#">Pricing</a>-->
<!--                        <a class="nav-link disabled" aria-disabled="true">Disabled</a>-->
                        <?php
                            $menu = Menu::get_menu_data();
                            if (!empty($menu)) {
                                foreach ($menu as $item) {
                                    print ("<a class='nav-link' href='${item['href']}'>");
                                    print($item["name"]);
                                    print('</a>');
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </nav>
        <?php
    }

    private function create_title(AContent $content)
    {
        print "<div class='title'>";
        $content->create_title();
        print "</div>";
    }

    private function show_content(AContent $content)
    {
        print "<div class='content'>";
        $content->create_content();
        print "</div>";
    }

    private function create_footer(){
        print "<div class='footer'>";
        print "Подвал";
        print "</div>";
    }

    private function finish_page()
    {
        print "</body></html>";
    }
}