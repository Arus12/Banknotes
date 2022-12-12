<!DOCTYPE html>
<html lang="cs">

<head>
    <title>Počet bankovek</title>
    <link href="../css/style.css" rel="stylesheet" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="Jan Černý" />
</head>

<body>
    <header>
        <?php
        $obj = new Banknotes();
        echo ($obj->get_result());
        ?>
        </div>
</body>

</html>

<?php
class Banknotes
{
    public $money;
    public $currency;
    public function __construct()
    {
        $this->money = $_POST["value"];
        $this->currency = $_POST["currency"];
    }

    public function get_result()
    {
        if ($this->get_value() != null and $this->get_value() != "" and $this->get_value() == intval($this->get_value())) {
            if ($this->get_value() == 0) {
                return $this->print_zero();
            } else if ($this->get_value() < 0) {
                return $this->print_negative_number();
            } else if ($this->get_currency() == "CZE") {
                return $this->print($this->banknotes_CZE($this->get_value()), $this->get_value());
            } else {
                return $this->print($this->banknotes_CZE($this->transfer_to_CZE()), $this->get_value());
            }
        } else {
            return $this->print_error();
        }
    }
    private function banknotes_CZE($money)
    {
        $banknotes = array(5000 => 0, 2000 => 0, 1000 => 0, 500 => 0, 200 => 0, 100 => 0, 50 => 0, 20 => 0, 10 => 0, 5 => 0, 2 => 0, 1 => 0);
        foreach ($banknotes as $key => $value){
            $banknotes[$key] = intval($money / $key);
            $money = $money % $key;
            }
            return $banknotes;
       }


    private function print($banknotes, $money)
    {
        $result = ('<h1>Výsledek pro hodnotu ' . $money . ' ' . $this->get_currency() . '</h1></header><div class = "main">');
        foreach ($banknotes as $key => $banknote) {
            if ($banknote > 0) {
                $result = $result . ('<div class="banknote"><p>' . $banknote . "x</p>");
                $result = $result . ('<div class = "img_banknote"><img src="../img/banknotes/'. $key .'kc.jpg" alt="'. $key .' kč" width="150" height="75"></div></div>');
            }
        }
        $result = $result .
            '</div><div class="buttons"><div class = "nav_button">
        <form action="../index.html"><input type="hidden"/><button>
        <span>Zpět k zadávání hodnoty</span></button></form></div>
        </div><footer>
        <h2>Kalkulačka</h2>
      </footer>';
        return $result;
    }

    private function print_error()
    {
        return '<h1>Chyba</h1></header>
        <div class = "error_main">
        <div class = "error"><p>Nezadali jste správnou hodnotu</p></div>
        <div class="buttons"><div class = "nav_button">
        <form action="../index.html"><input type="hidden"/><button>
        <span>Zpět k zadávání hodnoty</span></button></form></div>
        </div></div>';
    }

    private function print_zero()
    {
        return '<h1>Výsledek pro hodnotu 0</h1></header>
        <div class = "error_main">
        <div class = "error"><p>Zadali jste hodnotu 0</p></div>
        <div class="buttons"><div class = "nav_button">
        <form action="../index.html"><input type="hidden"/><button>
        <span>Zpět k zadávání hodnoty</span></button></form></div>
        </div></div>';
    }

    private function print_negative_number()
    {
        return '<h1>Výsledek pro hodnotu menší jak nula</h1></header>
        <div class = "error_main">
        <div class = "error"><p>Zadali jste hodnotu, která je menší jak nula</p></div>
        <div class="buttons"><div class = "nav_button">
        <form action="../index.html"><input type="hidden"/><button>
        <span>Zpět k zadávání hodnoty</span></button></form></div>
        </div></div>';
    }


    private function transfer_to_CZE()
    {
        include_once "simplehtmldom/simple_html_dom.php";
        $html = file_get_html("https://www.kurzy.cz/kurzy-men/aktualni/czk-" . strtolower($this->get_currency()));
        if(is_int(intval($html->find("span.clrred",0)->innertext)) and intval($html->find("span.clrred",0)->innertext) > 0){
            $course=$html->find("span.clrred",0)->innertext;
            return intval($this->money * $course);
        }else{
            die('<h1>Chyba</h1></header><div class = "error_main">
            <div class = "error"><p>Monentálně není možné načíst data o aktuálním kurzu.</p></div>
            <div class="buttons"><div class = "nav_button">
            <form action="../index.html"><input type="hidden"/><button>
            <span>Zpět k zadávání hodnoty</span></button></form></div>
            </div></div>');
        }
        }

    public function get_value()
    {
        if ($this->money != null and $this->money != "" and $this->money == intval($this->money)) {
            return $this->money;
        }
    }

    public function get_currency()
    {
        return $this->currency;
    }
}
