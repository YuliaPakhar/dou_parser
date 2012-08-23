<?
//require_once "./php-webdriver-bindings-0.8.0/phpwebdriver/WebDriver.php";
//require("./php-webdriver-bindings-0.8.0/phpwebdriver/LocatorStrategy.php");

require_once './webdriver/WebDriver.php';
require_once './webdriver/WebDriver/Driver.php';
require_once './webdriver/WebDriver/WebElement.php';
require_once './webdriver/WebDriver/MockDriver.php';
require_once './webdriver/WebDriver/WebElement.php';
require_once './webdriver/WebDriver/MockElement.php';


$driver = WebDriver_Driver::InitAtLocal("4444", "firefox");
$driver->set_implicit_wait(5000);
error_reporting(0);
for($page = 235; $page < 900; $page++) {
    echo "Page $page\n";
    $driver->load("http://www.developers.org.ua/users/$page/");
    $elements = $driver->get_all_elements('//ul[@id="usersList"]//a[@class="name"]');
    $num = sizeof($elements);
    for($i = 0; $i < $num; $i++) {
        $content = '';
        $elements = $driver->get_all_elements('//ul[@id="usersList"]//a[@class="name"]');
        $element = $elements[$i];
        if(!$element) {
            continue;
        }
        $element->click();
        if(!$driver->is_element_present("//div[@class='contact vcard']")) {
            $driver->go_back();
        }
        else {
            $vCard = $driver->get_element("//div[@class='contact vcard']");
            //$vCard = $driver->get_element("//div[@class='descr']");
            //$vCard = $driver->get_element("/html/body/div[2]/div[5]");
            if(!is_object($vCard->element_id)) {
                $end_time = time() + 2;
                do {
                  @$content .= $vCard->get_text();
                } while (time() < $end_time);
            }
            $content = mb_strtolower($content, 'utf8');
            if(stripos($content, 'javascript') !== false || stripos($content, 'php') !== false ) {
                echo $driver->get_url()."\n";
                $driver->go_back();
            }
            else {
                $driver->go_back();
            }
        }
    }
}
$driver->quit();
